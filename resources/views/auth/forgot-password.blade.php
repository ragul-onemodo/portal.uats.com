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

                    <h4 class="mb-12">Forgot your password?</h4>
                    <p class="mb-32 text-secondary-light text-lg">
                        No problem. Just enter your email address and we’ll send you a password reset link.
                    </p>
                </div>

                {{-- Session status (success message) --}}
                @if (session('status'))
                    <div class="alert alert-success mb-24">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-24">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="icon-field mb-24">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Email" required autofocus>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="btn btn-primary text-sm btn-sm px-12 py-16
                                   w-100 radius-12">
                        Email Password Reset Link
                    </button>

                    {{-- Back to login --}}
                    <div class="mt-24 text-center">
                        <a href="{{ route('login') }}" class="text-primary-600 fw-medium">
                            Back to Sign In
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </section>

    @include('inc.scripts')

</body>

</html>
