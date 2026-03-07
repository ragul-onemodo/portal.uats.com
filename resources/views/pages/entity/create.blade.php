<form id="crudForm" action="{{ route('entities.store') }}" method="POST">
    @csrf

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                value="{{ old('location') }}">
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <div class="switch-primary form-switch mt-4">
                <input class="form-check-input" type="checkbox" name="integration_enabled" id="integration_create"
                    value="1" {{ old('integration_enabled', false) ? 'checked' : '' }}>
                <label class="form-check-label" for="integration_create">
                    Integration Enabled
                </label>
            </div>
        </div>
    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary-600">Save</button>
    </div>
</form>
