<x-app-layout>
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <a href="{{ route('dashboard') }}" class="btn fs-4 btn-back p-0 me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="mb-0 fw-bold">Master Data Department</h4>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('master-department.create') }}" class="btn btn-sm btn-add text-white rounded-pill px-3"
                    style="background-color: #bf9000;">
                    <i class="fas fa-plus me-1"></i> Add
                </a>
                <button type="button" class="btn btn-sm btn-search rounded-pill px-3" data-bs-toggle="modal"
                    data-bs-target="#searchModal">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="searchModalLabel fw-bold">
                        <i class="fas fa-filter me-2"></i>Filter Data Department
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('master-department.index') }}" method="GET">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Department Name</label>
                                <input type="text" name="name" class="form-control rounded-pill"
                                    placeholder="Search department name..." value="{{ request('name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Department Code</label>
                                <input type="text" name="code" class="form-control rounded-pill"
                                    placeholder="Search code..." value="{{ request('code') }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Date Created</label>
                                <input type="date" name="created_at" class="form-control rounded-pill"
                                    value="{{ request('created_at') }}">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <a href="{{ route('master-department.index') }}"
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

    {{-- Table Section --}}
    <div class="container-fluid p-0">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-bordered nowrap w-100">
                        <thead class="bg-light text-nowrap">
                            <tr>
                                <th class="text-center" style="width: 120px;">Action</th>
                                <th class="text-center" style="width: 80px;">ID</th>
                                <th style="width: 150px;">Code</th>
                                <th>Department Name</th>
                                <th style="width: 200px;">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($depart as $dep)
                                <tr>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('master-department.edit', $dep->id) }}"
                                                class="btn btn-sm btn-warning text-white">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if (\App\Helpers\PermissionHelper::hasPermission($menuId, 'delete'))
                                                <form action="{{ route('master-department.destroy', $dep->id) }}"
                                                    method="POST" id="delete-form-{{ $dep->id }}"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete-dept"
                                                        data-id="{{ $dep->id }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>
                                    <td>{{ $dep->id }}</td>
                                    <td>{{ $dep->code }}</td>
                                    <td>{{ $dep->name }}</td>
                                    <td>{{ $dep->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open d-block mb-2 fa-2x"></i>
                                        No department data found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing <strong>{{ $depart->firstItem() ?? 0 }}</strong> to
                        <strong>{{ $depart->lastItem() ?? 0 }}</strong> of
                        <strong>{{ $depart->total() }}</strong> entries
                    </div>

                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Tombol Previous --}}
                            @if ($depart->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link shadow-none">Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link shadow-none" href="{{ $depart->previousPageUrl() }}"
                                        rel="prev">Previous</a>
                                </li>
                            @endif

                            {{-- Tombol Angka Halaman --}}
                            @foreach ($depart->getUrlRange(max(1, $depart->currentPage() - 2), min($depart->lastPage(), $depart->currentPage() + 2)) as $page => $url)
                                <li class="page-item {{ $page == $depart->currentPage() ? 'active' : '' }}">
                                    <a class="page-link shadow-none"
                                        href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            {{-- Tombol Next --}}
                            @if ($depart->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link shadow-none" href="{{ $depart->nextPageUrl() }}"
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

    @push('scripts')
        {{-- Script SweetAlert dan Delete tetap sama --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        timer: 2500,
                        showConfirmButton: false
                    });
                @endif

                $(document).on('click', '.btn-delete-dept', function(e) {
                    let id = $(this).data('id');
                    let form = $('#delete-form-' + id);
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Data yang dihapus tidak bisa dikembalikan!",
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
            });
        </script>
    @endpush
</x-app-layout>
