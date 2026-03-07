@extends('layout.index')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">{{ $pageTitle ?? 'Email Settings' }}</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">{{ $pageTitle ?? 'Email Settings' }}</li>
        </ul>
    </div>

    {{-- Email Settings Card --}}
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Global Email Configuration</h6>
        </div>

        <div class="card-body">
            <form id="emailSettingsForm">

                @csrf

                <div class="row g-3">

                    {{-- Mailer --}}
                    <div class="col-md-4">
                        <label class="form-label">Mailer</label>
                        <select name="mailer" class="form-select" required>
                            @foreach (['smtp', 'ses', 'sendmail', 'log', 'array'] as $mailer)
                                <option value="{{ $mailer }}"
                                    {{ ($email->mailer ?? 'smtp') === $mailer ? 'selected' : '' }}>
                                    {{ strtoupper($mailer) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Host --}}
                    <div class="col-md-4">
                        <label class="form-label">SMTP Host</label>
                        <input type="text" name="host" class="form-control" value="{{ $email->host ?? '' }}">
                    </div>

                    {{-- Port --}}
                    <div class="col-md-4">
                        <label class="form-label">SMTP Port</label>
                        <input type="number" name="port" class="form-control" value="{{ $email->port ?? '' }}">
                    </div>

                    {{-- Username --}}
                    <div class="col-md-4">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ $email->username ?? '' }}">
                    </div>

                    {{-- Password --}}
                    <div class="col-md-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Leave blank to keep existing">
                    </div>

                    {{-- Encryption --}}
                    <div class="col-md-4">
                        <label class="form-label">Encryption</label>
                        <select name="encryption" class="form-select">
                            <option value="">None</option>
                            <option value="tls" {{ ($email->encryption ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($email->encryption ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>

                    {{-- From Address --}}
                    <div class="col-md-6">
                        <label class="form-label">From Email</label>
                        <input type="email" name="from_address" class="form-control"
                            value="{{ $email->from_address ?? '' }}">
                    </div>

                    {{-- From Name --}}
                    <div class="col-md-6">
                        <label class="form-label">From Name</label>
                        <input type="text" name="from_name" class="form-control" value="{{ $email->from_name ?? '' }}">
                    </div>

                    {{-- Active --}}
                    <div class="col-md-12">
                        <div class="form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                {{ $email->is_active ?? true ? 'checked' : '' }}>
                            <label class="form-check-label">
                                Enable Email Sending
                            </label>
                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary-600">
                        <i class="ri-save-line"></i> Save Settings
                    </button>
                </div>

            </form>


            <hr class="my-4">

            <div class="row align-items-end g-3">

                <div class="col-md-6">
                    <label class="form-label">Test Email Address</label>
                    <input type="email" id="testEmailAddress" class="form-control" placeholder="example@domain.com">
                </div>

                <div class="col-md-6">
                    <button type="button" class="btn btn-outline-primary mt-4" id="sendTestEmail">
                        <i class="ri-mail-send-line"></i> Send Test Email
                    </button>
                </div>

            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#emailSettingsForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('settings.email.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.status) {
                        toastr.success(res.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Failed to save email settings');
                }
            });
        });
    </script>


    <script>
        $('#sendTestEmail').on('click', function() {

            const email = $('#testEmailAddress').val();

            if (!email) {
                toastr.error('Please enter a test email address');
                return;
            }

            $.ajax({
                url: '{{ route('settings.email.test') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: email
                },
                beforeSend: function() {
                    $('#sendTestEmail').prop('disabled', true);
                },
                success: function(res) {
                    if (res.status) {
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Failed to send test email');
                },
                complete: function() {
                    $('#sendTestEmail').prop('disabled', false);
                }
            });
        });
    </script>
@endpush
