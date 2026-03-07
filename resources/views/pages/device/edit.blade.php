<form id="crudForm" action="{{ route('devices.update', $device->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-3">

        {{-- Entity --}}
        <div class="col-md-6">
            <label class="form-label">
                Entity <span class="text-danger">*</span>
            </label>
            <select name="entity_id" class="form-select @error('entity_id') is-invalid @enderror" required>
                @foreach ($entities as $entity)
                    <option value="{{ $entity->id }}"
                        {{ old('entity_id', $device->entity_id) == $entity->id ? 'selected' : '' }}>
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
                <option value="edge_hub"
                    {{ old('device_type', $device->device_type) === 'edge_hub' ? 'selected' : '' }}>
                    Edge Hub
                </option>
                <option value="camera" {{ old('device_type', $device->device_type) === 'camera' ? 'selected' : '' }}>
                    Camera
                </option>
                <option value="sensor" {{ old('device_type', $device->device_type) === 'sensor' ? 'selected' : '' }}>
                    Sensor
                </option>
                <option value="weighbridge"
                    {{ old('device_type', $device->device_type) === 'weighbridge' ? 'selected' : '' }}>
                    Weighbridge
                </option>
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
                value="{{ old('device_name', $device->device_name) }}" required>
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
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $device->is_active) ? 'checked' : '' }}>

                <label class="form-check-label">
                    Active
                </label>
            </div>
        </div>

        {{-- Optional meta --}}
        <div class="col-12">
            <small class="text-muted">
                Last updated: {{ $device->updated_at->format('d M Y H:i') }}
            </small>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary-600">
            Update
        </button>
    </div>
</form>
