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
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <button type="button" class="btn btn-sm btn-primary-600" id="applicationCreate">
                    <i class="ri-add-line"></i>
                    Create Application
                </button>
            </div>
        </div>

        <div class="card-body">
            <table class="table bordered-table mb-0" id="application-table" data-pagination="#dt-pagination">
                <thead></thead>
                <tbody></tbody>
            </table>

            @include('components.datatable-pagination', ['id' => 'dt-pagination'])
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="applicationModal" tabindex="-1" aria-labelledby="applicationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applicationModalLabel">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="applicationModalBody">
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

            const table = $('#application-table').DataTable({
                ajax: {
                    url: '{{ route('applications.dt') }}',
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
                        title: 'Application Name'
                    },
                    {
                        data: 'code',
                        name: 'code',
                        title: 'Code'
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
                        searchable: false
                    }
                ],
                pageLength: 15,
                lengthChange: false,
                searching: false,
            });

            $('#application-table').ajaxCrudModal({
                createButton: '#applicationCreate',
                modalSelector: '#applicationModal',
                modalBodySelector: '#applicationModalBody',
                modalTitleSelector: '#applicationModalLabel',
                dataTable: table,
                routes: {
                    create: '{{ route('applications.create') }}',
                    edit: '{{ route('applications.edit', ':id') }}',
                    destroy: '{{ route('applications.destroy', ':id') }}'
                },
                entityName: 'Application'
            });

        });
    </script>
@endpush
