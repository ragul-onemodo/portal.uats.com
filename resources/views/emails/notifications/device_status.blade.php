@extends('emails.base')

@section('content')
    @php
        $status = $payload['status'] ?? 'unknown'; // online | offline | overheated
        $device = $payload['device_name'] ?? 'Unknown Device';

        $statusConfig = [
            'online' => [
                'class' => 'success',
                'icon' => 'fa-circle-check',
                'label' => 'Device Online',
            ],
            'offline' => [
                'class' => 'danger',
                'icon' => 'fa-circle-xmark',
                'label' => 'Device Offline',
            ],
            'overheated' => [
                'class' => 'warning',
                'icon' => 'fa-temperature-high',
                'label' => 'Device Overheated',
            ],
            'unknown' => [
                'class' => 'secondary',
                'icon' => 'fa-circle-info',
                'label' => 'Device Status Update',
            ],
        ];

        $ui = $statusConfig[$status];
    @endphp

    {{-- Status Header --}}
    <div class="alert alert-{{ $ui['class'] }} d-flex align-items-center gap-2">
        <i class="fa-solid {{ $ui['icon'] }} fa-lg"></i>
        <strong>{{ $ui['label'] }}</strong>
    </div>

    {{-- Device Info --}}
    <p class="mb-3">
        The following device has reported a status update:
    </p>

    <table class="table table-bordered">
        <tr>
            <th width="180">Device Name</th>
            <td>{{ $device }}</td>
        </tr>

        @if (isset($payload['entity_name']))
            <tr>
                <th>Entity</th>
                <td>{{ $payload['entity_name'] }}</td>
            </tr>
        @endif

        <tr>
            <th>Status</th>
            <td>
                <span class="badge bg-{{ $ui['class'] }}">
                    {{ ucfirst($status) }}
                </span>
            </td>
        </tr>

        <tr>
            <th>Reported At</th>
            <td>{{ $notification->occurred_at }}</td>
        </tr>

        @if (isset($payload['message']))
            <tr>
                <th>Message</th>
                <td>{{ $payload['message'] }}</td>
            </tr>
        @endif
    </table>
@endsection
