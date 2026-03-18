<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <a href="{{ route('dashboard') }}" class="btn fs-4 btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">License to Operate (LTO)</h4>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('transaction-license.create') }}" class="btn btn-sm btn-add"
                    style="background-color: #5b73e8;">
                    <i class="fas fa-plus me-1"></i> Add
                </a>
                <button type="button" class="btn btn-sm btn-search" data-bs-toggle="modal"
                    data-bs-target="#searchModal">
                    <i class="fas fa-search me-1"></i> Search
                </button>
                <a href="{{ route('transaction-license.index', ['action' => 'REQUEST']) }}"
                    class="btn btn-sm btn-request">
                    <i class="fas fa-paper-plane me-1"></i> Request
                </a>
                <a href="{{ route('transaction-license.index', ['action' => 'APPROVAL']) }}"
                    class="btn btn-sm btn-approval">
                    <i class="fas fa-check-double me-1"></i> Approval
                </a>
                <a href="{{ route('transaction-license.index', ['action' => 'APPROVAL_HISTORY']) }}"
                    class="btn btn-sm btn-appvHistory">
                    <i class="fas fa-check-double me-1"></i> Approval History
                </a>
                <a href="{{ route('transaction-license.index', ['action' => 'MONITORING']) }}"
                    class="btn btn-sm btn-monitoring">
                    <i class="fas fa-chart-line me-1"></i> Monitoring
                </a>
                <a href="{{ route('transaction-license.index', ['action' => 'DEPARTMENT_MONITORING']) }}"
                    class="btn btn-sm btn-deptMonitor">
                    <i class="fas fa-chart-line me-1"></i>Department Monitoring
                </a>
                <a href="{{ url('transaction/license/export' . str_replace(Request::url(), '', Request::fullUrl())) }}"
                    class="btn btn-sm btn-exp">
                    <i class="fas fa-download me-1"></i> Export
                </a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="searchModalLabel fw-bold"><i class="fas fa-filter me-2"></i>Filter Data
                        License</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('transaction-license.index') }}" method="GET">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="{{ request('action') }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Request Date</label>
                                <input type="text" name="request_date" id="request_date"
                                    class="form-control rounded-pill" value="{{ request('request_date') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Request No</label>
                                <input type="text" name="no" class="form-control rounded-pill"
                                    placeholder="Cth: 001/LIC/..." value="{{ request('no') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select rounded-pill">
                                    <option value="">-- All Status --</option>
                                    <option
                                        value="APPROVAL_REQUIRED"{{ request('status') == 'APPROVAL_REQUIRED' ? 'selected' : '' }}>
                                        APPROVAL_REQUIRED</option>
                                    <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>
                                        COMPLETED</option>
                                    <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>
                                        REJECTED</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Requestor Name</label>
                                <input type="text" name="requestor_name" class="form-control rounded-pill"
                                    placeholder="Nama pemohon..." value="{{ request('requestor_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Employee ID</label>
                                <input type="text" name="employee_id" class="form-control rounded-pill"
                                    placeholder="Employee ID peserta..." value="{{ request('employee_id') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Name</label>
                                <input type="text" name="name" class="form-control rounded-pill"
                                    placeholder="Nama peserta..." value="{{ request('name') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Company Name</label>
                                <input type="text" name="company_name" class="form-control rounded-pill"
                                    placeholder="Nama perusahaan..." value="{{ request('company_name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        {{-- Link Reset harus mengarah ke index dengan membawa 'type' yang sama --}}
                        <a href="{{ route('transaction-license.index', ['action' => request('action')]) }}"
                            class="btn btn-light rounded-pill px-4">Reset</a>
                        <button type="submit" class="btn btn-search px-4 text-white"
                            style="background-color: #0d6efd;">Apply Filter</button>
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
                        <table class="table table-hover align-middle mb-0 table-bordered text-nowrap w-100">
                            <thead class="bg-light">
                                <tr class="text-nowrap">
                                    <th class="text-center" style="min-width: 120px;">Action</th>
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'date', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Date
                                            <i
                                                class="fas {{ request('sort_by') == 'date' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'type', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Type
                                            <i
                                                class="fas {{ request('sort_by') == 'type' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'employee_id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Employee ID
                                            <i
                                                class="fas {{ request('sort_by') == 'employee_id' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Name
                                            <i
                                                class="fas {{ request('sort_by') == 'name' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'position', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Position
                                            <i
                                                class="fas {{ request('sort_by') == 'position' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'department_name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Date Of Risk Issue
                                            <i
                                                class="fas {{ request('sort_by') == 'department_name' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'company_name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Company
                                            <i
                                                class="fas {{ request('sort_by') == 'company_name' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'license_status', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            License Status
                                            <i
                                                class="fas {{ request('sort_by') == 'license_status' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                @foreach ($licen as $index => $li)
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                {{-- Action Buttons --}}
                                                <a href="{{ route('transaction-license.show', $li->id) }}"
                                                    class="btn btn-sm tombol-view" title="View">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $li->id }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $li->no }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($li->date)->format('d M Y') }}</td>
                                        <td>{{ $li->type }}</td>
                                        <td>{{ $li->employee_id }}</td>
                                        <td class="fw-medium">{{ $li->name }}</td>
                                        <td><small>{{ $li->position }}</small></td>
                                        <td>{{ $li->department_name }}</td>
                                        <td>{{ $li->company_name }}</td>
                                        <td>{{ $li->license_status }}</td>
                                        <td class="text-center"> {{ $li->status }}</td>
                                        <td><small class="text-muted">{{ $li->next_action ?? '-' }}</small></td>
                                        <td>{{ $li->next_user_name ?? '-' }}</td>
                                        <td>{{ $li->updated_at ? $li->updated_at->format('Y-m-d H:i') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing {{ $licen->firstItem() }} to {{ $licen->lastItem() }} of {{ $licen->total() }}
                            entries
                        </div>

                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Tombol Previous --}}
                                @if ($licen->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                @else
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $licen->previousPageUrl() }}">Previous</a></li>
                                @endif

                                {{-- Tombol Angka Halaman --}}
                                @foreach ($licen->getUrlRange(max(1, $licen->currentPage() - 2), min($licen->lastPage(), $licen->currentPage() + 2)) as $page => $url)
                                    <li class="page-item {{ $page == $licen->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                {{-- Tombol Next --}}
                                @if ($licen->hasMorePages())
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $licen->nextPageUrl() }}">Next</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">Next</span></li>
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
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#request_date').daterangepicker({
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

                $('#request_date').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                        'YYYY-MM-DD'));
                });

                $('#request_date').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>

<style>
    /* Styling tambahan agar mirip dengan UI Hazard Management Anda */
    .bg-soft-primary {
        background-color: rgba(91, 115, 232, 0.1);
    }

    .bg-soft-info {
        background-color: rgba(80, 165, 241, 0.1);
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
    }

    .card {
        border: none;
        border-radius: 8px;
    }
</style>
