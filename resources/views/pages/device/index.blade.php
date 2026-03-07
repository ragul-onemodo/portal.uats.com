@extends('layout.index')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">{{ $pageTitle ?? 'List' }}</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">{{ $pageTitle ?? 'List' }}</li>
        </ul>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-3">

                <div class="d-flex align-items-center gap-2">
                    <span>Show</span>
                    <select class="form-select form-select-sm w-auto" id="dt-legnth-filter">
                        <option>10</option>
                        <option>15</option>
                        <option>20</option>
                    </select>
                </div>

                <div class="icon-field">
                    <input type="text" class="form-control form-control-sm w-auto" placeholder="Search"
                        id="dt-search-filter">
                    <span class="icon">
                        <iconify-icon icon="ion:search-outline"></iconify-icon>
                    </span>
                </div>

                {{-- Entity filter --}}
                <div class="d-flex align-items-center gap-2">
                    <span>Entity</span>
                    <select class="form-select form-select-sm w-auto" id="entity-filter">
                        <option value="">All</option>
                        @foreach ($entities as $entity)
                            <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="d-flex flex-wrap align-items-center gap-3">
                <button type="button" class="btn btn-sm btn-primary-600" id="deviceCreate">
                    <i class="ri-add-line"></i>
                    Create Device
                </button>
            </div>
        </div>

        <div class="card-body">
            <table class="table bordered-table mb-0" id="device-table" data-pagination="#dt-pagination">
                <thead></thead>
                <tbody></tbody>
            </table>

            @include('components.datatable-pagination', ['id' => 'dt-pagination'])
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="deviceModal" tabindex="-1" aria-labelledby="deviceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deviceModalLabel">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="deviceModalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {

            const table = $('#device-table').DataTable({
                ajax: {
                    url: '{{ route('devices.dt') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function(d) {
                        d.entity_id = $('#entity-filter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        title: '#'
                    },
                    {
                        data: 'device_name',
                        title: 'Device Name'
                    },
                    {
                        data: 'device_type',
                        title: 'Type'
                    },
                    {
                        data: 'entity_name',
                        title: 'Entity'
                    },
                    {
                        data: 'last_heartbeat_at',
                        title: 'Last Seen'
                    },
                    {
                        data: 'status_badge',
                        title: 'Status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'device_uid',
                        title: 'Device Id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'api_key_masked',
                        title: 'API Key',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        title: 'Actions',
                        orderable: false,
                        searchable: false
                    }
                ],


                pageLength: 15,
                lengthChange: false,
                searching: false,
            });

            $('#device-table').ajaxCrudModal({
                createButton: '#deviceCreate',
                modalSelector: '#deviceModal',
                modalBodySelector: '#deviceModalBody',
                modalTitleSelector: '#deviceModalLabel',
                dataTable: table,
                routes: {
                    create: '{{ route('devices.create') }}',
                    edit: '{{ route('devices.edit', ':id') }}',
                    destroy: '{{ route('devices.destroy', ':id') }}'
                },
                entityName: 'Device'
            });

            $('#entity-filter').on('change', function() {
                table.ajax.reload();
            });

        });
    </script>


    <script>
        /**
         * Toggle device API key visibility
         */
        $(document).on('click', '.toggle-api-key', function() {

            const $btn = $(this);
            const $wrapper = $btn.closest('.api-key-wrapper');
            const $text = $wrapper.find('.api-key');

            if ($text.hasClass('masked')) {
                $text
                    .text($btn.data('value'))
                    .removeClass('masked');

                $btn.find('i')
                    .removeClass('ri-eye-line')
                    .addClass('ri-eye-off-line');
            } else {
                $text
                    .text('••••••••••••••••')
                    .addClass('masked');

                $btn.find('i')
                    .removeClass('ri-eye-off-line')
                    .addClass('ri-eye-line');
            }
        });
    </script>
@endpush
