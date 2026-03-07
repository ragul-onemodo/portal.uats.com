<form id="crudForm" action="{{ route('entities.update', $entity->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $entity->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                value="{{ old('location', $entity->location) }}">
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <div class="switch-primary form-switch mt-4">

                <!-- Hidden fallback -->
                <input type="hidden" name="is_active" value="0">

                <!-- Checkbox -->
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active_edit" value="1"
                    {{ old('is_active', $entity->is_active) ? 'checked' : '' }}>

                <label class="form-check-label" for="is_active_edit">Active</label>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="switch-primary form-switch mt-4">

                <!-- Hidden fallback -->
                <input type="hidden" name="integration_enabled" value="0">

                <!-- Checkbox -->
                <input class="form-check-input" type="checkbox" name="integration_enabled" id="integration_edit"
                    value="1" {{ old('integration_enabled', $entity->integration_enabled) ? 'checked' : '' }}>

                <label class="form-check-label" for="integration_edit">Integration Enabled</label>
            </div>
        </div>


        <!-- Optional: show some read-only info -->
        <div class="col-12">
            <small class="text-muted">
                Last updated: {{ $entity->updated_at->format('d M Y H:i') }}
            </small>
        </div>
    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary-600">Update</button>
    </div>
</form>
