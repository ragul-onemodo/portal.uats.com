@php
    use Spatie\Permission\Models\Permission;
@endphp

<form id="crudForm" action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">

        {{-- Role name --}}
        <div class="col-md-4">
            <label class="form-label">
                Role Name <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}"
                class="form-control @error('name') is-invalid @enderror" required>
        </div>

        {{-- Permissions --}}
        <div class="col-12">
            <label class="form-label fw-semibold mb-3">
                Permissions
            </label>

            <div class="row g-3">

                @foreach ($permissions as $module => $actions)
                    @php
                        // Build permission names
                        $modulePermissions = collect($actions)
                            ->map(fn($a) => $module . '.' . $a)
                            ->filter(fn($p) => Permission::where('name', $p)->where('guard_name', 'web')->exists());

                        $hasAllModulePermissions =
                            $modulePermissions->isNotEmpty() &&
                            $modulePermissions->every(fn($p) => $role->hasPermissionTo($p));
                    @endphp

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 permission-module" data-module="{{ $module }}">

                            {{-- Header --}}
                            <div class="card-header bg-light border-0">
                                <div class="d-flex align-items-center justify-content-between">

                                    <span class="fw-semibold text-capitalize">
                                        {{ str_replace('_', ' ', $module) }}
                                    </span>

                                    {{-- Master toggle --}}
                                    <div class="form-switch switch-warning">
                                        <input class="form-check-input module-toggle" type="checkbox" role="switch"
                                            id="{{ $module }}_all"
                                            {{ $hasAllModulePermissions ? 'checked' : '' }}>
                                    </div>
                                </div>

                                <small class="text-muted">
                                    Enable all {{ str_replace('_', ' ', $module) }} permissions
                                </small>
                            </div>

                            {{-- Body --}}
                            <div class="card-body">
                                <div class="d-flex flex-column gap-3">

                                    @foreach ($actions as $action)
                                        @php
                                            $permission = $module . '.' . $action;

                                            $permissionExists = Permission::where('name', $permission)
                                                ->where('guard_name', 'web')
                                                ->exists();
                                        @endphp

                                        <div
                                            class="form-switch switch-warning d-flex align-items-center justify-content-between">
                                            <label class="form-check-label fw-medium text-secondary-light"
                                                for="{{ $permission }}">
                                                {{ str_replace('_', ' ', ucfirst($action)) }}
                                            </label>

                                            <input class="form-check-input permission-toggle" type="checkbox"
                                                role="switch" name="permissions[]" value="{{ $permission }}"
                                                id="{{ $permission }}"
                                                {{ $permissionExists && $role->hasPermissionTo($permission) ? 'checked' : '' }}>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <div class="modal-footer mt-4">
        <button type="submit" class="btn btn-primary-600">
            Update Role
        </button>
    </div>
</form>



<script>
    $(document).ready(function() {

        // Toggle all permissions inside a module
        $('.module-toggle').on('change', function() {
            const moduleCard = $(this).closest('.permission-module');
            const isChecked = $(this).is(':checked');

            moduleCard.find('.permission-toggle')
                .prop('checked', isChecked);
        });

        // Auto-update module toggle if all permissions are checked manually
        $('.permission-toggle').on('change', function() {
            const moduleCard = $(this).closest('.permission-module');
            const total = moduleCard.find('.permission-toggle').length;
            const checked = moduleCard.find('.permission-toggle:checked').length;

            moduleCard.find('.module-toggle')
                .prop('checked', total === checked);
        });

    });
</script>
