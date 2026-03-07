<form id="crudForm" action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">
                Name <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">
                Email <span class="text-danger">*</span>
            </label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">
                Password <span class="text-danger">*</span>
            </label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">
                Entity <span class="text-danger">*</span>
            </label>
            <select name="entity_id" class="form-select @error('entity_id') is-invalid @enderror" required>
                <option value="">Select Entity</option>
                @foreach ($entities as $entity)
                    <option value="{{ $entity->id }}" {{ old('entity_id') == $entity->id ? 'selected' : '' }}>
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
                Role <span class="text-danger">*</span>
            </label>

            <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                <option value="">Select Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            @error('role_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <div class="col-12 col-md-6">
            <div class="switch-primary form-switch mt-4">
                <input class="form-check-input" type="checkbox" name="status" id="status_create" value="1"
                    {{ old('status', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="status_create">
                    Active
                </label>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
        </button>
        <button type="submit" class="btn btn-primary-600">
            Save
        </button>
    </div>
</form>
