<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <a href="{{ route('dashboard') }}" class="btn fs-4 btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Personnel Assignment</h4>
            </div>
            <div class="d-flex flex-wrap justify-content-between gap-2">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('transaction-personnel-assignments.create') }}" class="btn btn-add">
                        <i class="fas fa-plus me-1"></i> Add
                    </a>
                    <button type="button" class="btn btn-search" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    <a href="{{ route('transaction-personnel-assignments.index', ['type' => 'REQUEST']) }}"
                        class="btn btn-request">
                        <i class="fas fa-paper-plane me-1"></i> Request
                    </a>
                    <a href="{{ route('transaction-personnel-assignments.index', ['type' => 'APPROVAL']) }}"
                        class="btn btn-approval">
                        <i class="fas fa-check-double me-1"></i> Approval
                    </a>
                    <a href="{{ route('transaction-personnel-assignments.index', ['type' => 'APPROVAL_HISTORY']) }}"
                        class="btn btn-appvHistory">
                        <i class="fas fa-check-double me-1"></i>Approval History
                    </a>
                    <a href="{{ route('transaction-personnel-assignments.index') }}" class="btn btn-monitoring">
                        <i class="fas fa-chart-line me-1"></i> Monitoring
                    </a>
                    <a href="{{ route('transaction-personnel-assignments.export') }}" class="btn btn-exp">
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
                    <h5 class="modal-title fw-bold" id="searchModalLabel">
                        <i class="fas fa-filter me-2"></i>Filter Personnel Assignment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('transaction-personnel-assignments.index') }}" method="GET">
                    <div class="modal-body">
                        <input type="hidden" name="type" value="{{ request('type') }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Request No</label>
                                <input type="text" name="request_no" class="form-control rounded-pill"
                                    placeholder="Search No..." value="{{ request('request_no') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Request Date</label>
                                <input type="date" name="request_date" class="form-control rounded-pill"
                                    value="{{ request('request_date') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select rounded-pill">
                                    <option value="">-- All Status --</option>
                                    <option value="DRAFT" {{ request('status') == 'DRAFT' ? 'selected' : '' }}>DRAFT
                                    </option>
                                    <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>
                                        REJECTED</option>
                                    <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>
                                        COMPLETED</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Requestor Name</label>
                                <input type="text" name="requestor_name" class="form-control rounded-pill"
                                    placeholder="Name..." value="{{ request('requestor_name') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Company Name</label>
                                <input type="text" name="company_name" class="form-control rounded-pill"
                                    placeholder="Company..." value="{{ request('company_name') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Sub Company Name</label>
                                <input type="text" name="sub_company_name" class="form-control rounded-pill"
                                    placeholder="Sub Company..." value="{{ request('sub_company_name') }}">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <a href="{{ route('transaction-personnel-assignments.index', ['type' => request('type')]) }}"
                            class="btn btn-light rounded-pill px-4">Reset Default</a>

                        <button type="submit" class="btn btn-primary rounded-pill px-4"
                            style="background-color: #B0BC3F; border: none;">
                            <i class="fas fa-search me-1"></i> Apply Filter
                        </button>
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            ID
                                            <i
                                                class="fas {{ request('sort_by') == 'id' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'request_no', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Req. No
                                            <i
                                                class="fas {{ request('sort_by') == 'request_no' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
                                        </a>
                                    </th>
                                    <th>Req. Date</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'company_name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Company
                                            <i
                                                class="fas {{ request('sort_by') == 'company_name' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'requestor_name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-dark d-flex align-items-center justify-content-between text-decoration-none">
                                            Requestor
                                            <i
                                                class="fas {{ request('sort_by') == 'requestor_name' ? (request('sort_order') == 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort text-muted' }} ms-1"></i>
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
                                @forelse ($personnelAssignments as $pa)
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('transaction-personnel-assignments.show', $pa->id) }}"
                                                    class="btn btn-sm tombol-view" title="View">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $pa->id }}</td>
                                        <td>{{ $pa->request_no }}</td>
                                        <td>{{ $pa->request_date }}</td>
                                        <td>
                                            {{ $pa->company_name }}
                                            @if ($pa->sub_company_name)
                                                <br><small class="text-muted">{{ $pa->sub_company_name }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center"> {{ $pa->status }}</td>
                                        <td>{{ $pa->requestor_name }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ Str::limit($pa->remarks, 50) ?? '-' }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <span
                                                    class="{{ $pa->next_action == 'NONE' ? 'text-muted' : 'text-info' }}">
                                                    {{ $pa->next_action }}
                                                </span>
                                                @if ($pa->next_user_name)
                                                    <br><span class="text-muted">({{ $pa->next_user_name }})</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="small">
                                            {{ $pa->updated_at ? $pa->updated_at->format('Y-m-d H:i') : '-' }}
                                            <br><small class="text-muted">by:
                                                {{ $pa->updated_by ?? $pa->last_user_name }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing <strong>{{ $personnelAssignments->firstItem() ?? 0 }}</strong> to
                            <strong>{{ $personnelAssignments->lastItem() ?? 0 }}</strong> of
                            <strong>{{ $personnelAssignments->total() }}</strong> entries
                        </div>
                        <nav>
                            {{ $personnelAssignments->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
