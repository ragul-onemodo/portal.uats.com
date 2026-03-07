<html>

@include('inc.header')

<body>

    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="{{ asset('assets/images/auth/auth-img.png') }}" alt="">
            </div>
        </div>

        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">

                <div class="mb-40">
                    <a href="{{ url('/') }}" class="max-w-290-px d-none mb-24">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="">
                    </a>

                    <h4 class="mb-12">Reset Password</h4>
                    <p class="mb-32 text-secondary-light text-lg">
                        Please enter your email and choose a new password.
                    </p>
                </div>

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-24">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    {{-- Reset token --}}
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Email --}}
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                        <input type="email" name="email" value="{{ old('email', $request->email) }}"
                            class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Email" required autofocus
                            autocomplete="username">
                    </div>

                    {{-- New Password --}}
                    <div class="position-relative mb-20">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input type="password" name="password" class="form-control h-56-px bg-neutral-50 radius-12"
                                id="new-password" placeholder="New Password" required autocomplete="new-password">
                        </div>

                        <span
                            class="toggle-password ri-eye-line cursor-pointer
                              position-absolute end-0 top-50 translate-middle-y
                              me-16 text-secondary-light"
                            data-toggle="#new-password"></span>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="position-relative mb-32">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input type="password" name="password_confirmation"
                                class="form-control h-56-px bg-neutral-50 radius-12" id="confirm-password"
                                placeholder="Confirm Password" required autocomplete="new-password">
                        </div>

                        <span
                            class="toggle-password ri-eye-line cursor-pointer
                              position-absolute end-0 top-50 translate-middle-y
                              me-16 text-secondary-light"
                            data-toggle="#confirm-password"></span>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="btn btn-primary text-sm btn-sm px-12 py-16
                                   w-100 radius-12">
                        Reset Password
                    </button>
                </form>

            </div>
        </div>
    </section>

    @include('inc.scripts')

    {{-- INLINE SCRIPT (password toggle) --}}
    <script>
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on('click', function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                input.attr("type", input.attr("type") === "password" ? "text" : "password");
            });
        }
        initializePasswordToggle('.toggle-password');
    </script>

</body>

</html>
