<form id="crudForm" action="{{ route('applications.store') }}" method="POST">
    @csrf

    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Code <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control" required>
        </div>

        <div class="col-12">
            <label class="form-label">Webhook URL</label>
            <input type="url" name="webhook_url" class="form-control">
        </div>

        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
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
