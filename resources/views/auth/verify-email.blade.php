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

                    <h4 class="mb-12">Verify Your Email</h4>
                    <p class="mb-24 text-secondary-light text-lg">
                        Thanks for signing up! Before getting started, please verify your email address by clicking
                        the link we just sent to your inbox.
                    </p>

                    <p class="mb-32 text-secondary-light text-sm">
                        If you didn’t receive the email, we’ll gladly send you another.
                    </p>
                </div>

                {{-- Success message --}}
                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mb-24">
                        A new verification link has been sent to your email address.
                    </div>
                @endif

                {{-- Actions --}}
                <div class="d-flex flex-column gap-16">

                    {{-- Resend verification --}}
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="btn btn-primary text-sm btn-sm px-12 py-16
                                       w-100 radius-12">
                            Resend Verification Email
                        </button>
                    </form>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="btn btn-outline-secondary text-sm btn-sm px-12 py-16
                                       w-100 radius-12">
                            Log Out
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </section>

    @include('inc.scripts')

</body>

</html>
