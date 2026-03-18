<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center gap-2 mb-3">
                <a href="{{ route('transaction-asset.index') }}" class="btn btn-back fs-4 btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Asset Detail Information</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary"><i class="fas fa-info-circle me-2"></i>Asset Specifications</h5>
                        <div class="d-flex gap-2">
                            @if($is_able_to_admin_edit)
                            <a href="{{ route('transaction-asset.admin_edit', $asset->id) }}" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm">
                                Admin Edit
                            </a>
                            @endif
                            @if(($asset->approval_status=='APPROVAL_REQUIRED' && $asset->approval_level==1 && $asset->requestor_id==$user->id) || ($asset->approval_status=='REJECTED' && $asset->requestor_id==$user->id))
                            <a href="{{ route('transaction-asset.edit', $asset->id) }}" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm">
                                Edit
                            </a>
                            <form action="{{ route('transaction-asset.destroy', $asset->id) }}" method="POST"
                                class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm btn-delete-confirm">
                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            {{-- Baris Abu-abu untuk header kelompok data --}}
                            <tr class="bg-light">
                                <th colspan="4" class="py-2 px-3 text-muted uppercase small fw-bold">General
                                    Information</th>
                            </tr>
                            <tr>
                                <th class="bg-light w-25 px-3 py-2">Asset Code</th>
                                <td class="w-25 px-3 py-2 text-dark fw-bold">{{ $asset->code }}</td>
                                <th class="bg-light w-25 px-3 py-2">Asset Name</th>
                                <td class="w-25 px-3 py-2 text-dark fw-bold">{{ $asset->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light px-3 py-2">Category</th>
                                <td class="px-3 py-2">{{ $asset->category }}</td>
                                <th class="bg-light px-3 py-2">Asset Type</th>
                                <td class="px-3 py-2">{{ $asset->type }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light px-3 py-2">Register Date</th>
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($asset->register_date)->format('d M Y') }}
                                </td>
                                <th class="bg-light px-3 py-2">Commissioning Date</th>
                                <td class="px-3 py-2">
                                    {{ \Carbon\Carbon::parse($asset->commisioning_date)->format('d M Y') }}</td>
                            </tr>

                            <tr class="bg-light">
                                <th colspan="4" class="py-2 px-3 text-muted uppercase small fw-bold">Ownership &
                                    Location</th>
                            </tr>
                            <tr>
                                <th class="bg-light px-3 py-2">Company</th>
                                <td class="px-3 py-2">{{ $asset->company->name ?? '-' }}</td>
                                <th class="bg-light px-3 py-2">Department</th>
                                <td class="px-3 py-2">{{ $asset->department->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light px-3 py-2">Ownership</th>
                                <td class="px-3 py-2">{{ $asset->ownership }}</td>
                                <th class="bg-light px-3 py-2">Assembly Year</th>
                                <td class="px-3 py-2">{{ $asset->assembly_year }}</td>
                            </tr>

                            <tr class="bg-light">
                                <th colspan="4" class="py-2 px-3 text-muted uppercase small fw-bold">Additional
                                    Details</th>
                            </tr>
                            <tr>
                                <th class="bg-light px-3 py-2 align-top">Specification</th>
                                <td colspan="3" class="px-3 py-2">
                                    <p class="mb-0 text-break" style="white-space: pre-line;">
                                        {{ $asset->specification ?? '-' }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Card Terpisah untuk Lampiran --}}
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-paperclip me-2 text-primary"></i>Attachments</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($asset->attachments as $file)
                            @php
                                $fileNameOnly = basename($file->file_path);
                                $fileUrl = route('storage.external', ['folder' => 'asset', 'filename' => $fileNameOnly]);
                            @endphp

                            <div class="col-md-3 col-sm-6">
                                <div class="border rounded p-2 text-center h-100 shadow-sm">
                                    {{-- Cek tipe file (bisa pakai mime_type atau ekstensi) --}}
                                    @if (Str::contains($file->file_type, 'image'))
                                        <img src="{{ $fileUrl }}" class="img-fluid rounded mb-2"
                                            style="height: 120px; width: 100%; object-fit: cover; cursor: pointer;"
                                            onclick="window.open(this.src)">
                                    @else
                                        <div class="bg-light mb-2 d-flex align-items-center justify-content-center rounded"
                                            style="height: 120px;">
                                            <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                        </div>
                                    @endif

                                    <small class="d-block text-truncate fw-bold" title="{{ $file->file_name }}">
                                        {{ $file->file_name }}
                                    </small>

                                    <a href="{{ $fileUrl }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary mt-2 rounded-pill">
                                        <i class="fas fa-eye me-1"></i> View / Download
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted text-center my-3">No attachments available for this asset.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary"><i class="fas fa-history me-2"></i>Approval Log</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="px-3 border-0" style="width: 25%;">User Name</th>
                                    <th class="px-3 border-0" style="width: 20%;">Status</th>
                                    <th class="px-3 border-0">Remarks</th>
                                    <th class="px-3 border-0 text-center" style="width: 20%;">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Ganti $asset->logs dengan nama relasi log Anda --}}
                                @forelse($asset->logs as $l)
                                    <tr>
                                        <td class="px-3 align-middle text-dark">
                                            {{ $l->user_name }}
                                        </td>
                                        <td class="px-3 align-middle">
                                            {{ $l->status }}
                                        </td>
                                        <td class="px-3 align-middle text-muted">
                                            {{ $l->remarks }}
                                        </td>
                                        <td class="px-3 align-middle text-center small text-muted">
                                            {{ \Carbon\Carbon::parse($l->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">
                                            No approval history found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($asset->next_action=='APPROVAL' && (in_array($user->id, $next_user_ids) || $delegated))
                        <div class="card-footer bg-white py-3 border-top d-flex justify-content-end gap-2">
                            <form action="{{ route('transaction-asset.approve', $asset->id) }}"
                                method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="action" value="APPROVE">
                                <button type="button"
                                    class="btn btn-sm btn-outline-success btn-approve-confirm px-3 rounded-pill">
                                    <i class="fas fa-check me-1"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('transaction-asset.approve', $asset->id) }}"
                                method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="action" value="Reject">
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-reject-confirm px-3 rounded-pill">
                                    <i class="fas fa-times me-1"></i> Reject
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($asset->approval_status=='COMPLETED' && ($asset->requestor_id==$user->id || $is_able_to_admin_edit))
                        <div class="card-footer bg-white py-3 border-top d-flex justify-content-end gap-2">
                            @if($asset->status=='ACTIVE')
                            <form action="{{ route('transaction-asset.update_asset_status', $asset->id) }}"
                                method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="action" value="INACTIVATE">
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-inactivate-confirm px-3 rounded-pill">
                                    <i class="fas fa-times me-1"></i> INACTIVATE
                                </button>
                            </form>
                            @else
                            <form action="{{ route('transaction-asset.update_asset_status', $asset->id) }}"
                                method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="action" value="ACTIVATE">
                                <button type="button"
                                    class="btn btn-sm btn-outline-success btn-activate-confirm px-3 rounded-pill">
                                    <i class="fas fa-check me-1"></i> ACTIVATE
                                </button>
                            </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {

                // 1. Notifikasi Sukses dari Session (Muncul setelah redirect)
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        showConfirmButton: false,
                        timer: 2000
                    });
                @endif

                $('.btn-approve-confirm').on('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Setujui Pengajuan Asset?',
                        text: "Tambahkan catatan jika diperlukan:",
                        icon: 'question',
                        input: 'textarea',
                        inputPlaceholder: 'Tuliskan catatan persetujuan di sini...',
                        inputAttributes: {
                            'aria-label': 'Tuliskan catatan persetujuan di sini'
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Setujui!',
                        customClass: {
                            popup: 'rounded-4'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Buat input hidden secara dinamis untuk mengirim remarks
                            const inputRemarks = document.createElement('input');
                            inputRemarks.type = 'hidden';
                            inputRemarks.name = 'remarks';
                            inputRemarks.value = result.value; // Nilai dari textarea SWAL
                            form.appendChild(inputRemarks);

                            form.submit();
                        }
                    });
                });

                $('.btn-reject-confirm').on('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Tolak Pengajuan Asset?',
                        text: "Berikan alasan penolakan (Wajib):",
                        icon: 'error',
                        input: 'textarea',
                        inputPlaceholder: 'Alasan penolakan...',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Anda harus menuliskan alasan penolakan!'
                            }
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Tolak!',
                        customClass: {
                            popup: 'rounded-4'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const inputRemarks = document.createElement('input');
                            inputRemarks.type = 'hidden';
                            inputRemarks.name = 'remarks';
                            inputRemarks.value = result.value;
                            form.appendChild(inputRemarks);
                            form.submit();
                        }
                    });
                });

                $('.btn-activate-confirm').on('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Setujui pengaktifan Asset?',
                        text: "Tuliskan alasan pengaktifan:",
                        icon: 'question',
                        input: 'textarea',
                        inputPlaceholder: 'Tuliskan alasan pengaktifan di sini...',
                        inputAttributes: {
                            'aria-label': 'Tuliskan alasan pengaktifan di sini'
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Submit',
                        customClass: {
                            popup: 'rounded-4'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Buat input hidden secara dinamis untuk mengirim remarks
                            const inputRemarks = document.createElement('input');
                            inputRemarks.type = 'hidden';
                            inputRemarks.name = 'remarks';
                            inputRemarks.value = result.value; // Nilai dari textarea SWAL
                            form.appendChild(inputRemarks);

                            form.submit();
                        }
                    });
                });

                $('.btn-inactivate-confirm').on('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Nonaktifkan Asset?',
                        text: "Tuliskan alasan penonaktifan:",
                        icon: 'error',
                        input: 'textarea',
                        inputPlaceholder: 'Alasan penonaktifan...',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Anda harus menuliskan alasan penonaktifan!'
                            }
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Submit',
                        customClass: {
                            popup: 'rounded-4'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const inputRemarks = document.createElement('input');
                            inputRemarks.type = 'hidden';
                            inputRemarks.name = 'remarks';
                            inputRemarks.value = result.value;
                            form.appendChild(inputRemarks);
                            form.submit();
                        }
                    });
                });

                $('.btn-delete-confirm').on('click', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('form');
                    Swal.fire({
                        title: 'Hapus Asset?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
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
