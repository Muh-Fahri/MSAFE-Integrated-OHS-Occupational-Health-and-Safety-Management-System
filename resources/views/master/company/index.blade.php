<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <a href="{{ route('dashboard') }}" class="btn fs-4 btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Master Data Company</h4>
            </div>
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('master-company.create') }}" class="btn btn-sm btn-add">
                    <i class="fas fa-plus me-1"></i> Add
                </a>
                <button type="button" class="btn btn-sm btn-search" data-bs-toggle="modal"
                    data-bs-target="#searchModal">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>
            <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="border-radius: 15px;">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="searchModalLabel fw-bold"><i
                                    class="fas fa-filter me-2"></i>Filter Data
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('master-company.index') }}" method="GET">
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold">Company Name</label>
                                        <input type="text" name="name" class="form-control rounded-pill"
                                            placeholder="Search company name..." value="{{ request('name') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Parent Company</label>
                                        <select name="parent_id" class="form-select rounded-pill">
                                            <option value="">-- All Parent --</option>
                                            @foreach ($allCompanies as $parent)
                                                <option value="{{ $parent->id }}"
                                                    {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                                    {{ $parent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Date Created</label>
                                        <input type="date" name="created_at" class="form-control rounded-pill"
                                            value="{{ request('created_at') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer border-0">
                                <a href="{{ route('master-company.index') }}"
                                    class="btn btn-light rounded-pill px-4">Reset</a>
                                <button type="submit" class="btn btn-warning rounded-pill px-4 text-white"
                                    style="background-color: #bf9000; border-color: #bf9000;">
                                    Apply Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-bordered nowrap w-100">
                            <thead>
                                <tr class="bg-light text-nowrap">
                                    <th class="text-center" style="width: 80px;">Action</th>
                                    <th style="width: 100px;">ID</th>
                                    <th>Name</th>
                                    <th>Parent Company</th>
                                    <th>HSE Manager</th>
                                    <th>PJO</th>
                                    <th>Permit No</th>
                                    <th>Industry</th>
                                    <th>Permit Start Date</th>
                                    <th>Permit End Date</th>
                                    <th>Created At</th> {{-- Sesuai dengan isi Create --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($comp as $c)
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('master-company.edit', $c->id) }}"
                                                    class="btn btn-sm btn-warning text-white" title="Edit">
                                                    <i class="fas fa-edit small"></i>
                                                </a>

                                                {{-- Tombol Delete dengan Permission & Swal --}}
                                                @php $menuId = \App\Helpers\PermissionHelper::getCurrentMenuId(); @endphp
                                                @if (\App\Helpers\PermissionHelper::hasPermission($menuId, 'delete'))
                                                    <form action="{{ route('master-company.destroy', $c->id) }}"
                                                        method="POST" id="delete-form-{{ $c->id }}"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                            data-id="{{ $c->id }}" title="Delete">
                                                            <i class="fas fa-trash small"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $c->id }}</td>
                                        <td>{{ $c->name }}</td>
                                        <td>{{ $c->parent->name ?? '-' }}</td>
                                        <td>{{ $c->hseManager->name ?? '-' }}</td>
                                        <td>{{ $c->pjo->name ?? '-' }}</td>
                                        <td>{{ $c->permit_no ?? '-' }}</td>
                                        <td>{{ $c->industry ?? '-' }}</td>
                                        <td>{{ $c->permit_start_date ? \Carbon\Carbon::parse($c->permit_start_date)->format('d M Y') : '-' }}
                                        </td>
                                        <td>{{ $c->permit_end_date ? \Carbon\Carbon::parse($c->permit_end_date)->format('d M Y') : '-' }}
                                        </td>
                                        <td class="small text-muted">{{ $c->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open d-block mb-2 fa-2x"></i>
                                            No data available in this table
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing <strong>{{ $comp->firstItem() ?? 0 }}</strong> to
                            <strong>{{ $comp->lastItem() ?? 0 }}</strong> of
                            <strong>{{ $comp->total() }}</strong> entries
                        </div>

                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Tombol Previous --}}
                                @if ($comp->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link shadow-none">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link shadow-none" href="{{ $comp->previousPageUrl() }}"
                                            rel="prev">Previous</a>
                                    </li>
                                @endif

                                {{-- Tombol Angka Halaman --}}
                                @foreach ($comp->getUrlRange(max(1, $comp->currentPage() - 2), min($comp->lastPage(), $comp->currentPage() + 2)) as $page => $url)
                                    <li class="page-item {{ $page == $comp->currentPage() ? 'active' : '' }}">
                                        <a class="page-link shadow-none"
                                            href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                {{-- Tombol Next --}}
                                @if ($comp->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link shadow-none" href="{{ $comp->nextPageUrl() }}"
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
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let form = $('#delete-form-' + id);

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data perusahaan yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#bf9000',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        </script>
    @endpush

    {{-- Style tambahan untuk tombol view agar mirip dengan desain sebelumnya --}}
    <style>
        .table thead th {
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #333;
        }

        .btn-sm {
            padding: 0.25rem 0.6rem;
            font-size: 0.75rem;
        }
    </style>
</x-app-layout>
