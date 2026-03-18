<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0">Monthly Contractor Reports</h4>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('transaction-monthly-reports.create') }}" class="btn btn-primary btn-add">
                    <i class="fas fa-plus me-1"></i> Create Report
                </a>
                <a href="{{ route('transaction-monthly-reports.index', ['type' => 'AREQUEST']) }}"
                    class="btn btn-request">
                    <i class="fas fa-paper-plane me-1"></i> Request
                </a>
                <a href="{{ route('transaction-monthly-reports.index', ['type' => 'APPROVAL']) }}"
                    class="btn btn-approval">
                    <i class="fas fa-check-double me-1"></i> Approval
                </a>
                <a href="{{ route('transaction-monthly-reports.index', ['type' => 'APPROVAL_HISTORY']) }}"
                    class="btn btn-appvHistory">
                    <i class="fas fa-history me-1"></i> Approval History
                </a>
                <a href="{{ route('transaction-monthly-reports.index') }}" class="btn btn-monitoring">
                    <i class="fas fa-chart-line me-1"></i> Monitoring
                </a>
                <a href="{{ route('transaction-monthly-reports.excel') }}" class="btn btn-success btn-exp">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-bordered nowrap w-100">
                            <thead>
                                <tr class="text-nowrap bg-light">
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'report_no', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Report No
                                            <i
                                                class="fas {{ request('sort_by') == 'report_no' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'business_field', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Business Field
                                            <i
                                                class="fas {{ request('sort_by') == 'business_field' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'remarks', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Remarks
                                            <i
                                                class="fas {{ request('sort_by') == 'remarks' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'report_date', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Report Date
                                            <i
                                                class="fas {{ request('sort_by') == 'report_date' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                @forelse ($reports as $report)
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('transaction-monthly-reports.show', $report->id) }}"
                                                    class="btn tombol-view btn-sm" title="View">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $report->id }}</td>
                                        <td class="fw-bold">{{ $report->report_no }}</td>
                                        <td>{{ $report->company_name }}</td>
                                        <td>{{ $report->business_field }}</td>
                                        <td>{{ $report->remarks }}</td>
                                        <td>{{ $report->status }}</td>
                                        <td>{{ $report->next_user_name }}</td>
                                        <td>{{ $report->action }}</td>
                                        <td>{{ $report->report_date->format('M Y') }}</td>
                                        <td>{{ $report->updated_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open mb-2" style="font-size: 2rem;"></i>
                                            <p>Belum Ada Laporan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
                        <div class="text-muted small order-2 order-md-1">
                            Showing <strong>{{ $reports->firstItem() ?? 0 }}</strong> to
                            <strong>{{ $reports->lastItem() ?? 0 }}</strong> of
                            <strong>{{ $reports->total() }}</strong> entries
                        </div>

                        <nav class="order-1 order-md-2">
                            <ul class="pagination pagination-sm mb-0 ">
                                {{-- Previous Page Link --}}
                                @if ($reports->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $reports->previousPageUrl() }}"
                                            rel="prev">Previous</a></li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($reports->getUrlRange(max(1, $reports->currentPage() - 2), min($reports->lastPage(), $reports->currentPage() + 2)) as $page => $url)
                                    <li class="page-item {{ $page == $reports->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($reports->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $reports->nextPageUrl() }}"
                                            rel="next">Next</a></li>
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showLoading = (title = 'Processing...') => {
                Swal.fire({
                    title: title,
                    html: 'Please wait a moment.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            };
            const actionButtons = document.querySelectorAll(
                '.btn-add, .btn-approval, .btn-appvHistory, .btn-monitoring, .btn-info, .btn-warning, .btn-exp'
            );
            actionButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (!this.getAttribute('target') || this.getAttribute('target') !== '_blank') {
                        let message = 'Loading Data...';
                        if (this.classList.contains('btn-add')) message = 'Preparing Form...';
                        if (this.classList.contains('btn-exp')) message =
                            'Generating Excel File...';
                        if (this.classList.contains('btn-warning')) message = 'Generating PDF...';
                        showLoading(message);
                    }
                });
            });
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
</x-app-layout>
