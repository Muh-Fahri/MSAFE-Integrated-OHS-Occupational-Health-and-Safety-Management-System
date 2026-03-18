<x-app-layout>
    <div class="container-fluid"> {{-- Pakai container-fluid agar lebar --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('transaction-license.index') }}"
                            class="btn btn-outline-secondary btn-sm rounded-circle me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h4 class="mb-0 text-dark fw-bold">Detail Pengajuan License #{{ $data->no }}</h4>
                    </div>
                    <div>
                        <span
                            class="badge bg-{{ $data->status == 'COMPLETED' ? 'success' : 'secondary' }} text-uppercase px-3 py-2"
                            style="letter-spacing: 1px;">
                            {{ $data->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                {{-- Tanpa Shadow, Border Tipis, Menyatu dengan background --}}
                <div class="card border-0 bg-transparent">
                    <div class="card-body p-0"> {{-- Hilangkan padding card utama agar menyatu --}}

                        <div class="bg-white p-4 border border-light"> {{-- Kotak isi dokumen --}}

                            {{-- HEADER DOKUMEN --}}
                            <div class="row mb-5 pb-3 border-bottom border-2 border-dark">
                                <div class="col-md-6">
                                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Tipe Lisensi</h6>
                                    <h3 class="fw-bold text-dark">{{ $data->type }}</h3>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Tanggal Pengajuan</h6>
                                    <h5 class="fw-bold text-dark">
                                        {{ \Carbon\Carbon::parse($data->date)->format('d F Y') }}</h5>
                                </div>
                            </div>

                            <div class="row">
                                {{-- KOLOM KIRI --}}
                                <div class="col-md-6 pe-md-5">
                                    <div class="mb-4">
                                        <label class="text-muted fw-bold small text-uppercase d-block mb-2">Data
                                            Karyawan</label>
                                        <table class="table table-sm table-borderless m-0">
                                            <tr>
                                                <td width="150" class="text-muted py-2">ID Karyawan</td>
                                                <td class="fw-bold py-2">: {{ $data->employee_id }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted py-2">Nama Lengkap</td>
                                                <td class="fw-bold py-2">: {{ $data->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted py-2">Jabatan</td>
                                                <td class="fw-bold py-2">: {{ $data->position }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                {{-- KOLOM KANAN --}}
                                <div class="col-md-6 ps-md-5 border-start">
                                    <div class="mb-4">
                                        <label
                                            class="text-muted fw-bold small text-uppercase d-block mb-2">Penempatan</label>
                                        <table class="table table-sm table-borderless m-0">
                                            <tr>
                                                <td width="150" class="text-muted py-2">Department</td>
                                                <td class="fw-bold py-2">: {{ $data->department_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted py-2">Perusahaan</td>
                                                <td class="fw-bold py-2">: {{ $data->company_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted py-2">Masa Berlaku SIM</td>
                                                <td class="fw-bold py-2">:
                                                    {{ $data->driving_license_expiry_date ? \Carbon\Carbon::parse($data->driving_license_expiry_date)->format('d/m/Y') : '-' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                {{-- FULL WIDTH REASON --}}
                                <div class="col-12 mt-4">
                                    <div class="p-3 bg-light border-start border-4 border-secondary">
                                        <label class="text-muted fw-bold small text-uppercase d-block mb-1">Alasan
                                            Pengajuan:</label>
                                        <p class="mb-0 fw-semibold text-dark">{{ $data->reason ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- DETAIL ITEMS TABLE --}}
                            <div class="mt-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold text-uppercase m-0">Daftar Unit / Peralatan</h6>
                                    <small class="text-muted">Total: {{ $data->licenseItems->count() }} Item</small>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered border-secondary">
                                        <thead class="table-light">
                                            <tr class="text-center">
                                                <th width="60">NO</th>
                                                <th width="200">KODE</th>
                                                <th>NAMA UNIT / PERALATAN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($data->licenseItems as $index => $item)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td class="text-center fw-bold">{{ $item->code }}</td>
                                                    <td>{{ $item->name }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-muted">Data unit
                                                        tidak ditemukan.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if ($data->type == 'KIMPER')
                                <div class="mt-5">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold text-uppercase m-0">Zonasi Operasional (KIMPER)</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered border-secondary">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th width="60">STATUS</th>
                                                    <th width="200">ZONA</th>
                                                    <th>CAKUPAN AREA / KETERANGAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- Kita asumsikan relasi di model adalah licenseZones --}}
                                                @php
                                                    // Ambil kode zona yang sudah dipilih untuk memudahkan pengecekan
                                                    $selectedZones = $data->licenseZones->pluck('code')->toArray();
                                                @endphp

                                                {{-- Row Red Zone --}}
                                                <tr
                                                    class="{{ in_array('RED', $selectedZones) ? '' : 'text-muted opacity-50' }}">
                                                    <td class="text-center align-middle">
                                                        @if (in_array('RED', $selectedZones))
                                                            <i class="fas fa-check-circle text-danger h4 mb-0"></i>
                                                        @else
                                                            <i class="far fa-circle text-secondary h4 mb-0"></i>
                                                        @endif
                                                    </td>
                                                    <td class="bg-danger text-white text-center align-middle fw-bold">
                                                        RED ZONE
                                                    </td>
                                                    <td class="p-3">
                                                        <strong>Site Road Access</strong> (Area pembersihan lahan, area
                                                        aktif konstruksi, akses jalan Site)<br>
                                                        <strong>Mining</strong> – Pit, Waste Dump, Hauling Road,
                                                        Clearing,
                                                        Sediment Pond
                                                    </td>
                                                </tr>

                                                {{-- Row Yellow Zone --}}
                                                <tr
                                                    class="{{ in_array('YELLOW', $selectedZones) ? '' : 'text-muted opacity-50' }}">
                                                    <td class="text-center align-middle">
                                                        @if (in_array('YELLOW', $selectedZones))
                                                            <i class="fas fa-check-circle text-warning h4 mb-0"></i>
                                                        @else
                                                            <i class="far fa-circle text-secondary h4 mb-0"></i>
                                                        @endif
                                                    </td>
                                                    <td class="bg-warning text-dark text-center align-middle fw-bold">
                                                        YELLOW ZONE
                                                    </td>
                                                    <td class="p-3 align-middle">
                                                        <strong>TSF</strong> (Tailings Storage Facility)
                                                    </td>
                                                </tr>

                                                {{-- Row Green Zone --}}
                                                <tr
                                                    class="{{ in_array('GREEN', $selectedZones) ? '' : 'text-muted opacity-50' }}">
                                                    <td class="text-center align-middle">
                                                        @if (in_array('GREEN', $selectedZones))
                                                            <i class="fas fa-check-circle text-success h4 mb-0"></i>
                                                        @else
                                                            <i class="far fa-circle text-secondary h4 mb-0"></i>
                                                        @endif
                                                    </td>
                                                    <td class="bg-success text-white text-center align-middle fw-bold">
                                                        GREEN ZONE
                                                    </td>
                                                    <td class="p-3">
                                                        <strong>Public Road Access</strong> (Akses jalan dari luar
                                                        kontrak
                                                        karya sampai dengan batas Site Road Access)<br>
                                                        <strong>Road Access</strong> (Site & Public Road)
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- SECTION LOG AKTIVITAS --}}
                            <div class="mt-5">
                                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                    <h6 class="fw-bold text-uppercase m-0 text-secondary"><i
                                            class="fas fa-history me-2"></i> Riwayat Aktivitas Dokumen</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered border-secondary">
                                        <thead class="table-light text-center">
                                            <tr class="small text-uppercase">
                                                <th width="180">Tanggal & Waktu</th>
                                                <th width="150">User</th>
                                                <th width="150">Status</th>
                                                <th>Catatan / Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            {{-- Asumsi relasi di model adalah 'logs' atau 'histories' --}}
                                            {{-- Jika nama relasinya berbeda, silakan sesuaikan --}}
                                            @forelse($logs ?? [] as $log)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}
                                                    </td>
                                                    <td class="fw-bold text-dark">
                                                        {{ $log->user_name ?? $log->user->name }}</td>
                                                    <td class="text-center">
                                                        {{ $log->status }}
                                                    </td>
                                                    <td class="text-muted">{{ $log->remarks ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-3 text-muted">Belum ada
                                                        riwayat aktivitas untuk dokumen ini.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- SECTION LAMPIRAN PENDUKUNG --}}
                            <div class="mt-5 pb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                    <h6 class="fw-bold text-uppercase m-0">Lampiran Pendukung</h6>
                                    <small class="text-muted">Total: {{ $data->attachments->count() }} Dokumen</small>
                                </div>

                                <div class="row">
                                    {{-- Foto Diri (Asumsi dokumen pertama adalah foto diri sesuai instruksi simpan) --}}
                                    @php
                                        $firstDoc = $data->attachments->first();
                                        // Pastikan pengecekan tipe file aman dari case-sensitive
                                        $fileType = strtolower($firstDoc->file_type ?? '');
                                        $isImage =
                                            $firstDoc &&
                                            in_array($fileType, ['jpg', 'jpeg', 'png', 'image/jpeg', 'image/png']);
                                    @endphp
                                    <div class="col-md-3 mb-4 text-center">
                                        <div class="p-2 border border-secondary rounded-3 bg-light">
                                            <label class="d-block text-muted small fw-bold mb-2">FOTO IDENTITAS</label>
                                            @if ($isImage)
                                                <img src="{{ route('storage.external', ['folder' => 'license', 'filename' => basename($firstDoc->file_path)]) }}"
                                                    class="img-fluid rounded shadow-sm border"
                                                    style="max-height: 250px; width: 100%; object-fit: cover;"
                                                    alt="Foto Diri">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-secondary text-white rounded"
                                                    style="height: 200px;">
                                                    <i class="fas fa-user-circle fa-5x"></i>
                                                </div>
                                            @endif
                                            <div class="mt-2">
                                                <small
                                                    class="fw-bold text-dark d-block">{{ $firstDoc->name ?? 'Tidak ada foto' }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Daftar Dokumen Lainnya --}}
                                    <div class="col-md-9">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover border-secondary">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="50" class="text-center">NO</th>
                                                        <th>NAMA DOKUMEN</th>
                                                        <th width="100" class="text-center">TIPE</th>
                                                        <th width="150" class="text-center">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($data->attachments as $index => $file)
                                                        <tr>
                                                            <td class="text-center align-middle">{{ $index + 1 }}
                                                            </td>
                                                            <td class="align-middle fw-semibold text-dark">
                                                                {{ $file->name==''? $file->file_name : $file->name }}
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <span
                                                                    class="badge bg-outline-secondary border text-secondary text-uppercase">
                                                                    {{ $file->file_type }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <div
                                                                    class="btn-group border rounded-pill overflow-hidden">
                                                                    <a href="{{ route('storage.external', ['folder' => 'license', 'filename' => basename($file->file_path)]) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-light border-end">
                                                                        <i class="fas fa-eye text-primary"></i>
                                                                    </a>
                                                                    <a href="{{ route('storage.external', ['folder' => 'license', 'filename' => basename($file->file_path)]) }}"
                                                                        download class="btn btn-sm btn-light">
                                                                        <i class="fas fa-download text-success"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4"
                                                                class="text-center py-4 text-muted italic">Belum ada
                                                                lampiran yang diunggah.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-2 p-2 bg-light border-start border-3 border-info">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i> Klik ikon mata untuk melihat
                                                dokumen secara langsung atau ikon download untuk menyimpan file.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> {{-- End White Section --}}

                        {{-- ACTION BUTTONS (DI LUAR KOTAK PUTIH) --}}
                        <div class="mt-4 d-flex justify-content-end gap-2 pb-2 pl-3 pr-3"> {{-- Tambahkan padding agar tidak terlalu mepet dengan tepi --}}
                            {{-- TOMBOL DELETE & EDIT (REQUESTOR ONLY) --}}
                            @if($is_able_to_admin_edit)
                                <a href="{{ route('transaction-license.admin_edit', $data->id) }}"
                                    class="btn btn-outline-primary px-4 mx-1 shadow-none">
                                    <i class="fas fa-cog me-2"></i> ADMIN EDIT
                                </a>
                            @endif
                            @if(($data->status=='APPROVAL_REQUIRED' && $data->approval_level==1 && $data->requestor_id==$user->id) || ($data->status=='REJECTED' && $data->requestor_id==$user->id))
                                <a href="{{ route('transaction-license.edit', $data->id) }}"
                                    class="btn btn-outline-dark px-4 mx-1 shadow-none">
                                    <i class="fas fa-edit me-2"></i> EDIT
                                </a>
                            @endif
                            
                            @if ($is_able_to_admin_delete)
                                <form id="delete-form-{{ $data->id }}"
                                    action="{{ route('transaction-license.destroy', $data->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteDetail()"
                                        class="btn btn-outline-danger px-4 shadow-none">
                                        <i class="fas fa-trash-alt me-2"></i> Hapus
                                    </button>
                                </form>
                            @endif
                            
                            @if($is_able_to_view_pdf)
                                <a href="{{ route('transaction-license.view_pdf', $data->id) }}" 
                                    type="button" class="btn btn-outline-warning px-4 mx-1 shadow-none" 
                                    target="_BLANK">PDF</a>
                            @endif

                            @if($data->status=='APPROVAL_REQUIRED' && (in_array($user->id, $next_user_ids) || $delegated))
                                <a href="javascript:void(0)" 
                                    class="btn btn-outline-danger px-4 mx-1 shadow-none btn-action" 
                                    data-status="{{ $data->status }}" 
                                    next-action="{{ $data->next_action }}" 
                                    data-action="REJECT">REJECT</a> 
                                <a href="javascript:void(0)" 
                                    class="btn btn-outline-success px-4 mx-1 shadow-none btn-action" 
                                    data-status="{{ $data->status }}" 
                                    next-action="{{ $data->next_action }}" 
                                    data-action="APPROVE">APPROVE</a> 
                            @endif
                        </div>
                    </div>


                    {{-- SECTION ZONASI (Hanya tampil jika KIMPER) --}}


                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="win-approval" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Approval</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('transaction-license.approve', $data->id) }}" method="POST"
                    enctype="multipart/form-data" id="form-approval">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <input type="hidden" name="action" value="" id="js-action">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="Remarks ..."></textarea>
                        </div>

                        @if($data->next_action=='APPROVAL_AND_ASSIGNMENT')
                            <div class="mb-3 d-flex flex-column">
                                <label for="theory_tester_id" class="form-label fw-bold mb-1">Theory Tester:</label>
                                <select class="form-control select2 @error('theory_tester_id') is-invalid @enderror"
                                    id="theory_tester_id" name="theory_tester_id" style="width: 100%;">
                                    <option value="">-</option>
                                    @foreach ($list_user as $user_id => $user_name)
                                        <option value="{{ $user_id }}"
                                            {{ old('theory_tester_id') == $user_id ? 'selected' : '' }}>
                                            {{ $user_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('theory_tester_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 d-flex flex-column">
                                <label for="practice_tester_id" class="form-label fw-bold mb-1">Practice Tester:</label>
                                <select class="form-control select2 @error('practice_tester_id') is-invalid @enderror"
                                    id="practice_tester_id" name="practice_tester_id" style="width: 100%;">
                                    <option value="">-</option>
                                    @foreach ($list_user as $user_id => $user_name)
                                        <option value="{{ $user_id }}"
                                            {{ old('practice_tester_id') == $user_id ? 'selected' : '' }}>
                                            {{ $user_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('practice_tester_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if($data->type=='KIMPER')
                                <div class="mb-3 d-flex flex-column">
                                    <label for="first_aid_trainer_id" class="form-label fw-bold mb-1">First Aid Trainer:</label>
                                    <select class="form-control select2 @error('first_aid_trainer_id') is-invalid @enderror"
                                        id="first_aid_trainer_id" name="first_aid_trainer_id" style="width: 100%;">
                                        <option value="">-</option>
                                        @foreach ($list_user as $user_id => $user_name)
                                            <option value="{{ $user_id }}"
                                                {{ old('first_aid_trainer_id') == $user_id ? 'selected' : '' }}>
                                                {{ $user_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('first_aid_trainer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 d-flex flex-column">
                                    <label for="ddc_trainer_id" class="form-label fw-bold mb-1">DDC Trainer:</label>
                                    <select class="form-control select2 @error('ddc_trainer_id') is-invalid @enderror"
                                        id="ddc_trainer_id" name="ddc_trainer_id" style="width: 100%;">
                                        <option value="">-</option>
                                        @foreach ($list_user as $user_id => $user_name)
                                            <option value="{{ $user_id }}"
                                                {{ old('ddc_trainer_id') == $user_id ? 'selected' : '' }}>
                                                {{ $user_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ddc_trainer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Attachment (Max 2Mb)</label>
                            <input type="file" name="file_1" class="form-control mb-2" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                $('#theory_tester_id, #practice_tester_id, #first_aid_trainer_id, #ddc_trainer_id').select2({
                    dropdownParent: $('#win-approval')
                });
                $('.btn-action').on('click', function(){
                    var status = $(this).data('status');
                    var action = $(this).data('action');
                    $('#js-action').val(action);
                    $('#win-approval').modal('show');
                });
                $("#form-approval").submit(function () {
                    $(".btn-save").attr("disabled", true);
                    return true;
                });
            });
            function confirmDeleteDetail() {
                Swal.fire({
                    title: 'Hapus Data?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6e7d88',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jalankan submit form
                        document.getElementById('delete-form-{{ $data->id }}').submit();
                    }
                })
            }

            // Notifikasi Sukses setelah redirect (beserta Audio Ping)
            @if (session('success'))
                // Audio Glass Ping Santai
                var audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2355/2355-preview.mp3');
                audio.volume = 0.4;
                audio.play().catch(e => console.log("Audio play blocked"));

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 2500,
                    showConfirmButton: false,
                    showClass: {
                        popup: 'animate__animated animate__zoomIn'
                    }
                });
            @endif
        </script>
    @endpush
</x-app-layout>
