<form id="crudForm" action="{{ route('entity-applications.store') }}" method="POST">
    @csrf

    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Entity <span class="text-danger">*</span></label>
            <select name="entity_id" class="form-select" required>
                @foreach ($entities as $entity)
                    <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Application <span class="text-danger">*</span></label>
            <select name="application_id" class="form-select" required>
                @foreach ($applications as $app)
                    <option value="{{ $app->id }}">{{ $app->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12">
            <label class="form-label">Company Reference</label>
            <input type="text" name="company_reference" class="form-control" required>
        </div>

        <div class="col-12 col-md-6">
            <div class="switch-primary form-switch mt-4">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                <label class="form-check-label">Active</label>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary-600">Save</button>
    </div>
</form>
