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
                <button type="button" class="btn btn-sm btn-primary-600" id="entityApplicationCreate">
                    <i class="ri-add-line"></i>
                    Create Entity Application
                </button>
            </div>
        </div>

        <div class="card-body">
            <table class="table bordered-table mb-0" id="entity-application-table" data-pagination="#dt-pagination">
                <thead></thead>
                <tbody></tbody>
            </table>

            @include('components.datatable-pagination', ['id' => 'dt-pagination'])
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="entityApplicationModal" tabindex="-1" aria-labelledby="entityApplicationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="entityApplicationModalLabel">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="entityApplicationModalBody">
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

            const table = $('#entity-application-table').DataTable({
                ajax: {
                    url: '{{ route('entity-applications.dt') }}',
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
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        title: '#'
                    },
                    {
                        data: 'entity',
                        name: 'entity',
                        title: 'Entity'
                    },
                    {
                        data: 'application',
                        name: 'application',
                        title: 'Application'
                    },
                    {
                        data: 'company_reference',
                        title: 'Company Reference',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            if (!data) {
                                return '-';
                            }

                            return `
                        <div class="d-flex align-items-center gap-2 company-ref-wrapper">
                            <span class="company-ref masked">••••••••</span>
                            <button type="button"
                                    class="btn btn-sm btn-light toggle-company-ref"
                                    data-value="${data}">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                    `;
                        }
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

            $('#entity-application-table').ajaxCrudModal({
                createButton: '#entityApplicationCreate',
                modalSelector: '#entityApplicationModal',
                modalBodySelector: '#entityApplicationModalBody',
                modalTitleSelector: '#entityApplicationModalLabel',
                dataTable: table,
                routes: {
                    create: '{{ route('entity-applications.create') }}',
                    edit: '{{ route('entity-applications.edit', ':id') }}',
                    destroy: '{{ route('entity-applications.destroy', ':id') }}'
                },
                entityName: 'Entity Application'
            });


            $('#entity-filter').on('change', function() {
                table.ajax.reload();
            });


        });
    </script>

    <script>
        /**
         * Toggle company reference visibility
         * Works with DataTables redraw & pagination
         */
        $(document).on('click', '.toggle-company-ref', function() {

            const $btn = $(this);
            const $wrapper = $btn.closest('.company-ref-wrapper');
            const $text = $wrapper.find('.company-ref');

            if ($text.hasClass('masked')) {
                // Reveal
                $text.text($btn.data('value'))
                    .removeClass('masked');

                $btn.find('i')
                    .removeClass('ri-eye-line')
                    .addClass('ri-eye-off-line');
            } else {
                // Hide
                $text.text('••••••••')
                    .addClass('masked');

                $btn.find('i')
                    .removeClass('ri-eye-off-line')
                    .addClass('ri-eye-line');
            }
        });
    </script>
@endpush
