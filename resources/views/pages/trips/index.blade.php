@extends('layout.index')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">{{ $pageTitle ?? 'Trips List' }}</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">{{ $pageTitle ?? 'Trips List' }}</li>
        </ul>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span>Show</span>
                    <select class="form-select form-select-sm w-auto" id="dt-length-filter">
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
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <select class="form-select form-select-sm w-auto" id="trip-filter">
                    <option value="">All Trips</option>
                    <option value="IN">IN</option>
                    <option value="OUT">OUT</option>
                </select>
                <button type="button" class="btn btn-sm btn-primary-600" id="tripCreate">
                    <i class="ri-add-line"></i>
                    Create Trip
                </button>
            </div>
        </div>

        <div class="card-body">
            <table class="table bordered-table mb-0" id="trip-table" data-pagination="#dt-pagination">
                <thead></thead>
                <tbody></tbody>
            </table>

            @include('components.datatable-pagination', ['id' => 'dt-pagination'])
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="tripModal" tabindex="-1" aria-labelledby="tripModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tripModalLabel">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="tripModalBody">
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

            const table = $('#trip-table').DataTable({
                ajax: {
                    url: '{{ route('trips.datatable') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function(d) {
                        d.trip_id = $('#trip-filter').val();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        title: '#'
                    },
                    {
                        data: 'device_name',
                        name: 'device.name',
                        title: 'Device Name'
                    },
                    {
                        data: 'entity_name',
                        name: 'entity.name',
                        title: 'Entity Name'
                    },
                    {
                        data: 'direction',
                        name: 'direction',
                        title: 'Direction'
                    },
                    {
                        data: 'weight',
                        name: 'weight',
                        title: 'Weight'
                    },
                    {
                        data: 'device_timestamp',
                        name: 'device_timestamp',
                        title: 'Trip Date Time'
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     title: 'Actions',
                    //     orderable: false,
                    //     searchable: false
                    // }
                ],
                pageLength: 15,
                lengthChange: false,
                searching: false,
            });

            $('#trip-table').ajaxCrudModal({
                createButton: '#tripCreate',
                modalSelector: '#tripModal',
                modalBodySelector: '#tripModalBody',
                modalTitleSelector: '#tripModalLabel',
                dataTable: table,
                routes: {
                    create: '{{ route('trips.create') }}',
                    edit: '{{ route('trips.edit', ':id') }}',
                    destroy: '{{ route('trips.destroy', ':id') }}'
                },
                entityName: 'Trip'
            });

            // Reload table automatically when the filter dropdown changes
            $('#trip-filter').on('change', function() {
                table.ajax.reload(null, false);
            });
        });
    </script>
@endpush