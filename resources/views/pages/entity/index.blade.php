@extends('layout.index')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">{{ $pagetitle ?? 'List' }}</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">{{ $pagetitle ?? 'List' }}</li>
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
                    <input type="text" name="#0" class="form-control form-control-sm w-auto" placeholder="Search"
                        id="dt-search-filter">
                    <span class="icon">
                        <iconify-icon icon="ion:search-outline"></iconify-icon>
                    </span>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <select class="form-select form-select-sm w-auto">
                    <option>Satatus</option>
                    <option>Paid</option>
                    <option>Pending</option>
                </select>
                <button type="button" class="btn btn-sm btn-primary-600" id="entityCreate"><i class="ri-add-line"></i>
                    Create
                    Entity</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table bordered-table mb-0" id="entity-table" data-pagination="#dt-pagination">
                <thead>
                </thead>
                <tbody>

                </tbody>
            </table>

            @include('components.datatable-pagination', ['id' => 'dt-pagination'])

        </div>
    </div>


    <div class="modal fade" id="entityModal" tabindex="-1" aria-labelledby="entityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="entityModalLabel">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="entityModalBody">
                    <!-- AJAX content will be placed here -->
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


            const table = $('#entity-table').DataTable({

                ajax: {
                    url: '{{ route('entities.dt') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',

                        orderable: false,
                        searchable: false,
                        title: '#'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        title: 'Name'
                    },
                    {
                        data: 'integration_enabled',
                        name: 'integration_enabled',
                        title: "Integration Status"

                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        title: 'Status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Actions',
                        orderable: false,
                        searchable: false,
                    }
                ],
                pageLength: 15,
                lengthChange: false,
                searching: false,
            });


            $('#entity-table').ajaxCrudModal({
                createButton: '#entityCreate',
                modalSelector: '#entityModal',
                modalBodySelector: '#entityModalBody',
                modalTitleSelector: '#entityModalLabel',
                dataTable: table,
                routes: {
                    create: '{{ route('entities.create') }}', // change route name
                    edit: '{{ route('entities.edit', ':id') }}',
                    destroy: '{{ route('entities.destroy', ':id') }}'
                },
                entityName: 'Entity'
            });
        });
    </script>
@endpush
