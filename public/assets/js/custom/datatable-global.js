; (function ($) {
    "use strict";

    // Store original DataTable reference
    const _DataTable = $.fn.DataTable;

    // Default custom pagination settings
    const customDefaults = {
        customPagination: true,
        paginationSelector: null,
        classes: {
            container: 'd-flex flex-wrap align-items-center justify-content-between gap-2 mt-24',
            paginationList: 'pagination d-flex flex-wrap align-items-center gap-2 justify-content-center',
            pageItem: 'page-item',
            pageLink: 'page-link fw-medium radius-4 border-0 px-10 py-10 d-flex align-items-center justify-content-center h-32-px w-32-px',
            active: 'bg-primary-600 text-white',
            inactive: 'bg-primary-5 text-secondary-light',
            navButton: 'bg-base text-secondary-light',
            disabled: 'disabled opacity-50 cursor-not-allowed'
        },
        icons: {
            prev: '<iconify-icon icon="ep:d-arrow-left" class="text-xl"></iconify-icon>',
            next: '<iconify-icon icon="ep:d-arrow-right" class="text-xl"></iconify-icon>'
        },

        searchSelector: '#dt-search-filter',
        lengthSelector: '#dt-legnth-filter',

    };

    // Override DataTable
    $.fn.DataTable = function (options) {

        const settings = $.extend(true, {}, customDefaults, options || {});
        const table = this;

        // Force minimal DOM since we handle pagination
        settings.dom = settings.dom || 'rt';

        // Capture user drawCallback
        const userDrawCallback = settings.drawCallback;

        settings.drawCallback = function (dtSettings) {
            const api = this.api();

            if (settings.customPagination) {
                buildPagination(api, table, settings);
            }

            if (typeof userDrawCallback === 'function') {
                userDrawCallback.call(this, dtSettings);
            }
        };

        // Init original DataTable
        const dtInstance = _DataTable.call(this, settings);

        // Bind pagination clicks once
        bindPaginationEvents(dtInstance, table, settings);

        bindExternalFilters(dtInstance, table, settings);

        // Return real DataTables API
        return dtInstance;
    };

    function resolvePaginationContainer(table, fallbackSelector) {

        // 1. Check for data-pagination attribute on the table
        const dataSelector = table.data('pagination');
        if (dataSelector && $(dataSelector).length) {
            return $(dataSelector);
        }

        // 2. Check for JS settings selector
        if (fallbackSelector && $(fallbackSelector).length) {
            return $(fallbackSelector);
        }

        // 3. Fallback: Check if we already created a dynamic div
        let $p = table.next('.custom-dt-pagination');

        // 4. Create new dynamic div if nothing else exists
        if (!$p.length) {
            $p = $('<div class="custom-dt-pagination"></div>');
            table.after($p);
        }
        return $p;
    }

    function buildPagination(api, table, settings) {

        const pageInfo = api.page.info();
        const c = settings.classes;
        const icons = settings.icons;

        const start = pageInfo.recordsDisplay > 0 ? pageInfo.start + 1 : 0;
        const end = pageInfo.end;
        const total = pageInfo.recordsDisplay;

        const $pagination = resolvePaginationContainer(table, settings.paginationSelector);

        let html = '';

        // Prev
        html += `
        <li class="${c.pageItem} ${pageInfo.page === 0 ? c.disabled : ''}">
            <a class="${c.pageLink} ${c.navButton}" href="javascript:void(0)" data-dt-action="prev">
                ${icons.prev}
            </a>
        </li>`;

        // Pages
        for (let i = 0; i < pageInfo.pages; i++) {
            html += `
            <li class="${c.pageItem}">
                <a class="${c.pageLink} ${i === pageInfo.page ? c.active : c.inactive}"
                   href="javascript:void(0)" data-dt-action="${i}">
                   ${i + 1}
                </a>
            </li>`;
        }

        // Next
        html += `
        <li class="${c.pageItem} ${pageInfo.page >= pageInfo.pages - 1 ? c.disabled : ''}">
            <a class="${c.pageLink} ${c.navButton}" href="javascript:void(0)" data-dt-action="next">
                ${icons.next}
            </a>
        </li>`;

        $pagination.html(`
            <div class="${c.container}">
                <span>Showing ${start} to ${end} of ${total} entries</span>
                <ul class="${c.paginationList}">
                    ${html}
                </ul>
            </div>
        `);
    }

    function bindPaginationEvents(dtInstance, table, settings) {

        const $pagination = resolvePaginationContainer(table, settings.paginationSelector);

        $pagination.off('click', 'a[data-dt-action]')
            .on('click', 'a[data-dt-action]', function (e) {

                e.preventDefault();

                const action = $(this).data('dt-action');

                if ($(this).parent().hasClass(settings.classes.disabled)) return;

                if (action === 'prev') dtInstance.page('previous').draw('page');
                else if (action === 'next') dtInstance.page('next').draw('page');
                else dtInstance.page(parseInt(action)).draw('page');
            });
    }


    function bindExternalFilters(dtInstance, table, settings) {

        // Read selectors from data attributes if provided
        const searchSel = table.data('dt-search') || settings.searchSelector;
        const lengthSel = table.data('dt-length') || settings.lengthSelector;

        // Bind search filter if exists
        if ($(searchSel).length) {
            $(searchSel).off('keyup.customDT')
                .on('keyup.customDT', function () {
                    dtInstance.search(this.value).draw();
                });
        }

        // Bind length filter if exists
        if ($(lengthSel).length) {
            $(lengthSel).off('change.customDT')
                .on('change.customDT', function () {
                    dtInstance.page.len(this.value).draw();
                });
        }
    }



})(jQuery);
