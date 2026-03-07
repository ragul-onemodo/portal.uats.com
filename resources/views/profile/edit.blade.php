@extends('layout.index')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Profile</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Profile</li>
        </ul>
    </div>

    <div class="row g-24">

        {{-- Update Profile Information --}}
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-semibold mb-0">Profile Information</h6>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        {{-- Update Password --}}
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-semibold mb-0">Update Password</h6>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        {{-- Delete Account --}}
        <div class="col-12 my-5">
            <div class="card border-danger">
                <div class="card-header bg-danger-subtle">
                    <h6 class="fw-semibold mb-0 text-danger">Delete Account</h6>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

    </div>
@endsection
