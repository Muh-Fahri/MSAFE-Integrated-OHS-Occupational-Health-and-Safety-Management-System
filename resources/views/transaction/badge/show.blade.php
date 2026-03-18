<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('transaction-badge.index') }}" class="btn btn-back fs-4 btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                    <h4 class="mb-0">Badge Request Details</h4>
                </div>
                <div>
                    <span class="badge bg-soft-primary text-primary px-3 py-2 fs-6">
                        {{ $data->request_no }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 border-end">
                            <h6 class="fw-bold text-uppercase small text-muted mb-3">I. General Information</h6>
                            <table class="table table-sm table-borderless mt-2">
                                <tr>
                                    <td width="35%" class="text-muted">Requestor</td>
                                    <td class="fw-bold">: {{ $data->requestor_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Company</td>
                                    <td>: {{ $data->company->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Sub Company</td>
                                    <td>: {{ $data->sub_company_id == 1 ? 'Sub Unit A' : 'Sub Unit B' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <h6 class="fw-bold text-uppercase small text-muted mb-3">&nbsp;</h6>
                            <table class="table table-sm table-borderless mt-2">
                                <tr>
                                    <td width="35%" class="text-muted">Request Date</td>
                                    <td>: {{ \Carbon\Carbon::parse($data->request_date)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Location</td>
                                    <td>: <span class="badge bg-light text-dark border">{{ $data->location }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <h6 class="fw-bold text-uppercase small text-muted">II. Personnel Details</h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="bg-light small text-uppercase fw-bold">
                                <tr>
                                    <th class="ps-3">No</th>
                                    <th>Employee ID</th>
                                    <th>Citizen ID (NIK)</th>
                                    <th>Full Name</th>
                                    <th>Position</th>
                                    <th>Status</th>
                                    <th class="text-center">Period</th>
                                    <th class="text-center" width="250">Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->lines as $index => $line)
                                    <tr>
                                        <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                                        <td class="fw-medium">{{ $line->employee_id }}</td>
                                        <td>{{ $line->citizen_id }}</td>
                                        <td class="fw-bold text-primary">{{ $line->name }}</td>
                                        <td>{{ $line->title }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $line->status == 'Active' ? 'bg-success' : 'bg-danger' }} rounded-pill"
                                                style="font-size: 10px;">
                                                {{ $line->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $line->active_period }} Months</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                @php
                                                    $docs = [
                                                        ['path' => $line->file_path_photo, 'label' => 'Photo'],
                                                        ['path' => $line->file_path_ktp, 'label' => 'KTP'],
                                                        ['path' => $line->file_path_ftw, 'label' => 'FTW'],
                                                        ['path' => $line->file_path_induksi, 'label' => 'Induction'],
                                                    ];
                                                @endphp
                                                @foreach ($docs as $doc)
                                                    @if ($doc['path'])
                                                        @php
                                                            $filename = $doc['path'];
                                                        @endphp
                                                        <a href="{{ route('storage.external', ['folder' => 'badge_request', 'filename' => basename($filename)]) }}"
                                                            target="_blank" class="btn-pdf-view"
                                                            title="View {{ $doc['label'] }}">
                                                            <i class="fas fa-file-pdf"></i>
                                                            <span>{{ $doc['label'] }}</span>
                                                        </a>
                                                    @else
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <p class="text-muted small mb-0">Requested at: {{ $data->created_at->format('d/m/Y H:i') }}</p>

                        <div class="d-flex align-items-center gap-2">
                            {{-- Tombol Back --}}
                            <a href="{{ route('transaction-badge.index') }}" class="btn btn-secondary px-3">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <div class="vr mx-2" style="height: 30px;"></div>

                            {{-- Kondisional Tombol APPROVAL --}}
                            @if($data->status=='APPROVAL_REQUIRED' && (in_array($user->id, $next_user_ids) || $delegated))
                                <form action="{{ route('transaction-badge.approve', $data->id) }}"
                                    method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="action" value="REJECT">
                                    <button type="button"
                                        class="btn btn-danger text-white px-3 btn-reject-swal">
                                        <i class="fas fa-times me-1"></i> REJECT
                                    </button>
                                </form>
                                <form action="{{ route('transaction-badge.approve', $data->id) }}" 
                                    method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="action" value="APPROVE">
                                    <button type="button"
                                        class="btn btn-success text-white px-3 btn-approve-swal">
                                        <i class="fas fa-check me-1"></i> APPROVE
                                    </button>
                                </form>
						    @endif

                            @if($data->status=='WAITING_TO_PRINT' && (in_array($user->id, $next_user_ids) || $delegated))
                                <form action="{{ route('transaction-badge.approve', $data->id) }}" 
                                    method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="action" value="PRINT">
                                    <button type="button"
                                        class="btn btn-success text-white px-3 btn-print-swal">
                                        <i class="fas fa-print me-1"></i> PRINT
                                    </button>
                                </form>
                            @endif

                            {{-- Tombol EDIT --}}
                            @if(($data->status=='APPROVAL_REQUIRED' && $data->approval_level==1 && $data->requestor_id==$user->id) || ($data->status=='REJECTED' && $data->requestor_id==$user->id))
                            <a href="{{ route('transaction-badge.edit', $data->id) }}"
                                class="btn btn-warning text-white px-3">
                                <i class="fas fa-pen me-1"></i> Edit
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.btn-approve-swal').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                Swal.fire({
                    title: 'Setujui Laporan?',
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
                        const inputRemarks = document.createElement('input');
                        inputRemarks.type = 'hidden';
                        inputRemarks.name = 'remarks';
                        inputRemarks.value = result.value;
                        form.append(inputRemarks);

                        form.submit();
                    }
                });
            });
            $('.btn-reject-swal').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                Swal.fire({
                    title: 'Tolak Laporan?',
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
                        form.append(inputRemarks);
                        form.submit();
                    }
                });
            });
            $('.btn-print-swal').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                Swal.fire({
                    title: 'Cetak Laporan?',
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
                    confirmButtonText: 'Ya, Cetak!',
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
                        form.append(inputRemarks);

                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush

<style>
    /* UI PDF Warna Abu-abu sesuai permintaan */
    .btn-pdf-view {
        background-color: #f0f0f0;
        border: 1px solid #dcdcdc;
        color: #555;
        padding: 4px 8px;
        border-radius: 4px;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 55px;
        transition: all 0.2s ease;
    }

    .btn-pdf-view i {
        font-size: 16px;
        margin-bottom: 2px;
        color: #777;
    }

    .btn-pdf-view span {
        font-size: 9px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .btn-pdf-view:hover {
        background-color: #e2e2e2;
        color: #333;
        border-color: #bcbcbc;
        transform: translateY(-2px);
    }

    /* Style untuk dokumen yang kosong */
    .btn-pdf-disabled {
        background-color: #fafafa;
        border: 1px dashed #e0e0e0;
        color: #ccc;
        padding: 4px 8px;
        border-radius: 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 55px;
        cursor: not-allowed;
    }

    .btn-pdf-disabled i {
        font-size: 14px;
        margin-bottom: 2px;
    }

    .btn-pdf-disabled span {
        font-size: 9px;
        text-transform: uppercase;
    }

    /* Print styling */
    @media print {

        .btn-back,
        .btn-pdf-view,
        .btn-secondary,
        .btn-outline-dark,
        .btn-add {
            display: none !important;
        }
    }
</style>
</x-app-layout>
