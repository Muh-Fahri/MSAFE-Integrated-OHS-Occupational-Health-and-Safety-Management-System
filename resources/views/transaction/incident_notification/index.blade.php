<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center">
                <a href="{{ route('dashboard') }}" class="btn fs-4 btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Incident Notification</h4>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('transaction-incidentNotification.create') }}" class="btn btn-sm btn-add">
                        <i class="fas fa-plus me-1"></i> Add
                    </a>
                    <a href="{{ route('transaction-incidentNotification.index', ['action' => 'REQUEST']) }}"
                        class="btn btn-sm btn-request">
                        <i class="fas fa-paper-plane me-1"></i> Request
                    </a>
                    <button type="button" class="btn btn-sm btn-search" data-bs-toggle="modal"
                        data-bs-target="#searchModal">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    <a href="{{ route('transaction-incidentNotification.index', ['action' => 'APPROVAL']) }}"
                        class="btn btn-sm btn-approval">
                        <i class="fas fa-check-double me-1"></i> Approval
                    </a>
                    <a href="{{ route('transaction-incidentNotification.index', ['action' => 'APPROVAL_HISTORY']) }}"
                        class="btn btn-sm btn-appvHistory">
                        <i class="fas fa-history me-1"></i> Approval History
                    </a>
                    <a href="{{ route('transaction-incidentNotification.index', ['action' => 'MONITORING']) }}"
                        class="btn btn-sm btn-monitoring">
                        <i class="fas fa-chart-line me-1"></i> Monitoring
                    </a>
                    <a href="{{ route('transaction-incidentNotification.index', ['action' => 'DEPARTMENT_MONITORING']) }}"
                        class="btn btn-sm btn-deptMonitor">
                        <i class="fas fa-building me-1"></i> Department Monitoring
                    </a>
                    <a href="{{ url('transaction/incident-notification/export' . str_replace(Request::url(), '', Request::fullUrl())) }}"
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
                <form action="{{ route('transaction-incidentNotification.index') }}" method="GET">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="{{ request('action') }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Report Date</label>
                                <input type="text" name="report_date" id="report_date"
                                    class="form-control rounded-pill" value="{{ request('report_date') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Report No</label>
                                <input type="text" name="no" class="form-control rounded-pill"
                                    placeholder="IN-202X-..." value="{{ request('no') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select rounded-pill">
                                    <option value="">-- All Status --</option>
                                    <option value="APPROVAL_REQUIRED"
                                        {{ request('status') == 'APPROVAL_REQUIRED' ? 'selected' : '' }}>
                                        APPROVAL_REQUIRED</option>
                                    <option value="INVESTIGATION_REQUIRED"
                                        {{ request('status') == 'INVESTIGATION_REQUIRED' ? 'selected' : '' }}>
                                        INVESTIGATION_REQUIRED</option>
                                    <option value="INVESTIGATION_APPROVAL_REQUIRED"
                                        {{ request('status') == 'INVESTIGATION_APPROVAL_REQUIRED' ? 'selected' : '' }}>
                                        INVESTIGATION_APPROVAL_REQUIRED</option>
                                    <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>
                                        COMPLETED</option>
                                    <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>
                                        REJECTED</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Reporter Name</label>
                                <input type="text" name="reporter_name" class="form-control rounded-pill"
                                    placeholder="Name..." value="{{ request('reporter_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Department Name</label>
                                <input type="text" name="department_name" class="form-control rounded-pill"
                                    placeholder="Department..." value="{{ request('department_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Company Name</label>
                                <input type="text" name="company_name" class="form-control rounded-pill"
                                    placeholder="Company..." value="{{ request('company_name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <a href="{{ route('transaction-incidentNotification.index', ['action' => request('action')]) }}"
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
                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 text-nowrap">
                            <thead>
                                <tr class="text-nowrap bg-light">
                                    <th class="px-3 text-center">Action</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            ID
                                            <i
                                                class="fas {{ request('sort_by') == 'id' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'no', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            No
                                            <i
                                                class="fas {{ request('sort_by') == 'no' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'report_date', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Report Date
                                            <i
                                                class="fas {{ request('sort_by') == 'report_date' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'event_title', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Event Title
                                            <i
                                                class="fas {{ request('sort_by') == 'event_title' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'event_datetime', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Date/Time
                                            <i
                                                class="fas {{ request('sort_by') == 'event_datetime' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'event_type', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Event Type
                                            <i
                                                class="fas {{ request('sort_by') == 'event_type' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                    <th class="text-center">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Status
                                            <i
                                                class="fas {{ request('sort_by') == 'status' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'reporter_name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Reporter
                                            <i
                                                class="fas {{ request('sort_by') == 'reporter_name' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>Remarks</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'next_action', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Next Action
                                            <i
                                                class="fas {{ request('sort_by') == 'next_action' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                @foreach ($data as $item)
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('transaction-incidentNotification.show', $item->id) }}"
                                                    class="btn btn-sm tombol-view" title="View">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->no }}</td>
                                        <td>{{ $item->report_date }}</td>
                                        <td>{{ $item->event_title }}</td>
                                        <td>
                                            @php
                                                $rawDate = $item->event_dateTime ?? $item->event_datetime;
                                            @endphp
                                            @if (!empty($rawDate) && $rawDate !== '0000-00-00 00:00:00' && $rawDate !== '0000-00-00')
                                                {{ \Carbon\Carbon::parse($rawDate)->format('d-m-Y H:i') }}
                                            @else
                                                <span class="text-muted text-italic" style="font-size: 11px;">(No
                                                    Data)</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->event_type }}</td>
                                        <td>{{ $item->location }}</td>
                                        <td class="text-center">{{ $item->status }}</td>
                                        <td>{{ $item->reporter_name }}</td>
                                        <td>{{ $item->remarks }}</td>
                                        <td>{{ $item->next_action }}</td>
                                        <td>{{ $item->next_user_name }}</td>
                                        <td>{{ $item->updated_at ? $item->updated_at->format('Y-m-d H:i') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION DI BAWAH TABEL --}}
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing <strong>{{ $data->firstItem() ?? 0 }}</strong> to
                            <strong>{{ $data->lastItem() ?? 0 }}</strong> of
                            <strong>{{ $data->total() }}</strong> entries
                        </div>

                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Tombol Previous --}}
                                @if ($data->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link shadow-none">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link shadow-none" href="{{ $data->previousPageUrl() }}"
                                            rel="prev">Previous</a>
                                    </li>
                                @endif

                                {{-- Tombol Angka --}}
                                @foreach ($data->getUrlRange(max(1, $data->currentPage() - 2), min($data->lastPage(), $data->currentPage() + 2)) as $page => $url)
                                    <li class="page-item {{ $page == $data->currentPage() ? 'active' : '' }}">
                                        <a class="page-link shadow-none"
                                            href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                {{-- Tombol Next --}}
                                @if ($data->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link shadow-none" href="{{ $data->nextPageUrl() }}"
                                            rel="next">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link shadow-none">Next</span>
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
        <script src="{{ asset('Minible/HTML/dist/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#report_date').daterangepicker({
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

                $('#report_date').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                        'YYYY-MM-DD'));
                });

                $('#report_date').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });

                // --- 1. Fungsi Helper Loading ---
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

                // --- 2. Inisialisasi DataTable ---
                $('#datatable').DataTable({
                    "processing": true,
                    "paging": false,
                    "info": false,
                    "searching": false,
                    "language": {
                        "processing": '<div class="spinner-border text-primary" role="status"></div>'
                    }
                });

                // --- 3. Notifikasi Session (Success/Error) ---
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        timer: 2000,
                        showConfirmButton: false
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: "{{ session('error') }}",
                    });
                @endif

                // --- 4. Event Click: Navigasi & Tombol View/Edit ---
                $('.btn-add, .btn-request, .btn-approval, .btn-monitoring, .btn-appvHistory, .btn-deptMonitor, .btn-warning, .btn-info')
                    .on('click', function() {
                        if ($(this).is('a') && !$(this).hasClass('dropdown-toggle')) {
                            showLoading('Loading Page...');
                        }
                    });

                // --- 5. Event Click: Delete Confirmation ---
                $(document).on('click', '.btn-confirm-delete', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f46a6a',
                        cancelButtonColor: '#74788d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading('Deleting data...');
                            form.submit();
                        }
                    });
                });

                // --- 6. Event Click: Approve Confirmation ---
                $(document).on('click', '.btn-approve-swal', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('form');
                    Swal.fire({
                        title: 'Approve Document?',
                        text: "Apakah Anda yakin ingin menyetujui laporan ini?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#34c38f',
                        cancelButtonColor: '#74788d',
                        confirmButtonText: 'Yes, Approve!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading('Approving...');
                            // Pastikan input action ada jika diperlukan controller
                            if (form.find('input[name="action"]').length === 0) {
                                form.append('<input type="hidden" name="action" value="APPROVE">');
                            }
                            form.submit();
                        }
                    });
                });

                // --- 7. Event Click: Reject Confirmation ---
                $(document).on('click', '.btn-reject-swal', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('form');
                    Swal.fire({
                        title: 'Reject Document?',
                        text: "Berikan alasan penolakan jika diperlukan.",
                        icon: 'warning',
                        input: 'text', // Menambahkan input teks alasan langsung di SWAL
                        inputPlaceholder: 'Tulis alasan di sini...',
                        showCancelButton: true,
                        confirmButtonColor: '#f46a6a',
                        cancelButtonColor: '#74788d',
                        confirmButtonText: 'Yes, Reject!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading('Rejecting...');
                            if (form.find('input[name="action"]').length === 0) {
                                form.append('<input type="hidden" name="action" value="REJECT">');
                            }
                            // Menambahkan alasan dari input SWAL ke form
                            if (result.value) {
                                form.append('<input type="hidden" name="remarks" value="' + result
                                    .value + '">');
                            }
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
