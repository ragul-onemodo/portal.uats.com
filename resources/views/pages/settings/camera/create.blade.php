<form id="crudForm" action="{{ route('settings.cameras.store') }}" method="POST">
    @csrf

    <div class="row g-4">

        {{-- Entity --}}
        <div class="col-md-4">
            <label class="form-label">
                Entity <span class="text-danger">*</span>
            </label>
            <select name="entity_id" class="form-select" required>
                <option value="">Select Entity</option>
                @foreach ($entities as $entity)
                    <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Camera Name --}}
        <div class="col-md-4">
            <label class="form-label">
                Camera Name <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" class="form-control" required>
        </div>

        {{-- IP Address --}}
        <div class="col-md-4">
            <label class="form-label">
                IP Address <span class="text-danger">*</span>
            </label>
            <input type="text" name="ip_address" id="ip_address" class="form-control" placeholder="192.168.1.10"
                required>
        </div>

        {{-- Username --}}
        <div class="col-md-4">
            <label class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control">
        </div>

        {{-- Password --}}
        <div class="col-md-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        {{-- Snapshot Slug --}}
        <div class="col-md-4">
            <label class="form-label">
                Snapshot Slug <span class="text-danger">*</span>
            </label>
            <input type="text" name="snapshot_slug" id="snapshot_slug" class="form-control"
                placeholder="snapshot.jpg" required>

            <small class="text-muted d-block mt-1" id="snapshotPreview">
                Snapshot URL will be generated automatically
            </small>

            <input type="hidden" name="snapshot_url" id="snapshot_url">
        </div>

        {{-- Camera Role --}}
        <div class="col-12">
            <label class="form-label fw-semibold mb-2">Camera Role</label>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-switch switch-primary">
                        <input class="form-check-input camera-role" type="checkbox" name="is_primary" value="1">
                        <label class="form-check-label fw-medium">
                            Primary Camera
                        </label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-switch switch-info">
                        <input class="form-check-input camera-role" type="checkbox" name="is_secondary" value="1">
                        <label class="form-check-label fw-medium">
                            Secondary Camera
                        </label>
                    </div>
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block mt-1">
                        If neither is selected, camera will be treated as <b>Other</b>.
                    </small>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="col-md-4">
            <label class="form-label">Status</label>
            <div class="form-switch switch-success">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                <label class="form-check-label">Active</label>
            </div>
        </div>

    </div>

    <div class="modal-footer mt-4">
        <button type="submit" class="btn btn-primary-600">
            Save Camera
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {

        // Primary & Secondary are mutually exclusive
        $('.camera-role').on('change', function() {
            if ($(this).is(':checked')) {
                $('.camera-role').not(this).prop('checked', false);
            }
        });

        function generateSnapshotUrl() {
            const ip = $('#ip_address').val()?.trim();
            const user = $('#username').val()?.trim();
            const pass = $('#password').val() || '';
            const slug = $('#snapshot_slug').val()?.trim();

            if (!ip || !slug) {
                $('#snapshotPreview').text('Snapshot URL will be generated automatically');
                $('#snapshot_url').val('');
                return;
            }

            // Real auth (submitted)
            let realAuth = '';
            if (user || pass) {
                realAuth = `${user}:${pass}@`;
            }

            // Masked auth (preview only)
            let maskedAuth = '';
            if (user || pass) {
                const maskedPass = pass.length ? '****' : '';
                maskedAuth = `${user}:${maskedPass}@`;
            }

            const realUrl = `http://${realAuth}${ip}/${slug}`;
            const maskedUrl = `http://${maskedAuth}${ip}/${slug}`;

            $('#snapshot_url').val(realUrl);
            $('#snapshotPreview').text(maskedUrl);
        }

        $('#ip_address, #username, #password, #snapshot_slug')
            .on('keyup change', generateSnapshotUrl);

    });
</script>
