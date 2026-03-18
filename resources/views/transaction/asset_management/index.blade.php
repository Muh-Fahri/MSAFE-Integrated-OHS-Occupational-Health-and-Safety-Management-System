<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <a href="{{ route('dashboard') }}" class="btn fs-4 btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Asset Management</h4>
            </div>

            <div class="d-flex flex-wrap justify-content-between gap-2">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('transaction-asset.create') }}" class="btn btn-sm btn-add">
                        <i class="fas fa-plus me-1"></i> Add
                    </a>
                    <button type="button" class="btn btn-sm btn-search" data-bs-toggle="modal"
                        data-bs-target="#searchModal">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    <a href="{{ route('transaction-asset.index', ['action' => 'REQUEST']) }}"
                        class="btn btn-sm btn-request text-white">
                        <i class="fas fa-paper-plane me-1"></i> Request
                    </a>
                    <a href="{{ route('transaction-asset.index', ['action' => 'APPROVAL']) }}"
                        class="btn  btn-sm btn-approval">
                        <i class="fas fa-check-double me-1"></i> Approval
                    </a>
                    <a href="{{ route('transaction-asset.index', ['action' => 'APPROVAL_HISTORY']) }}"
                        class="btn btn-sm btn-appvHistory">
                        <i class="fas fa-check-double me-1"></i>Approval History
                    </a>
                    <a href="{{ route('transaction-asset.index', ['action' => 'MONITORING']) }}"
                        class="btn btn-sm btn-monitoring">
                        <i class="fas fa-chart-line me-1"></i> Monitoring
                    </a>
                    <a href="{{ route('transaction-asset.index', ['action' => 'DEPARTMENT_MONITORING']) }}"
                        class="btn btn-sm btn-deptMonitor">
                        <i class="fas fa-chart-line me-1"></i>Department Monitoring
                    </a>
                    <a href="{{ url('transaction/asset/export' . str_replace(Request::url(), '', Request::fullUrl())) }}"
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
                <form action="{{ route('transaction-asset.index') }}" method="GET">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="{{ request('action') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Register Date</label>
                                <input type="text" name="register_date" id="register_date"
                                    class="form-control rounded-pill" value="{{ request('register_date') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Code</label>
                                <input type="text" name="code" class="form-control rounded-pill" placeholder=""
                                    value="{{ request('code') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Name</label>
                                <input type="text" name="name" class="form-control rounded-pill" placeholder=""
                                    value="{{ request('name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Type</label>
                                <select name="type[]" id="type" class="select2 form-control select2-multiple"
                                    multiple="multiple">
                                    @foreach ($list_type as $type)
                                        <option value="{{ $type }}"
                                            {{ is_array(request('type')) && in_array($type, request('type')) ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Category</label>
                                <select name="category[]" id="category" class="select2 form-control select2-multiple"
                                    multiple="multiple">
                                    @foreach ($list_category as $category)
                                        <option value="{{ $category }}"
                                            {{ is_array(request('category')) && in_array($category, request('category')) ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status[]" id="status" class="select2 form-control select2-multiple"
                                    multiple="multiple">
                                    @foreach ($list_status as $status)
                                        <option value="{{ $status }}"
                                            {{ is_array(request('status')) && in_array($status, request('status')) ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Department</label>
                                <select name="department_id[]" id="department_id"
                                    class="select2 form-control select2-multiple" multiple="multiple">
                                    @foreach ($list_department as $department_id => $department_name)
                                        <option value="{{ $department_id }}"
                                            {{ is_array(request('department_id')) && in_array($department_id, request('department_id')) ? 'selected' : '' }}>
                                            {{ $department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Company</label>
                                <select name="company_id[]" id="company_id"
                                    class="select2 form-control select2-multiple" multiple="multiple">
                                    @foreach ($list_company as $company_id => $company_name)
                                        <option value="{{ $company_id }}"
                                            {{ is_array(request('company_id')) && in_array($company_id, request('company_id')) ? 'selected' : '' }}>
                                            {{ $company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Approval Status</label>
                                <select name="approval_status[]" id="approval_status"
                                    class="select2 form-control select2-multiple" multiple="multiple">
                                    @foreach ($list_approval_status as $approval_status)
                                        <option value="{{ $approval_status }}"
                                            {{ is_array(request('approval_status')) && in_array($approval_status, request('approval_status')) ? 'selected' : '' }}>
                                            {{ $approval_status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Requestor Name</label>
                                <input type="text" name="requestor_name" class="form-control rounded-pill"
                                    placeholder="" value="{{ request('requestor_name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <a href="{{ route('transaction-asset.index', ['action' => request('action')]) }}"
                            class="btn btn-light rounded-pill px-4">Reset</a>
                        <button type="submit" class="btn btn-search px-4">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-bordered nowrap w-100">
                            <thead>
                                <tr class="text-nowrap bg-light ">
                                    <th class="text-center">Action</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            ID
                                            <i
                                                class="fas {{ request('sort_by') == 'id' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'code', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Code
                                            <i
                                                class="fas {{ request('sort_by') == 'code' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'register_date', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Register Date
                                            <i
                                                class="fas {{ request('sort_by') == 'register_date' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'category', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Category
                                            <i
                                                class="fas {{ request('sort_by') == 'category' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'ownership', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Ownership
                                            <i
                                                class="fas {{ request('sort_by') == 'ownership' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'commissioning_date', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Commissioning Date
                                            <i
                                                class="fas {{ request('sort_by') == 'commissioning_date' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'company_name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Company
                                            <i
                                                class="fas {{ request('sort_by') == 'company_name' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
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
                                @forelse ($data as $item)
                                    <tr class="text-nowrap">
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                {{-- Button View --}}
                                                <a href="{{ route('transaction-asset.show', $item->id) }}"
                                                    class="btn btn-sm btn-secondary tombol-view" title="View">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->register_date)->format('d/m/Y') }}</td>
                                        <td>{{ $item->type }}</td>
                                        <td>{{ $item->category }}</td>
                                        <td>{{ $item->ownership }}</td>
                                        <td>{{ $item->commissioning_date }}</td>
                                        <td>{{ $item->department_name }}</td>
                                        <td>{{ $item->company_name }}</td>
                                        <td> {{ $item->status }}</td>
                                        <td>{{ $item->next_action }}</td>
                                        <td>{{ $item->next_user_name ?? 'System' }}</td>
                                        <td>{{ $item->updated_at ? $item->updated_at->format('Y-m-d H:i') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="16" class="text-center">No data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} of
                            {{ $data->total() ?? 0 }} entries
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                @if ($data->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                @else
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $data->previousPageUrl() }}">Previous</a></li>
                                @endif
                                @foreach ($data->getUrlRange(max(1, $data->currentPage() - 2), min($data->lastPage(), $data->currentPage() + 2)) as $page => $url)
                                    <li class="page-item {{ $page == $data->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach
                                @if ($data->hasMorePages())
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $data->nextPageUrl() }}">Next</a></li>
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#type, #category, #department_id, #company_id, #approval_status, #status').select2({
                    width: '100%',
                    dropdownParent: $('#searchModal')
                });
                $('#register_date').daterangepicker({
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

                $('#register_date').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                        'YYYY-MM-DD'));
                });

                $('#register_date').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });

                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                @endif
                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "{{ session('error') }}",
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
