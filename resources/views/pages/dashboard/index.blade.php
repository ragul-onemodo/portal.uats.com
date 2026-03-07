@extends('layout.index')

@section('content')
<section class="wrapper">

    <div class="d-flex justify-content-between mb-4">
        <h5 class="fw-bold">Dashboard</h5>
    </div>

    <!-- ===================== -->
    <!-- TOP CARDS -->
    <!-- ===================== -->
    <div class="row g-4">
        @php
            $cards = [
                ['id'=>'total-entities','title'=>'Total Entities','icon'=>'fa-building','class'=>'card-entities'],
                ['id'=>'total-users','title'=>'Total Users','icon'=>'fa-users','class'=>'card-users'],
                ['id'=>'total-devices','title'=>'Total Devices','icon'=>'fa-microchip','class'=>'card-devices'],
                ['id'=>'total-trips','title'=>'Total Trips','icon'=>'fa-car','class'=>'card-trips'],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card {{ $card['class'] }}">
                <div class="card-content">
                    <div>
                        <p>{{ $card['title'] }}</p>
                        <h2 id="{{ $card['id'] }}">0</h2>
                        <span class="sub-text">Live Count</span>
                    </div>
                    <div class="icon-box">
                        <i class="fas {{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ===================== -->
    <!-- CHARTS -->
    <!-- ===================== -->
    <div class="row g-4 mt-3">
        <div class="col-xl-8">
            <div class="card p-4 h-100">
                <h6 class="fw-semibold mb-3">Trips - Last 7 Days</h6>
                <div class="chart-wrapper">
                    <canvas id="tripChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card p-4 h-100">
                <h6 class="fw-semibold mb-3">Device Status</h6>
                <canvas id="deviceChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- ===================== -->
    <!-- SUMMARY + RECENT -->
    <!-- ===================== -->
    <div class="row g-4 mt-3">

        <div class="col-xl-4">
            <div class="card p-4 h-100">
                <h6 class="fw-semibold mb-3">Today's Summary</h6>
                <ul class="summary-list">
                    <li>
                        <span>Today Trips</span>
                        <span class="count bg-primary-soft" id="todayTrips">0</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card p-4 h-100">
                <h6 class="fw-semibold mb-3">Recent Trips</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>Direction</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="recentTrips"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</section>
@endsection

@stack('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let tripChart = null;
    let deviceChart = null;

    function loadDashboard() {

        $.get('{{ route('dashboard.stats') }}', function(data) {

            // ======================
            // Top Counts
            // ======================
            $('#total-entities').text(data.entities);
            $('#total-users').text(data.users);
            $('#total-devices').text(data.devices);
            $('#total-trips').text(data.trips);

            // ======================
            // Trips Line Chart
            // ======================
            if (tripChart) {
                tripChart.destroy();
            }

            const tripCtx = document.getElementById('tripChart').getContext('2d');

            tripChart = new Chart(tripCtx, {
                type: 'line',
                data: {
                    labels: data.weeklyLabels,
                    datasets: [{
                        label: 'Trips',
                        data: data.weeklyTrips,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // ======================
            // Device Doughnut Chart
            // ======================
            if (deviceChart) {
                deviceChart.destroy();
            }

            const deviceCtx = document.getElementById('deviceChart').getContext('2d');

            deviceChart = new Chart(deviceCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Inactive'],
                    datasets: [{
                        data: [
                            data.deviceStatus?.active ?? 0,
                            data.deviceStatus?.inactive ?? 0
                        ],
                        backgroundColor: ['#22c55e', '#ef4444'],
                        borderWidth: 2
                    }]
                }
            });

            // ======================
            // Today Summary
            // ======================
            $('#todayTrips').text(data.todayTrips);
            $('#ongoingTrips').text('-');
            $('#completedTrips').text('-');
            $('#pendingTrips').text('-');

            // ======================
            // Recent Trips Table
            // ======================
            let rows = '';

            data.recentTrips.forEach(function(trip) {

                let badgeClass = 'bg-primary-soft';

                rows += `
                <tr>
                    <td>${trip.vehicle ?? '-'}</td>
                    <td>
                        <span class="status-badge ${badgeClass}">
                            ${trip.direction ?? '-'}
                        </span>
                    </td>
                    <td>${trip.date ?? '-'}</td>
                </tr>
            `;
            });

            $('#recentTrips').html(rows);
        });
    }

    loadDashboard();
    setInterval(loadDashboard, 30000);
</script>

<style>
    .dashboard-card {
        border-radius: 12px;
        border: none;
    }

    .chart-wrapper {
        position: relative;
        height: 350px;
        width: 100%;
    }

    .chart-wrapper canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .wrapper {
        padding: 20px;
    }

    .dashboard-card {
        height: 200px;
        border-radius: 20px;
        padding: 25px;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-8px);
    }

    .card-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 100%;
    }

    .dashboard-card p {
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
    }

    .dashboard-card h2 {
        font-size: 48px;
        font-weight: bold;
        margin: 5px 0;
    }

    .sub-text {
        font-size: 13px;
        opacity: 0.8;
    }

    .icon-box {
        width: 65px;
        height: 65px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 28px;
    }


    .card-entities {
        background: linear-gradient(135deg, #667eea, #5a67d8);
    }

    .card-users {
        background: linear-gradient(135deg, #38b2ac, #319795);
    }

    .card-devices {
        background: linear-gradient(135deg, #f6ad55, #ed8936);
    }

    .card-trips {
        background: linear-gradient(135deg, #f56565, #e53e3e);
    }

    .row>div {
        margin-bottom: 25px;
    }


    .col-md-3:nth-child(1) .dashboard-card {
        border-left: 5px solid #6366f1;
    }

    .col-md-3:nth-child(2) .dashboard-card {
        border-left: 5px solid #10b981;
    }

    .col-md-3:nth-child(3) .dashboard-card {
        border-left: 5px solid #f59e0b;
    }

    .col-md-3:nth-child(4) .dashboard-card {
        border-left: 5px solid #ef4444;
    }


    @media (max-width: 768px) {
        .dashboard-card {
            margin-bottom: 20px;
        }
    }

    .card {
        border: none;
        border-radius: 16px;
        background: #ffffff;
        transition: 0.3s ease;
    }

    .card:hover {
        transform: translateY(-3px);
    }

    .card h6 {
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
        letter-spacing: 0.5px;
    }



    .status-badge {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .bg-success {
        background: rgba(34, 197, 94, 0.1) !important;
        color: #16a34a !important;
    }

    .bg-warning {
        background: rgba(251, 191, 36, 0.15) !important;
        color: #d97706 !important;
    }

    .bg-danger {
        background: rgba(239, 68, 68, 0.1) !important;
        color: #dc2626 !important;
    }


    .table-responsive {
        scrollbar-width: thin;
    }




    .list-group-item {
        border: none;
        padding: 14px 0;
        font-size: 15px;
        color: #374151;
    }

    .list-group-item:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }

    .list-group-item span {
        font-weight: 600;
        font-size: 16px;
    }



    .table {
        margin-bottom: 0;
    }

    .table thead {
        background: #f9fafb;
        border-radius: 12px;
    }

    .table th {
        font-weight: 600;
        font-size: 14px;
        color: #6b7280;
        border-bottom: 1px solid #e5e7eb;
    }

    .table td {
        vertical-align: middle;
        font-size: 15px;
        color: #374151;
        border-top: 1px solid #f1f5f9;
    }

    .table tbody tr {
        transition: 0.2s ease;
    }

    .table tbody tr:hover {
        background: #f8fafc;
    }



    .badge {
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }

    .badge-pending {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-completed {
        background: #d1fae5;
        color: #047857;
    }

    .badge-ongoing {
        background: #dbeafe;
        color: #1d4ed8;
    }


    .badge-failed {
        background: #fee2e2;
        color: #b91c1c;
    }


    @media (max-width: 768px) {
        .card {
            padding: 20px !important;
        }
    }

    .summary-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .summary-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .summary-list li:last-child {
        border-bottom: none;
    }

    .summary-list .label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        color: #333;
    }

    .summary-list .label i {
        font-size: 16px;
    }

    .summary-list .count {
        min-width: 38px;
        height: 32px;
        padding: 0 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }



    .bg-primary-soft {
        background: rgba(59, 130, 246, 0.12);
        color: #2563eb;
    }

    .bg-warning-soft {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
    }

    .bg-success-soft {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
    }

    .bg-danger-soft {
        background: rgba(239, 68, 68, 0.15);
        color: #dc2626;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
