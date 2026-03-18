<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0">Corrective Action</h4>
            </div>

            <div class="d-flex flex-wrap justify-content-between gap-2">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('transaction-correctiveAction.create') }}" class="btn btn-sm btn-add">
                        <i class="fas fa-plus me-1"></i> Add
                    </a>

                    <button type="button" class="btn btn-sm btn-search" data-bs-toggle="modal"
                        data-bs-target="#searchModal">
                        <i class="fas fa-search me-1"></i> Search
                    </button>

                    <a href="{{ route('transaction-correctiveAction.index', ['action' => 'TO_DO']) }}"
                        class="btn btn-sm btn-todo rounded-pill">
                        To do
                    </a>

                    <a href="{{ route('transaction-correctiveAction.index', ['action' => 'APPROVAL']) }}"
                        class="btn btn-sm btn-approval">
                        <i class="fas fa-check-double me-1"></i> Approval
                    </a>
                    <a href="{{ route('transaction-correctiveAction.index', ['action' => 'APPROVAL_HISTORY']) }}"
                        class="btn btn-sm btn-appvHistory">
                        <i class="fas fa-check-double me-1"></i> Approval History
                    </a>
                    <a href="{{ route('transaction-correctiveAction.index', ['action' => 'MONITORING']) }}"
                        class="btn btn-sm btn-monitoring">
                        <i class="fas fa-chart-line me-1"></i> Monitoring
                    </a>
                    <a href="{{ route('transaction-correctiveAction.index', ['action' => 'DEPARTMENT_MONITORING']) }}"
                        class="btn btn-sm btn-deptMonitor">
                        <i class="fas fa-building me-1"></i> Department Monitoring
                    </a>
                    <a href="{{ url('transaction/corrective-actions/export' . str_replace(Request::url(), '', Request::fullUrl())) }}"
                        class="btn btn-sm btn-exp">
                        <i class="fas fa-download me-1"></i> Export
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="searchModalLabel fw-bold"><i class="fas fa-filter me-2"></i>Filter Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('transaction-correctiveAction.index') }}" method="GET">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="{{ request('action') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Date Of Risk Issue</label>
                                <input type="text" name="risk_issue_date" id="risk_issue_date"
                                    class="form-control rounded-pill" value="{{ request('risk_issue_date') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Source No</label>
                                <input type="text" name="source_no" class="form-control rounded-pill"
                                    placeholder="HAZ-202X-..." value="{{ request('source_no') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select rounded-pill">
                                    <option value="">-- All Status --</option>
                                    <option
                                        value="ACTION_REQUIRED"{{ request('status') == 'ACTION_REQUIRED' ? 'selected' : '' }}>
                                        ACTION_REQUIRED</option>
                                    <option
                                        value="APPROVAL_REQUIRED"{{ request('status') == 'APPROVAL_REQUIRED' ? 'selected' : '' }}>
                                        APPROVAL_REQUIRED</option>
                                    <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>
                                        COMPLETED</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Risk Issuer By</label>
                                <input type="text" name="risk_issuer_name" class="form-control rounded-pill"
                                    placeholder="Name..." value="{{ request('risk_issuer_name') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Department</label>
                                <input type="text" name="department_name" class="form-control rounded-pill"
                                    placeholder="Department..." value="{{ request('department_name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <a href="{{ route('transaction-correctiveAction.index', ['action' => request('action')]) }}"
                            class="btn btn-light rounded-pill px-4">Reset</a>
                        <button type="submit" class="btn btn-search px-4">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-bordered text-nowrap w-100"
                            id="datatable">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-center" style="min-width: 130px;">Action</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            ID
                                            <i
                                                class="fas {{ request('sort_by') == 'id' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'source', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Source
                                            <i
                                                class="fas {{ request('sort_by') == 'source' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'source_no', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Source No
                                            <i
                                                class="fas {{ request('sort_by') == 'source_no' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'risk_issuer_name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Risk Issued By
                                            <i
                                                class="fas {{ request('sort_by') == 'risk_issuer_name' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'risk_issue_date', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Date Of Risk Issue
                                            <i
                                                class="fas {{ request('sort_by') == 'risk_issue_date' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'description', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Description / Task / Finding
                                            <i
                                                class="fas {{ request('sort_by') == 'description' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'location', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Location
                                            <i
                                                class="fas {{ request('sort_by') == 'location' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'department_name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Department
                                            <i
                                                class="fas {{ request('sort_by') == 'department_name' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'responsible_person_name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Responsible Person
                                            <i
                                                class="fas {{ request('sort_by') == 'responsible_person_name' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'corrective_action', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Corrective Action
                                            <i
                                                class="fas {{ request('sort_by') == 'corrective_action' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'due_date', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Date Required Completion Date
                                            <i
                                                class="fas {{ request('sort_by') == 'due_date' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Status
                                            <i
                                                class="fas {{ request('sort_by') == 'status' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'next_user_name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Next User
                                            <i
                                                class="fas {{ request('sort_by') == 'next_user_name' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'next_action', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Next Action
                                            <i
                                                class="fas {{ request('sort_by') == 'next_action' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'updated_at', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Last Updated
                                            <i
                                                class="fas {{ request('sort_by') == 'updated_at' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($corrAct as $c)
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('transaction-correctiveAction.show', $c->id) }}"
                                                    class="btn btn-sm tombol-view" title="View">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                        <td class="fw-bold">{{ $c->id }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $c->source }}</span>
                                        </td>
                                        <td>{{ $c->source_no }}</td>
                                        <td>{{ $c->risk_issuer_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($c->risk_issue_date)->format('d M Y') }}</td>
                                        <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $c->risk_description }}
                                        </td>
                                        <td>{{ $c->location }}</td>
                                        <td>{{ $c->department_name }}</td>
                                        <td>{{ $c->responsible_person_name }}</td>
                                        <td>{{ $c->corrective_action }}</td>
                                        <td>{{ $c->due_date }}</td>
                                        <td class="text-center">{{ $c->status }}</td>
                                        <td>{{ $c->next_user_name }}</td>
                                        <td>{{ $c->next_action }}</td>
                                        <td>{{ $c->updated_at ? $c->updated_at->format('Y-m-d H:i') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing <strong>{{ $corrAct->firstItem() ?? 0 }}</strong> to
                            <strong>{{ $corrAct->lastItem() ?? 0 }}</strong> of
                            <strong>{{ $corrAct->total() }}</strong> entries
                        </div>

                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                @if ($corrAct->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link shadow-none">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link shadow-none" href="{{ $corrAct->previousPageUrl() }}"
                                            rel="prev">Previous</a>
                                    </li>
                                @endif
                                @foreach ($corrAct->getUrlRange(max(1, $corrAct->currentPage() - 2), min($corrAct->lastPage(), $corrAct->currentPage() + 2)) as $page => $url)
                                    <li class="page-item {{ $page == $corrAct->currentPage() ? 'active' : '' }}">
                                        <a class="page-link shadow-none"
                                            href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach
                                @if ($corrAct->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link shadow-none" href="{{ $corrAct->nextPageUrl() }}"
                                            rel="next">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled"><span class="page-link shadow-none">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <style>
            .daterangepicker td.available,
            .daterangepicker th.available {
                color: #333 !important;
            }

            .daterangepicker td.off,
            .daterangepicker td.off.in-range,
            .daterangepicker td.off.start-date,
            .daterangepicker td.off.end-date {
                color: #999 !important;
            }

            .daterangepicker {
                z-index: 9999 !important;
                border: 1px solid #ddd;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
        </style>
    @endpush
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#risk_issue_date').daterangepicker({
                    autoUpdateInput: false,
                    alwaysShowCalendars: true,
                    opens: 'left',
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    },
                    locale: {
                        format: 'YYYY-MM-DD',
                        separator: " to ",
                        applyLabel: "Select",
                        cancelLabel: "Clear"
                    }
                });

                $('#risk_issue_date').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                        'YYYY-MM-DD'));
                });

                $('#risk_issue_date').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });


                const showLoading = (title = 'Processing...') => {
                    Swal.fire({
                        title: title,
                        html: 'Please wait a moment.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                };
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        timer: 2000,
                        showConfirmButton: false
                    });
                @endif
                $('.btn-confirm-delete').on('click', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This data will be permanently deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading('Deleting...');
                            form.submit();
                        }
                    });
                });
                $('.btn-add, .btn-request, .btn-approval, .btn-monitoring, .btn-warning, .btn-info').on('click',
                    function() {
                        if ($(this).is('a')) {
                            showLoading('Loading Page...');
                        }
                    });
                $('form').on('submit', function() {
                    const btn = $(document.activeElement);
                    if (btn.val() === 'APPROVE') showLoading('Approving...');
                    else if (btn.val() === 'REJECT') showLoading('Rejecting...');
                });
                $(document).on('click', '.btn-approve-swal', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This document will be approved!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, approve it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading('Approving...');
                            form.append('<input type="hidden" name="action" value="APPROVE">');
                            form.submit();
                        }
                    });
                });
                $(document).on('click', '.btn-reject-swal', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This document will be rejected!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, reject it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading('Rejecting...');
                            form.append('<input type="hidden" name="action" value="REJECT">');
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
