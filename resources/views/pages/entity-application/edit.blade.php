<form id="crudForm" action="{{ route('entity-applications.update', $entityApplication->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">
                Entity <span class="text-danger">*</span>
            </label>
            <select name="entity_id" class="form-select @error('entity_id') is-invalid @enderror" required>
                @foreach ($entities as $entity)
                    <option value="{{ $entity->id }}"
                        {{ old('entity_id', $entityApplication->entity_id) == $entity->id ? 'selected' : '' }}>
                        {{ $entity->name }}
                    </option>
                @endforeach
            </select>
            @error('entity_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">
                Application <span class="text-danger">*</span>
            </label>
            <select name="application_id" class="form-select @error('application_id') is-invalid @enderror" required>
                @foreach ($applications as $app)
                    <option value="{{ $app->id }}"
                        {{ old('application_id', $entityApplication->application_id) == $app->id ? 'selected' : '' }}>
                        {{ $app->name }}
                    </option>
                @endforeach
            </select>
            @error('application_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label class="form-label">
                Company Reference
            </label>
            <input type="text" name="company_reference"
                class="form-control @error('company_reference') is-invalid @enderror"
                value="{{ old('company_reference', $entityApplication->company_reference) }}" required>
            @error('company_reference')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <div class="switch-primary form-switch mt-4">

                <!-- Hidden fallback -->
                <input type="hidden" name="is_active" value="0">

                <!-- Checkbox -->
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $entityApplication->is_active) ? 'checked' : '' }}>

                <label class="form-check-label">
                    Active
                </label>
            </div>
        </div>

        <!-- Optional info -->
        <div class="col-12">
            <small class="text-muted">
                Last updated:
                {{ $entityApplication->updated_at->format('d M Y H:i') }}
            </small>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary-600">
            Update
        </button>
    </div>
</form>
