<section>

    <div class="mb-24">
        <h6 class="fw-semibold mb-8 text-danger">
            Delete Account
        </h6>
        <p class="text-secondary-light text-sm">
            Once your account is deleted, all of its resources and data will be permanently deleted.
            Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </div>

    {{-- Trigger button --}}
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        Delete Account
    </button>

    {{-- Confirmation Modal --}}
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionLabel">
                            Confirm Account Deletion
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-secondary-light mb-16">
                            Are you sure you want to delete your account?
                            This action is permanent and cannot be undone.
                            Please enter your password to confirm.
                        </p>

                        {{-- Password --}}
                        <div class="mb-12">
                            <label for="delete_account_password" class="form-label">
                                Password
                            </label>
                            <input id="delete_account_password" name="password" type="password"
                                class="form-control
                                    @if ($errors->userDeletion->has('password')) is-invalid @endif"
                                placeholder="Password">

                            @if ($errors->userDeletion->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->userDeletion->first('password') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-danger">
                            Delete Account
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- Auto-open modal on validation error --}}
    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(
                    document.getElementById('confirmUserDeletionModal')
                );
                modal.show();
            });
        </script>
    @endif

</section>
