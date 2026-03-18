<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <a href="{{ route('dashboard') }}" class="btn fs-4 btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Hazard Management</h4>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('transaction-hazards.create') }}" class="btn btn-sm btn-add">
                        <i class="fas fa-plus me-1"></i> Add
                    </a>
                    <button type="button" class="btn btn-sm btn-search" data-bs-toggle="modal"
                        data-bs-target="#searchModal">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    <a href="{{ route('transaction-hazards.index', ['action' => 'REQUEST']) }}"
                        class="btn btn-sm btn-request btn-request">
                        <i class="fas fa-paper-plane me-1"></i> Request
                    </a>
                    <a href="{{ route('transaction-hazards.index', ['action' => 'MONITORING']) }}"
                        class="btn btn-sm btn-monitoring">
                        <i class="fas fa-chart-line me-1"></i> Monitoring
                    </a>
                    <a href="{{ route('transaction-hazards.index', ['action' => 'DEPARTMENT_MONITORING']) }}"
                        class="btn btn-sm btn-deptMonitor">
                        <i class="fas fa-building me-1"></i> Department Monitoring
                    </a>
                    <a href="{{ url('transaction/hazards/export' . str_replace(Request::url(), '', Request::fullUrl())) }}"
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
                <form action="{{ route('transaction-hazards.index') }}" method="GET">
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
                                    placeholder="HAZ-202X-..." value="{{ request('no') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select rounded-pill">
                                    <option value="">-- All Status --</option>
                                    <option value="ACTION_REQUIRED"
                                        {{ request('status') == 'ACTION_REQUIRED' ? 'selected' : '' }}>ACTION_REQUIRED
                                    </option>
                                    <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>
                                        COMPLETED</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Reporter</label>
                                <input type="text" name="reporter_name" class="form-control rounded-pill"
                                    placeholder="Name..." value="{{ request('reporter_name') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Reporter Department</label>
                                <input type="text" name="reporter_department_name" class="form-control rounded-pill"
                                    placeholder="Reporter Department..."
                                    value="{{ request('reporter_department_name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <a href="{{ route('transaction-hazards.index', ['action' => request('action')]) }}"
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
                        <table class="table table-hover align-middle mb-0 table-bordered nowrap w-100">
                            <thead>
                                <tr class="text-nowrap bg-light">
                                    <th class="text-center" style="width: 100px;">Action</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'no', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            No
                                            <i
                                                class="fas {{ request('sort_by') == 'no' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'report_datetime', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Date/Time
                                            <i
                                                class="fas {{ request('sort_by') == 'report_datetime' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'hazard_source', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Source
                                            <i
                                                class="fas {{ request('sort_by') == 'hazard_source' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'hazard_type', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Type
                                            <i
                                                class="fas {{ request('sort_by') == 'hazard_type' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                @forelse ($hazards as $hazard)
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('transaction-hazards.show', $hazard->id) }}"
                                                    class="btn btn-sm tombol-view" title="View">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $hazard->no }}</td>
                                        <td>{{ $hazard->report_datetime }}</td>
                                        <td>{{ $hazard->hazard_source ?? '-' }}</td>
                                        <td>{{ $hazard->hazard_type }}</td>
                                        <td>{{ $hazard->location }}</td>
                                        <td class="text-center"> {{ $hazard->status }}</td>
                                        <td>{{ $hazard->reporter_name }}</td>
                                        <td>{{ $hazard->next_action }}</td>
                                        <td>{{ $hazard->updated_at ? $hazard->updated_at->format('Y-m-d H:i') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing <strong>{{ $hazards->firstItem() ?? 0 }}</strong> to
                            <strong>{{ $hazards->lastItem() ?? 0 }}</strong> of
                            <strong>{{ $hazards->total() }}</strong> entries
                        </div>

                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Tombol Previous --}}
                                @if ($hazards->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link shadow-none">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        {{-- Laravel otomatis menyertakan ?type=... di dalam previousPageUrl() jika sudah di-append di Controller --}}
                                        <a class="page-link shadow-none" href="{{ $hazards->previousPageUrl() }}"
                                            rel="prev">Previous</a>
                                    </li>
                                @endif

                                {{-- Tombol Angka Halaman --}}
                                @foreach ($hazards->getUrlRange(max(1, $hazards->currentPage() - 2), min($hazards->lastPage(), $hazards->currentPage() + 2)) as $page => $url)
                                    <li class="page-item {{ $page == $hazards->currentPage() ? 'active' : '' }}">
                                        <a class="page-link shadow-none"
                                            href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                {{-- Tombol Next --}}
                                @if ($hazards->hasMorePages())
                                    <li class="page-item">
                                        {{-- Laravel otomatis menyertakan ?type=... di dalam nextPageUrl() --}}
                                        <a class="page-link shadow-none" href="{{ $hazards->nextPageUrl() }}"
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

                // --- 1. Global Loading Helper ---
                const showLoading = (title = 'Processing...') => {
                    Swal.fire({
                        title: title,
                        html: 'Please wait a moment.',
                        allowOutsideClick: false,
                        showConfirmButton: false, // Menghilangkan tombol OK agar fokus ke spinner
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                };

                // --- 2. SWAL FOR DELETE CONFIRMATION ---
                const deleteButtons = document.querySelectorAll('.btn-delete');
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        const form = this.closest('.form-delete');

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this data!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#F76361', // UI color
                            cancelButtonColor: '#818181', // UI color
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Using your requested loading format
                                showLoading('Deleting...');
                                form.submit();
                            }
                        });
                    });
                });

                // --- 3. SWAL FOR NAVIGATION LOADING ---
                const actionButtons = document.querySelectorAll(
                    '.btn-add, .btn-request, .btn-monitoring, .btn-deptMonitor');

                actionButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        // Using your requested logic for 'target'
                        if (!this.getAttribute('target')) {
                            // Using your requested loading format
                            showLoading('Loading Data...');
                        }
                    });
                });

                // --- 4. SESSION NOTIFICATIONS ---
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
                        confirmButtonColor: '#F76361'
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
