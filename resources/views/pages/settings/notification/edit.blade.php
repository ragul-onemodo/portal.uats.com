<form id="crudForm" action="{{ route('settings.notification.store') }}" method="POST">
    @csrf

    @if (!empty($rule))
        <input type="hidden" name="id" value="{{ $rule->id }}">
    @endif

    <div class="row g-4">

        {{-- Target Type --}}
        <div class="col-md-4">
            <label class="form-label">
                Target Type <span class="text-danger">*</span>
            </label>
            <select name="target_type" id="target_type" class="form-select" required>
                <option value="">Select Type</option>
                <option value="entity" @selected(optional($rule)->target_type === 'entity')>Entity</option>
                <option value="device" @selected(optional($rule)->target_type === 'device')>Device</option>
            </select>
        </div>

        {{-- Target ID (Entity) --}}
        <div class="col-md-4 d-none" id="entityTargetWrapper">
            <label class="form-label">
                Entity <span class="text-danger">*</span>
            </label>
            <select name="target_id" id="entityTarget" class="form-select">
                <option value="">Select Entity</option>
                @foreach ($entities as $entity)
                    <option value="{{ $entity->id }}" @selected(optional($rule)->target_type === 'entity' && optional($rule)->target_id == $entity->id)>
                        {{ $entity->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Target ID (Device) --}}
        <div class="col-md-4 d-none" id="deviceTargetWrapper">
            <label class="form-label">
                Device <span class="text-danger">*</span>
            </label>
            <select name="target_id" id="deviceTarget" class="form-select">
                <option value="">Select Device</option>
            </select>
        </div>

        <input type="hidden" name="target_id" id="finalTargetId" value="{{ optional($rule)->target_id }}">

        {{-- Event --}}
        <div class="col-md-4">
            <label class="form-label">
                Event <small class="text-muted">(optional)</small>
            </label>
            <input type="text" name="event" class="form-control" value="{{ $rule->event ?? '' }}"
                placeholder="e.g. device_offline">
            <small class="text-muted">
                Leave empty to receive <b>all events</b>
            </small>
        </div>

        {{-- Channel --}}
        <div class="col-md-4">
            <label class="form-label">
                Channel <span class="text-danger">*</span>
            </label>
            <select name="channel" class="form-select" required>
                <option value="email" selected>Email</option>
            </select>
        </div>

        {{-- Status --}}
        <div class="col-md-4">
            <label class="form-label">Status</label>
            <div class="form-switch switch-success">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    @checked($rule->is_active ?? true)>
                <label class="form-check-label">Active</label>
            </div>
        </div>

        {{-- Recipients --}}
        <div class="col-12">
            <label class="form-label fw-semibold mb-2">
                Email Recipients <span class="text-danger">*</span>
            </label>

            <div id="recipient-wrapper">
                @php
                    $recipients = $rule->recipients ?? collect([null]);
                @endphp

                @foreach ($recipients as $i => $recipient)
                    <div class="row g-2 align-items-center recipient-row mb-2">
                        <div class="col-md-10">
                            <input type="email" name="recipients[{{ $i }}][value]" class="form-control"
                                value="{{ $recipient->recipient_value ?? '' }}" placeholder="email@example.com"
                                required>
                            <input type="hidden" name="recipients[{{ $i }}][type]" value="email">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm btn-remove-recipient w-100">
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addRecipient">
                + Add Recipient
            </button>
        </div>

    </div>

    <div class="modal-footer mt-4">
        <button type="submit" class="btn btn-primary-600">
            Save Notification Rule
        </button>
    </div>
</form>

{{-- <script>
    $(document).ready(function() {

        let index = {{ $recipients->count() ?? 1 }};

        $('#addRecipient').on('click', function() {
            $('#recipient-wrapper').append(`
            <div class="row g-2 align-items-center recipient-row mb-2">
                <div class="col-md-10">
                    <input type="email"
                           name="recipients[${index}][value]"
                           class="form-control"
                           placeholder="email@example.com"
                           required>
                    <input type="hidden"
                           name="recipients[${index}][type]"
                           value="email">
                </div>
                <div class="col-md-2">
                    <button type="button"
                            class="btn btn-danger btn-sm btn-remove-recipient w-100">
                        Remove
                    </button>
                </div>
            </div>
        `);

            index++;
        });

        $(document).on('click', '.btn-remove-recipient', function() {
            $(this).closest('.recipient-row').remove();
        });

    });
</script> --}}


<script>
    $(document).ready(function() {

        let index = {{ $recipients->count() ?? 1 }};

        function toggleTargetInputs(type) {
            $('#entityTargetWrapper, #deviceTargetWrapper').addClass('d-none');
            $('#entityTarget, #deviceTarget').prop('required', false);

            if (type === 'entity') {
                $('#entityTargetWrapper').removeClass('d-none');
                $('#entityTarget').prop('required', true);
            }

            if (type === 'device') {
                $('#deviceTargetWrapper').removeClass('d-none');
                $('#deviceTarget').prop('required', true);
                loadDevices();
            }
        }

        function loadDevices() {
            $.ajax({
                url: '{{ route('api.devices.list') }}', // YOU ALREADY USE THIS PATTERN
                type: 'GET',
                success: function(res) {
                    const $select = $('#deviceTarget');
                    $select.empty().append('<option value="">Select Device</option>');

                    res.data.forEach(device => {
                        $select.append(
                            `<option value="${device.id}"
                            ${device.id == {{ (int) ($rule->target_id ?? 0) }} ? 'selected' : ''}>
                            ${device.device_name}
                        </option>`
                        );
                    });
                }
            });
        }

        $('#target_type').on('change', function() {
            toggleTargetInputs(this.value);
        });

        // Init on edit
        toggleTargetInputs($('#target_type').val());

        // Recipients
        $('#addRecipient').on('click', function() {
            $('#recipient-wrapper').append(`
            <div class="row g-2 align-items-center recipient-row mb-2">
                <div class="col-md-10">
                    <input type="email"
                           name="recipients[${index}][value]"
                           class="form-control"
                           placeholder="email@example.com"
                           required>
                    <input type="hidden"
                           name="recipients[${index}][type]"
                           value="email">
                </div>
                <div class="col-md-2">
                    <button type="button"
                            class="btn btn-danger btn-sm btn-remove-recipient w-100">
                        Remove
                    </button>
                </div>
            </div>
        `);
            index++;
        });

        $(document).on('change', '#entityTarget', function(e) {
            $('#finalTargetId').val($(this).val())
        });
        $(document).on('change', '#deviceTarget', function(e) {
            $('#finalTargetId').val($(this).val())
        });

        $(document).on('click', '.btn-remove-recipient', function() {
            $(this).closest('.recipient-row').remove();
        });

    });
</script>
