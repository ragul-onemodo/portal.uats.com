<section>

    <div class="mb-24">
        <h6 class="fw-semibold mb-8">
            Profile Information
        </h6>
        <p class="text-secondary-light text-sm">
            Update your account's profile information and email address.
        </p>
    </div>

    {{-- Resend verification --}}
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        {{-- Name --}}
        <div class="mb-20">
            <label for="name" class="form-label">
                Name
            </label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-20">
            <label for="email" class="form-label">
                Email
            </label>
            <input id="email" name="email" type="email"
                class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}"
                required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            {{-- Email verification notice --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-12">
                    <p class="text-sm text-warning mb-8">
                        Your email address is unverified.
                    </p>

                    <button type="submit" form="send-verification"
                        class="btn btn-link p-0 text-primary-600 fw-medium text-sm">
                        Click here to re-send the verification email.
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-12 mb-0">
                            A new verification link has been sent to your email address.
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="d-flex align-items-center gap-16">
            <button type="submit" class="btn btn-primary-600">
                Save
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success text-sm">
                    Saved.
                </span>
            @endif
        </div>

    </form>

</section>
