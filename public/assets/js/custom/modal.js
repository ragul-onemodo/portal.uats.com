/**
 * jQuery AjaxCrudModal Plugin
 * Laravel-Compatible Edition v2.1.0
 * Works with Laravel Resource Controllers + Bootstrap 5 + DataTables
 */
; (function ($, window, document, undefined) {
    "use strict";

    const pluginName = "ajaxCrudModal";

    const defaults = {
        createButton: null,
        modalSelector: '#crudModal',
        modalBodySelector: '#crudModalBody',
        modalTitleSelector: '#crudModalLabel',
        dataTable: null,

        routes: {
            create: null,
            edit: null,
            destroy: null
        },

        formSelector: '#crudForm, form[data-ajax-form]',
        submitBtnSelector: 'button[type="submit"]',

        entityName: 'Item',
        loadingHtml: '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>',
        errorHtml: '<div class="alert alert-danger">Failed to load form</div>',

        confirmDeleteMsg: 'Are you sure you want to delete this :entity?',
        successCreateMsg: ':entity created successfully',
        successUpdateMsg: ':entity updated successfully',
        successDeleteMsg: ':entity deleted successfully',
        errorGenericMsg: 'An error occurred',

        onFormLoaded: null,
        onSuccess: null
    };

    function AjaxCrudModal(element, options) {
        this.element = $(element);
        this.settings = $.extend(true, {}, defaults, options);
        this.uid = pluginName + '_' + Math.random().toString(36).substr(2, 9);
        this.init();
    }

    $.extend(AjaxCrudModal.prototype, {

        init: function () {
            this.table = this.settings.dataTable;
            this.$modal = $(this.settings.modalSelector);
            this.$modalBody = $(this.settings.modalBodySelector);
            this.$modalTitle = $(this.settings.modalTitleSelector);

            if (!this.$modal.length || !this.$modalBody.length) {
                console.error('AjaxCrudModal: Modal not found');
                return;
            }

            this.bindEvents();
        },

        bindEvents: function () {
            const self = this;
            const ns = '.' + this.uid;

            if (this.settings.createButton) {
                $(document).off('click' + ns, this.settings.createButton)
                    .on('click' + ns, this.settings.createButton, function (e) {
                        e.preventDefault();
                        self.openCreate();
                    });
            }

            this.element.off('click' + ns, '.btn-edit, [data-action="edit"]')
                .on('click' + ns, '.btn-edit, [data-action="edit"]', function (e) {
                    e.preventDefault();
                    const id = $(this).data('id');
                    if (id) self.openEdit(id);
                });

            this.element.off('click' + ns, '.btn-delete, [data-action="delete"]')
                .on('click' + ns, '.btn-delete, [data-action="delete"]', function (e) {
                    e.preventDefault();
                    const id = $(this).data('id');
                    if (id) self.deleteItem(id);
                });

            $(document).off('submit' + ns, this.settings.formSelector)
                .on('submit' + ns, this.settings.formSelector, function (e) {

                    if (self.$modal.data('owner-uid') !== self.uid) return;

                    e.preventDefault();
                    self.submitForm($(this));
                });
        },

        setModalOwnership: function () {
            this.$modal.data('owner-uid', this.uid);
        },

        openCreate: function () {
            this.setModalOwnership();
            this.loadForm(this.settings.routes.create, 'Create ' + this.settings.entityName);
        },

        openEdit: function (id) {
            this.setModalOwnership();
            const url = this.settings.routes.edit.replace(':id', id);
            this.loadForm(url, 'Edit ' + this.settings.entityName);
        },

        loadForm: function (url, title) {
            const self = this;

            this.$modalTitle.text(title);
            this.$modalBody.html(this.settings.loadingHtml);
            this.$modal.modal('show');

            $.get(url)
                .done(function (res) {
                    self.$modalBody.html(res);
                    if (typeof self.settings.onFormLoaded === 'function') {
                        self.settings.onFormLoaded(self.$modalBody.find('form'));
                    }
                })
                .fail(function () {
                    self.$modalBody.html(self.settings.errorHtml);
                });
        },

        /**
         * 🔧 Laravel-Compatible Submit
         * Always POST, let Laravel spoof PUT/PATCH via _method field
         */
        submitForm: function ($form) {
            const self = this;
            const url = $form.attr('action');
            const $btn = $form.find(this.settings.submitBtnSelector);
            const originalBtnText = $btn.html();

            $btn.prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm"></span> Saving...');

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: url,
                type: 'POST', // 🔥 Always POST for Laravel
                data: $form.serialize(), // includes _method=PUT or POST naturally
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    self.$modal.modal('hide');

                    console.log(self.table);

                    if (self.table) self.table.ajax.reload(null, false);

                    const spoofedMethod = $form.find('input[name="_method"]').val() || 'POST';
                    const msg = response.message || (
                        spoofedMethod.toUpperCase() === 'POST'
                            ? self.settings.successCreateMsg
                            : self.settings.successUpdateMsg
                    );

                    self.showNotification('success', self.replaceEntityPlaceholder(msg));

                    if (typeof self.settings.onSuccess === 'function') {
                        self.settings.onSuccess(response, spoofedMethod === 'POST' ? 'create' : 'update');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        self.handleValidationErrors(xhr.responseJSON?.errors || {});
                    } else {
                        self.showNotification('error',
                            xhr.responseJSON?.message || self.settings.errorGenericMsg
                        );
                    }
                },
                complete: function () {
                    $btn.prop('disabled', false).html(originalBtnText);
                }
            });
        },

        deleteItem: function (id) {
            const self = this;
            const msg = this.replaceEntityPlaceholder(this.settings.confirmDeleteMsg);

            $.confirm({
                title: 'Confirm Delete',
                content: msg,
                animation: 'zoom',
                closeAnimation: 'scale',
                buttons: {
                    cancel: function () { /* no-op */ },
                    confirm: {
                        text: 'Delete',
                        btnClass: 'btn-red',
                        action: function () {
                            const url = self.settings.routes.destroy.replace(':id', id);
                            const csrfToken = $('meta[name="csrf-token"]').attr('content');

                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': csrfToken },
                                success: function (response) {
                                    if (self.table) self.table.ajax.reload(null, false);
                                    const m = response.message || self.settings.successDeleteMsg;
                                    self.showNotification('success', self.replaceEntityPlaceholder(m));
                                },
                                error: function (xhr) {
                                    self.showNotification('error',
                                        xhr.responseJSON?.message || self.settings.errorGenericMsg
                                    );
                                }
                            });
                        }
                    }
                }
            });
        },


        handleValidationErrors: function (errors) {
            const $form = this.$modalBody.find('form');
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').remove();

            Object.entries(errors).forEach(([field, messages]) => {
                const $input = $form.find(`[name="${field}"], [name="${field}[]"]`);
                if ($input.length) {
                    $input.addClass('is-invalid');
                    const $container = $input.closest('.input-group').length
                        ? $input.closest('.input-group')
                        : $input;
                    $container.after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
                }
            });
        },

        showNotification: function (type, message) {
            if (window.toastr && window.toastr[type]) toastr[type](message);
            else alert(message);
        },

        replaceEntityPlaceholder: function (str) {
            return str.replace(/:entity/gi, this.settings.entityName.toLowerCase());
        }
    });

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new AjaxCrudModal(this, options));
            }
        });
    };

})(jQuery, window, document);
