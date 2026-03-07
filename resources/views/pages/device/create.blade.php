<form id="crudForm" action="{{ route('devices.store') }}" method="POST">
    @csrf

    <div class="row g-3">

        {{-- Entity --}}
        <div class="col-md-6">
            <label class="form-label">
                Entity <span class="text-danger">*</span>
            </label>
            <select name="entity_id" class="form-select @error('entity_id') is-invalid @enderror" required>
                @foreach ($entities as $entity)
                    <option value="{{ $entity->id }}">
                        {{ $entity->name }}
                    </option>
                @endforeach
            </select>
            @error('entity_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Device Type --}}
        <div class="col-md-6">
            <label class="form-label">
                Device Type <span class="text-danger">*</span>
            </label>
            <select name="device_type" class="form-select @error('device_type') is-invalid @enderror" required>
                <option value="edge_hub">Edge Hub</option>
                <option value="camera">Camera</option>
                <option value="sensor">Sensor</option>
                <option value="weighbridge">Weighbridge</option>
            </select>
            @error('device_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Device Name --}}
        <div class="col-md-12">
            <label class="form-label">
                Device Name <span class="text-danger">*</span>
            </label>
            <input type="text" name="device_name" class="form-control @error('device_name') is-invalid @enderror"
                placeholder="e.g. Edge Hub - Gate 1" required>
            @error('device_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Status --}}
        <div class="col-12 col-md-6">
            <div class="switch-primary form-switch mt-4">

                <!-- Hidden fallback -->
                <input type="hidden" name="is_active" value="0">

                <!-- Checkbox -->
                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>

                <label class="form-check-label">
                    Active
                </label>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary-600">
            Save
        </button>
    </div>
</form>
