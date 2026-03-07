@extends('layout.index')

@section('custom-styles')
    <style>
        /* --- Replicating the Premium Template Styles --- */
        :root {
            --primary-light: #64748b;
            --title-color: #1e293b;
            --border-color: #e2e8f0;
        }

        /* Card & Layout Base */
        .wrapper {
            background-color: #f8fafc;
            min-height: 100vh;
            padding: 24px;
        }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            background: #fff;
            height: 100%;
            transition: transform 0.2s;
        }

        .card-body {
            padding: 24px;
        }

        .radius-8 {
            border-radius: 8px;
        }

        .radius-12 {
            border-radius: 12px;
        }

        /* Typography */
        .text-lg {
            font-size: 1.125rem;
        }

        .text-md {
            font-size: 1rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .fw-medium {
            font-weight: 500;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .text-primary-light {
            color: var(--primary-light);
        }

        .text-title {
            color: var(--title-color);
        }

        /* Gradients (Matches your sample) */
        .bg-gradient-start-1 {
            background: linear-gradient(180deg, #e0f2fe 0%, #fff 100%);
            border-color: #bae6fd !important;
        }

        .bg-gradient-start-2 {
            background: linear-gradient(180deg, #f3e8ff 0%, #fff 100%);
            border-color: #e9d5ff !important;
        }

        .bg-gradient-start-3 {
            background: linear-gradient(180deg, #fee2e2 0%, #fff 100%);
            border-color: #fecaca !important;
        }

        .bg-gradient-start-4 {
            background: linear-gradient(180deg, #dcfce7 0%, #fff 100%);
            border-color: #bbf7d0 !important;
        }

        /* Icon Circles */
        .w-50-px {
            width: 50px;
        }

        .h-50-px {
            height: 50px;
        }

        .text-2xl {
            font-size: 1.5rem;
        }

        .bg-cyan {
            background-color: #06b6d4;
        }

        .bg-purple {
            background-color: #8b5cf6;
        }

        .bg-red {
            background-color: #ef4444;
        }

        .bg-success-main {
            background-color: #22c55e;
        }

        /* Tables */
        .bordered-table thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 12px 16px;
        }

        .bordered-table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .bordered-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Sensor Badges */
        .bg-base {
            background-color: #f8fafc;
        }

        .icon-shape {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }

        /* Extra spacing for chart section */
        .chart-container {
            min-height: 340px;
        }


        /* Visual warning when usage is high */
        .progress-bar.bg-cyan {
            background-color: #06b6d4;
        }

        .progress-bar.bg-purple {
            background-color: #8b5cf6;
        }

        .progress-bar.bg-red {
            background-color: #ef4444;
        }

        /* Optional: make bar "pulse" or change shade when >90% */
        [data-kpi="cpu"]~.progress .progress-bar[style*="width: 9"]:not([style*="width: 0"]),
        [data-kpi="ram"]~.progress .progress-bar[style*="width: 9"]:not([style*="width: 0"]),
        [data-kpi="storage"]~.progress .progress-bar[style*="width: 9"]:not([style*="width: 0"]) {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }
    </style>
@endsection

@section('content')
    <section class="wrapper" id="device-dashboard">

        {{-- HEADER --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <h6 class="fw-semibold mb-0 text-title fs-4">{{ $device->device_name }}</h6>
                <div
                    class="badge rounded-pill bg-white border text-dark px-3 py-3 fw-normal d-flex align-items-center jus gap-2">
                    <span id="device-status-badge">
                        {!! $device->status_badge !!}
                    </span>

                    <span class="text-secondary opacity-50">|</span>
                    <span class="font-monospace">{{ $device->device_uid }}</span>
                </div>
            </div>

            <ul class="d-flex align-items-center gap-2 m-0 p-0 list-unstyled">
                <li class="fw-medium text-primary-light">
                    <a href="#" class="d-flex align-items-center gap-1 text-decoration-none text-primary-light">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Home
                    </a>
                </li>
                <li class="text-primary-light">-</li>
                <li class="fw-medium text-primary-light">Devices</li>
                <li class="text-primary-light">-</li>
                <li class="fw-medium text-dark">Dashboard</li>
            </ul>
        </div>

        {{-- KPI CARDS ROW --}}
        <div class="row row-cols-xxxl-4 row-cols-lg-4 row-cols-sm-2 row-cols-1 gy-4 my-3 mb-4">

            {{-- CPU CARD --}}
            <div class="col">
                <div class="card shadow-none border bg-gradient-start-1 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">CPU Usage</p>
                                <h6 class="mb-0 fs-4 fw-bold" data-kpi="cpu">{{ $systemStat?->cpu_usage_percent ?? '0' }}%
                                </h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center shadow-sm">
                                <iconify-icon icon="solar:tachometer-fast-bold"
                                    class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 6px; background: rgba(6, 182, 212, 0.15);">
                                <div class="progress-bar bg-cyan rounded-pill" role="progressbar" data-progress="cpu"
                                    style="width: {{ $systemStat?->cpu_usage_percent ?? 0 }}%">
                                </div>

                            </div>
                            <p class="fw-medium text-xs text-primary-light mt-2 mb-0 d-flex align-items-center gap-2">
                                <span class="text-dark">{{ $systemStat?->cpu_cores ?? '—' }} Cores</span> Active
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RAM CARD --}}
            <div class="col">
                <div class="card shadow-none border bg-gradient-start-2 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">RAM Usage</p>
                                <h6 class="mb-0 fs-4 fw-bold" data-kpi="ram">{{ $systemStat?->ram_usage_percent ?? '0' }}%
                                </h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center shadow-sm">
                                <iconify-icon icon="solar:chip-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 6px; background: rgba(139, 92, 246, 0.15);">
                                <div class="progress-bar bg-purple rounded-pill" role="progressbar" data-progress="ram"
                                    style="width: {{ $systemStat?->ram_usage_percent ?? 0 }}%">
                                </div>

                            </div>
                            <p class="fw-medium text-xs text-primary-light mt-2 mb-0">
                                {{ $systemStat?->total_ram_mb ?? '—' }} MB Total Capacity
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STORAGE CARD --}}
            <div class="col">
                <div class="card shadow-none border bg-gradient-start-3 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Storage</p>
                                <h6 class="mb-0 fs-4 fw-bold" data-kpi="storage">
                                    {{ $systemStat?->storage_usage_percent ?? '0' }}%</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-red rounded-circle d-flex justify-content-center align-items-center shadow-sm">
                                <iconify-icon icon="solar:server-square-bold"
                                    class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 6px; background: rgba(239, 68, 68, 0.15);">
                                <div class="progress-bar bg-red rounded-pill" role="progressbar" data-progress="storage"
                                    style="width: {{ $systemStat?->storage_usage_percent ?? 0 }}%">
                                </div>

                            </div>
                            <p class="fw-medium text-xs text-primary-light mt-2 mb-0">
                                {{ $systemStat?->total_storage_mb ?? '—' }} MB Total Capacity
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- UPTIME CARD --}}
            <div class="col">
                <div class="card shadow-none border bg-gradient-start-4 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">System Uptime</p>
                                <h6 id="device-uptime" class="mb-0 fs-4 fw-bold">
                                    {{ $systemStat?->uptime_seconds ? gmdate('H:i:s', $systemStat->uptime_seconds) : '—' }}
                                </h6>

                            </div>
                            <div
                                class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center shadow-sm">
                                <iconify-icon icon="solar:clock-circle-bold"
                                    class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                        <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                            <span class="d-inline-flex align-items-center gap-1 text-success-main">
                                <iconify-icon icon="solar:check-circle-bold" class="text-md"></iconify-icon>
                                Healthy
                            </span>

                            <span id="device-last-seen">
                                Last seen {{ optional($device->last_seen_at)->diffForHumans() ?? 'Never' }}
                            </span>

                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHART SECTION --}}
        <div class="row gy-4 mb-4 mt-3">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-body p-24 chart-container">
                        <h6 class="text-lg fw-bold mb-4">Resource Usage Trend (Last 30 updates)</h6>
                        <div id="systemChart"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT SPLIT --}}
        <div class="row gy-4 my-3">

            {{-- LEFT COL: DEVICE INFO --}}
            <div class="col-xxl-8 col-xl-12">
                <div class="card h-100">
                    <div class="card-body p-24">
                        <div class="d-flex flex-wrap align-items-center gap-1 justify-content-between mb-16">
                            <h6 class="text-lg fw-bold mb-0">System Information</h6>
                            <span class="badge bg-base text-secondary fw-normal border">
                                Kernel: {{ $systemStat?->kernel_version ?? 'Unknown' }}
                            </span>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-base h-100">
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <div class="icon-shape bg-white text-primary rounded-circle shadow-sm">
                                            <iconify-icon icon="solar:laptop-minimalistic-bold"></iconify-icon>
                                        </div>
                                        <span class="text-secondary fw-medium">Firmware</span>
                                    </div>
                                    <h5 class="mb-1">{{ $systemStat?->firmware_version ?? 'N/A' }}</h5>
                                    <span class="text-xs text-primary-light">Build:
                                        {{ $systemStat?->firmware_build ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-base h-100">
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <div class="icon-shape bg-white text-purple rounded-circle shadow-sm">
                                            <iconify-icon icon="solar:layers-minimalistic-bold"></iconify-icon>
                                        </div>
                                        <span class="text-secondary fw-medium">OS Version</span>
                                    </div>
                                    <h5 class="mb-1">{{ $systemStat?->os_name ?? 'Linux' }}</h5>
                                    <span class="text-xs text-primary-light">Ver:
                                        {{ $systemStat?->os_version ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 border rounded-3 bg-base h-100">
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <div class="icon-shape bg-white text-warning rounded-circle shadow-sm">
                                            <iconify-icon icon="solar:cpu-bolt-bold"></iconify-icon>
                                        </div>
                                        <span class="text-secondary fw-medium">CPU Model</span>
                                    </div>
                                    <h6 class="mb-0 text-dark">{{ $systemStat?->cpu_model ?? 'Generic Processor' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COL: SENSORS --}}
            <div class="col-xxl-4 col-xl-12">
                <div class="card h-100">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-4">
                            <h6 class="mb-0 fw-bold text-lg">Connected Sensors</h6>
                            <a href="javascript:void(0)"
                                class="text-primary text-decoration-none fw-medium d-flex align-items-center gap-1 hover-text-primary">
                                {{ count($systemStat?->connected_sensors ?? []) }} Active
                            </a>
                        </div>

                        @if (empty($systemStat?->connected_sensors))
                            <div class="d-flex flex-column align-items-center justify-content-center py-5 opacity-50">
                                <iconify-icon icon="solar:plug-circle-broken" class="text-6xl text-secondary mb-2"
                                    style="font-size: 3rem;"></iconify-icon>
                                <span class="text-secondary">No sensors found</span>
                            </div>
                        @else
                            <div class="d-flex flex-column gap-3">
                                @foreach ($systemStat->connected_sensors as $sensor)
                                    <div
                                        class="d-flex align-items-center justify-content-between p-3 border rounded-3 hover-bg-base transition-all">
                                        <div class="d-flex align-items-center gap-3">
                                            <div
                                                class="w-40-px h-40-px bg-white border rounded-circle d-flex align-items-center justify-content-center">
                                                <iconify-icon icon="solar:usb-bold" class="text-dark fs-5"></iconify-icon>
                                            </div>
                                            <div>
                                                <h6 class="text-md mb-0 fw-medium text-title">
                                                    {{ $sensor['type'] ?? 'Generic Device' }}</h6>
                                                <span class="text-xs text-primary-light">Port:
                                                    {{ $sensor['port'] ?? 'Unknown' }}</span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span
                                                class="badge bg-success-main bg-opacity-10 text-success-main text-white rounded-pill px-3 py-1 text-xs fw-semibold">
                                                Connected
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ────────────────────────────────────────────────
            //  GLOBAL STATE (Persisted in localStorage)
            // ────────────────────────────────────────────────
            const deviceId = {{ $device->id }};
            const STORAGE_KEY = `device:${deviceId}:stats`;

            let statsState = JSON.parse(localStorage.getItem(STORAGE_KEY)) || {
                cpu: [],
                ram: [],
                storage: [],
                labels: []
            };

            // ────────────────────────────────────────────────
            //  HELPERS
            // ────────────────────────────────────────────────
            function pushMetric(arr, value, max = 30) {
                if (value === undefined || value === null) return;
                arr.push(Number(value));
                if (arr.length > max) arr.shift();
            }

            function updateKPI(type, value) {
                const el = document.querySelector(`[data-kpi="${type}"]`);
                if (!el || value === undefined) return;
                el.textContent = Math.round(value) + '%';
            }


            function updateProgress(type, value) {
                const bar = document.querySelector(`[data-progress="${type}"]`);
                if (!bar || value === undefined) return;

                const safeValue = Math.min(100, Math.max(0, value));

                bar.style.width = safeValue + '%';
                bar.setAttribute('aria-valuenow', safeValue);

                // Optional: color shift for high usage
                if (safeValue > 85) {
                    bar.classList.add('opacity-75');
                } else {
                    bar.classList.remove('opacity-75');
                }
            }


            function updateStatus(status) {
                const badge = document.getElementById('device-status-badge');
                if (!badge) return;

                const map = {
                    online: '<span class="badge bg-success">Online</span>',
                    degraded: '<span class="badge bg-warning text-dark">Degraded</span>',
                    offline: '<span class="badge bg-danger">Offline</span>',
                    disabled: '<span class="badge bg-secondary">Disabled</span>',
                };

                badge.innerHTML = map[status] ?? badge.innerHTML;
            }

            function updateLastSeen(isoTime) {
                const el = document.getElementById('device-last-seen');
                if (!el || !isoTime) return;

                el.textContent = 'Last seen just now';
            }

            function updateUptime(seconds) {
                const el = document.getElementById('device-uptime');
                if (!el || seconds === undefined) return;

                const h = String(Math.floor(seconds / 3600)).padStart(2, '0');
                const m = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
                const s = String(seconds % 60).padStart(2, '0');

                el.textContent = `${h}:${m}:${s}`;
            }



            function saveState() {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(statsState));
            }

            // ────────────────────────────────────────────────
            //  CHART SETUP
            // ────────────────────────────────────────────────
            const chartElement = document.querySelector('#systemChart');
            
            // Check if element exists before proceeding
            if (chartElement) {
                const chartOptions = {
                    chart: {
                        type: 'area',
                        height: 320,
                        animations: {
                            enabled: true,
                            easing: 'easeinout'
                        },
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'inherit'
                    },
                    series: [{
                            name: 'CPU',
                            data: statsState.cpu.length ? statsState.cpu : [0]
                        },
                        {
                            name: 'RAM',
                            data: statsState.ram.length ? statsState.ram : [0]
                        },
                        {
                            name: 'Storage',
                            data: statsState.storage.length ? statsState.storage : [0]
                        }
                    ],
                    xaxis: {
                        categories: statsState.labels.length ? statsState.labels : ['-'],
                        labels: {
                            style: {
                                fontSize: '12px',
                                colors: '#64748b'
                            }
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2.5
                    },
                    colors: ['#06b6d4', '#8b5cf6', '#ef4444'],
                    yaxis: {
                        min: 0,
                        max: 100,
                        tickAmount: 5,
                        labels: {
                            formatter: v => v + '%',
                            style: {
                                fontSize: '12px',
                                colors: '#64748b'
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left',
                        fontSize: '13px',
                        markers: {
                            width: 12,
                            height: 12,
                            radius: 12
                        }
                    },
                    grid: {
                        borderColor: '#e2e8f0',
                        strokeDashArray: 4
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: v => v.toFixed(1) + "%"
                        }
                    }
                };

                const systemChart = new ApexCharts(chartElement, chartOptions);
                systemChart.render();

                function updateCharts() {
                    systemChart.updateSeries([{
                            name: 'CPU',
                            data: statsState.cpu
                        },
                        {
                            name: 'RAM',
                            data: statsState.ram
                        },
                        {
                            name: 'Storage',
                            data: statsState.storage
                        }
                    ]);
                    
                    systemChart.updateOptions({
                        xaxis: {
                            categories: statsState.labels
                        }
                    });
                }

                // Initial load
                updateCharts();

                // ────────────────────────────────────────────────
                //  LARAVEL REVERB / ECHO LISTENER
                // ────────────────────────────────────────────────
                if (typeof Echo !== 'undefined') {
                    Echo.channel(`device.${deviceId}`)
                        .listen('.stats.updated', (e) => {
                            const stats = e.stats;
                            const now = new Date().toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit'
                            });

                            // Live update KPI cards
                            updateKPI('cpu', stats.cpu_usage_percent);
                            updateKPI('ram', stats.ram_usage_percent);
                            updateKPI('storage', stats.storage_usage_percent);


                            updateProgress('cpu', stats.cpu_usage_percent);
                            updateProgress('ram', stats.ram_usage_percent);
                            updateProgress('storage', stats.storage_usage_percent);


                            updateStatus(stats.status);
                            updateLastSeen(stats.last_seen_at);
                            updateUptime(stats.uptime_seconds);

                            // Append new data point
                            pushMetric(statsState.cpu, stats.cpu_usage_percent);
                            pushMetric(statsState.ram, stats.ram_usage_percent);
                            pushMetric(statsState.storage, stats.storage_usage_percent);
                            pushMetric(statsState.labels, now);

                            saveState();
                            updateCharts();
                        });
                }
            }
        });
    </script>
@endpush