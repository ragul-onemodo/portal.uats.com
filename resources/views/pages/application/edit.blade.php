<form id="crudForm" action="{{ route('applications.update', $application->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">
                Name <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $application->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">
                Code <span class="text-danger">*</span>
            </label>
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                value="{{ old('code', $application->code) }}" required>
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Webhook URL</label>
            <input type="url" name="webhook_url" class="form-control @error('webhook_url') is-invalid @enderror"
                value="{{ old('webhook_url', $application->webhook_url) }}">
            @error('webhook_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $application->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <div class="switch-primary form-switch mt-4">

                <!-- Hidden fallback -->
                <input type="hidden" name="is_active" value="0">

                <!-- Checkbox -->
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $application->is_active) ? 'checked' : '' }}>

                <label class="form-check-label">Active</label>
            </div>
        </div>

        <!-- Optional: read-only info -->
        <div class="col-12">
            <small class="text-muted">
                Last updated: {{ $application->updated_at->format('d M Y H:i') }}
            </small>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary-600">
            Update
        </button>
    </div>
</form>
