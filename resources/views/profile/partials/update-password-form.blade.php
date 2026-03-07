<section>

    <div class="mb-24">
        <h6 class="fw-semibold mb-8">
            Update Password
        </h6>
        <p class="text-secondary-light text-sm">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        {{-- Current Password --}}
        <div class="mb-20">
            <label for="update_password_current_password" class="form-label">
                Current Password
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="form-control
                    @if ($errors->updatePassword->has('current_password')) is-invalid @endif"
                autocomplete="current-password">
            @if ($errors->updatePassword->has('current_password'))
                <div class="invalid-feedback">
                    {{ $errors->updatePassword->first('current_password') }}
                </div>
            @endif
        </div>

        {{-- New Password --}}
        <div class="mb-20">
            <label for="update_password_password" class="form-label">
                New Password
            </label>
            <input id="update_password_password" name="password" type="password"
                class="form-control
                    @if ($errors->updatePassword->has('password')) is-invalid @endif"
                autocomplete="new-password">
            @if ($errors->updatePassword->has('password'))
                <div class="invalid-feedback">
                    {{ $errors->updatePassword->first('password') }}
                </div>
            @endif
        </div>

        {{-- Confirm Password --}}
        <div class="mb-24">
            <label for="update_password_password_confirmation" class="form-label">
                Confirm Password
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="form-control
                    @if ($errors->updatePassword->has('password_confirmation')) is-invalid @endif"
                autocomplete="new-password">
            @if ($errors->updatePassword->has('password_confirmation'))
                <div class="invalid-feedback">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="d-flex align-items-center gap-16">
            <button type="submit" class="btn btn-primary-600">
                Save
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-success text-sm">
                    Saved.
                </span>
            @endif
        </div>

    </form>

</section>
