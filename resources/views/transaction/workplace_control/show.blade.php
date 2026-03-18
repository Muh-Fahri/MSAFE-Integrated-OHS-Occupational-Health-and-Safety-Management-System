<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <div class="page-title-right">
                    <a href="{{ route('transaction-workPlace.index') }}" class="btn fs-4 btn-back">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                </div>
                <h4 class="mb-0">Work Place Control</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card rounded-0 border-0 shadow-sm w-100">
                <div class="card-header bg-secondary text-white rounded-0 py-3">
                    <h5 class="mb-0 text-uppercase">Laporan Inspeksi: {{ str_replace('_', ' ', $type) }}</h5>
                </div>

                <div class="card-body p-4 bg-light">
                    <div class="container-fluid bg-white p-0 border shadow-sm">
                        <div class="row g-0">

                            {{-- 1. Tipe Kendaraan --}}
                            @if ($type === 'INSPECTION_VEHICLE')
                                <div class="col-md-6 border-end">
                                    <div class="d-flex border-bottom">
                                        <div class="col-5 bg-light p-3 fw-bold border-end text-muted">Driver / Operator
                                        </div>
                                        <div class="col-7 p-3 text-dark">{{ $data->operator_name ?? '-' }}</div>
                                    </div>
                                    <div class="d-flex border-bottom">
                                        <div class="col-5 bg-light p-3 fw-bold border-end text-muted">Dept / Perusahaan
                                        </div>
                                        <div class="col-7 p-3 text-dark">{{ $data->department->name ?? '-' }}</div>
                                    </div>
                                    <div class="d-flex border-bottom border-md-bottom-0">
                                        <div class="col-5 bg-light p-3 fw-bold border-end text-muted">Lokasi Pemeriksaan
                                        </div>
                                        <div class="col-7 p-3 text-dark">{{ $data->location ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex border-bottom">
                                        <div class="col-5 bg-light p-3 fw-bold border-end text-muted">Tanggal</div>
                                        <div class="col-7 p-3 text-dark">
                                            {{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}</div>
                                    </div>
                                    <div class="d-flex border-bottom">
                                        <div class="col-5 bg-light p-3 fw-bold border-end text-muted">ID Kendaraan</div>
                                        <div class="col-7 p-3 text-dark">{{ $data->vehicle_code ?? '-' }}</div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="col-5 bg-light p-3 fw-bold border-end text-muted">Jenis Kendaraan
                                        </div>
                                        <div class="col-7 p-3 text-dark">{{ $data->vehicle_type ?? '-' }}</div>
                                    </div>
                                </div>

                                {{-- 2. Tipe Area Spesifik (Road, Drilling, Construction, Dump, Loading) --}}
                            @elseif (in_array($type, [
                                    'INSPECTION_ROAD',
                                    'INSPECTION_DRILLING_AREA',
                                    'INSPECTION_CONSTRUCTION_AREA',
                                    'INSPECTION_DUMP_POINT_AREA',
                                    'INSPECTION_LOADING_POINT_AREA',
                                ]))
                                <div class="col-md-6 border-end border-bottom">
                                    <div class="d-flex h-100">
                                        <div class="col-5 bg-light p-3 fw-bold border-end text-muted">Area Inspeksi
                                        </div>
                                        <div class="col-7 p-3 text-dark">{{ $data->location }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 border-bottom">
                                    <div class="d-flex h-100">
                                        <div class="col-5 bg-light p-3 fw-bold border-end text-muted">Tanggal</div>
                                        <div class="col-7 p-3 text-dark">
                                            {{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}</div>
                                    </div>
                                </div>
                                <div class="col-12 text-wrap">
                                    <div class="d-flex">
                                        <div class="col-3 bg-light p-3 fw-bold border-end text-muted">Shift</div>
                                        <div class="col-9 p-3 text-dark">{{ $data->shift ?? '-' }}</div>
                                    </div>
                                </div>

                                {{-- 3. Tipe PTO --}}
                            @elseif ($type == 'PLANNED_TASK_OBSERVATION')
                                <div class="col-md-6 border-end border-bottom">
                                    <div class="d-flex h-100">
                                        <div class="col-5 bg-light p-3 fw-bold border-end text-muted">Area Kerja</div>
                                        <div class="col-7 p-3 text-dark">{{ $data->location }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 border-bottom">
                                    <div class="d-flex h-100">
                                        <div class="col-5 bg-light p-3 fw-bold border-end text-muted">Tanggal Observasi
                                        </div>
                                        <div class="col-7 p-3 text-dark">
                                            {{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}</div>
                                    </div>
                                </div>
                                <div class="col-12 border-bottom text-wrap">
                                    <div class="d-flex">
                                        <div class="col-3 bg-light p-3 fw-bold border-end text-muted">Aktivitas</div>
                                        <div class="col-9 p-3 text-break text-dark">{{ $data->activity }}</div>
                                    </div>
                                </div>
                                <div class="col-12 text-wrap">
                                    <div class="d-flex">
                                        <div class="col-3 bg-light p-3 fw-bold border-end text-muted">Prosedur / JSA
                                        </div>
                                        <div class="col-9 p-3 text-break text-dark">{{ $data->procedure }}</div>
                                    </div>
                                </div>

                                {{-- 4. Default / Lainnya --}}
                            @else
                                <div class="col-12 border-bottom">
                                    <div class="d-flex">
                                        <div class="col-3 bg-light p-3 fw-bold border-end text-muted">Lokasi</div>
                                        <div class="col-9 p-3 text-dark">{{ $data->location }}</div>
                                    </div>
                                </div>
                                <div class="col-12 border-bottom">
                                    <div class="d-flex">
                                        <div class="col-3 bg-light p-3 fw-bold border-end text-muted">Dept / Perusahaan
                                        </div>
                                        <div class="col-9 p-3 text-dark">
                                            {{ $data->department->name ?? ($data->activity_company ?? '-') }}</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex">
                                        <div class="col-3 bg-light p-3 fw-bold border-end text-muted">Tanggal</div>
                                        <div class="col-9 p-3 text-dark">
                                            {{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}</div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="mb-3 mt-5">
                        <div class="mb-2">
                            <h6 class="fw-bold text-uppercase border-bottom border-dark pb-2">
                                Tim Pemeriksa / Penilai
                            </h6>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered border-dark align-middle">
                                <thead class="bg-light">
                                    <tr class="text-center small">
                                        <th class="py-2" style="width: 5%;">NO</th>
                                        <th class="py-2" style="width: 35%;">NAMA LENGKAP</th>
                                        <th class="py-2" style="width: 30%;">JABATAN / ROLE</th>
                                        <th class="py-2" style="width: 30%;">DIREKTORAT / DEPT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->teams as $index => $member)
                                        <tr>
                                            <td class="text-center py-2 text-dark">{{ $index + 1 }}</td>
                                            <td class="py-2 px-3 text-dark">{{ $member->name }}</td>
                                            <td class="py-2 px-3 text-dark">{{ $member->role }}</td>
                                            <td class="py-2 px-3 text-dark">{{ $member->department }}</td>
                                        </tr>
                                    @endforeach

                                    {{-- Jika data kosong, tampilkan baris kosong agar tabel tidak 'hilang' --}}
                                    @if ($data->teams->isEmpty())
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted italic">Tidak ada
                                                anggota tim terdaftar</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-5 mb-4">
                        <div class="mb-3">
                            <h6 class="fw-bold text-uppercase border-bottom border-dark pb-2">
                                <i class="fas fa-clipboard-check me-2"></i>Pemeriksaan Item
                            </h6>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered border-dark align-middle">
                                <thead class="bg-light text-center">
                                    <tr class="small fw-bold">
                                        <th class="py-2" style="width: 50px;">NO</th>
                                        <th class="py-2 text-start">ITEM PEMERIKSAAN</th>
                                        <th class="py-2" style="width: 100px;">HASIL</th>
                                        <th class="py-2" style="width: 400px;">KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($checking_items as $index => $item)
                                        @php
                                            $savedItem = $data->items->where('checking_item_id', $item->id)->first();
                                            $result = $savedItem ? $savedItem->result : 'N/A';
                                        @endphp
                                        <tr>
                                            <td class="text-center py-2 text-dark small">{{ $loop->iteration }}</td>
                                            <td class="py-2 px-3 text-dark fw-bold" style="word-break: break-word;">
                                                {{ $item->name }}
                                            </td>
                                            <td class="text-center py-2">
                                                {{-- Tampilan Badge Kotak yang Tegas untuk Hasil --}}
                                                <span
                                                    class="fw-bold {{ $result == 'Y' ? 'text-success' : ($result == 'N' ? 'text-danger' : 'text-muted') }}">
                                                    {{ $result == 'Y' ? 'YES' : ($result == 'N' ? 'NO' : 'N/A') }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-3 text-dark small" style="min-height: 40px;">
                                                {{ $savedItem && $savedItem->remarks ? $savedItem->remarks : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="mb-2">
                            <h6 class="fw-bold text-uppercase border-bottom border-dark pb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Temuan Tambahan (Luar Pemeriksaan)
                            </h6>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered border-dark align-middle mb-0">
                                <thead class="bg-light text-center">
                                    <tr class="small fw-bold">
                                        <th class="py-2" style="width: 50px;">NO</th>
                                        <th class="py-2 text-start">DESKRIPSI TEMUAN</th>
                                        <th class="py-2" style="width: 250px;">KATEGORI HASIL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($existingFindings as $index => $finding)
                                        <tr>
                                            <td class="text-center py-2 text-dark small">{{ $index + 1 }}</td>
                                            <td class="py-2 px-3 text-dark fw-bold" style="word-break: break-word;">
                                                {{ $finding->checking_item_name }}
                                            </td>
                                            <td class="text-center py-2">
                                                {{-- Konversi nilai select ke teks statis yang rapi --}}
                                                @php
                                                    $resultText = $finding->result;
                                                    // Menyesuaikan label jika di database berbeda dengan tampilan
                                                    if ($resultText == 'Temuan Positif') {
                                                        $resultText = 'Kondisi Tidak Aman';
                                                    }
                                                    if ($resultText == 'Perlu Tindakan Perbaikan') {
                                                        $resultText = 'Perilaku Tidak Aman';
                                                    }
                                                @endphp
                                                <span
                                                    class="text-uppercase small fw-bold {{ $finding->result ? 'text-danger' : 'text-muted' }}">
                                                    {{ $resultText ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted small italic">
                                                <i class="fas fa-info-circle me-1"></i> Tidak ada data temuan tambahan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="mb-2">
                            <h6 class="fw-bold text-uppercase border-bottom border-dark pb-2">
                                <i class="fas fa-tasks me-2"></i>Tindak Lanjut Temuan
                            </h6>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered border-dark align-middle mb-0">
                                <thead class="bg-light text-center">
                                    <tr class="small fw-bold">
                                        <th class="py-2" style="width: 25%;">TINDAKAN LANGSUNG</th>
                                        <th class="py-2" style="width: 20%;">KATEGORI</th>
                                        <th class="py-2" style="width: 15%;">STATUS</th>
                                        <th class="py-2" style="width: 25%;">PIC / PENANGGUNG JAWAB</th>
                                        <th class="py-2" style="width: 15%;">BATAS WAKTU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data->actions as $index => $action)
                                        <tr>
                                            <td class="py-2 px-3 text-dark small" style="word-break: break-word;">
                                                {{ $action->name }}
                                            </td>
                                            <td class="text-center py-2 small text-dark">
                                                {{ $action->category ?? '-' }}
                                            </td>
                                            <td class="text-center py-2">
                                                @php
                                                    $statusLabel =
                                                        $action->status == 'ACT_REQ'
                                                            ? 'ACTION REQUIRED'
                                                            : $action->status;
                                                    $statusClass =
                                                        $action->status == 'COMPLETED' ? 'text-success' : 'text-danger';
                                                @endphp
                                                <span class="fw-bold small {{ $statusClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-3 text-dark small">
                                                {{-- Mengambil nama user langsung dari relasi --}}
                                                {{ $action->assignee_name ?? '-' }}
                                            </td>
                                            <td class="text-center py-2 text-dark small">
                                                {{ $action->due_date ? \Carbon\Carbon::parse($action->due_date)->format('d/m/Y') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted small italic">
                                                <i class="fas fa-clipboard-list me-1"></i> Belum ada data tindak
                                                lanjut.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- SECTION LAMPIRAN --}}
                    @if ($data->attachments && $data->attachments->count() > 0)
                        <div class="mt-5">
                            <h6 class="fw-bold text-uppercase border-bottom border-dark pb-2 mb-3">
                                <i class="fas fa-images me-2"></i>Lampiran Dokumentasi
                            </h6>

                            <div class="row g-2"> {{-- g-2 untuk jarak antar foto yang rapat dan rapi --}}
                                @foreach ($data->attachments as $attachment)
                                    <div class="col-md-3 col-6 mb-3" id="attachment-{{ $attachment->id }}">
                                        <div class="border border-dark p-1 bg-white h-100 shadow-sm">
                                            <div class="d-flex align-items-center justify-content-center bg-light"
                                                style="height: 180px; overflow: hidden; border: 1px solid #dee2e6;">
                                                <img src="{{ route('storage.external', ['folder' => 'workplace_control', 'filename' => basename($attachment->file_path)]) }}"
                                                    style="width: 100%; height: 100%; object-fit: cover;"
                                                    alt="Lampiran"
                                                    onerror="this.onerror=null;this.src='{{ asset('assets/img/no-image.png') }}';">
                                            </div>
                                            <div class="p-2">
                                                <p class="mb-0 small text-truncate text-center fw-bold text-dark">
                                                    {{ $attachment->file_name }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- TOMBOL AKSI UTAMA --}}
                    <div class="mt-5 mb-5 border-top pt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            {{-- Keterangan Cetak (Opsional, memperkuat kesan dokumen) --}}
                            <div class="small text-muted italic">
                                Dicetak pada: {{ date('d/m/Y H:i') }}
                            </div>

                            <div class="d-flex gap-2">
                                @if($is_able_to_admin_edit)
                                    <a href="{{ route('transaction-workPlace.admin_edit', $data->id) }}"
                                        class="btn btn-outline-warning text-dark px-4 fw-bold rounded-0">
                                        <i class="fas fa-pen me-2"></i> ADMIN EDIT
                                    </a>
                                @endif
                                @if($is_able_to_admin_delete)
                                    <form action="{{ route('transaction-workPlace.destroy', ['id' => $data->id]) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn btn-outline-danger px-4 fw-bold rounded-0 sa-delete">
                                            <i class="fas fa-trash me-2"></i> DELETE
                                        </button>
                                    </form>
                                @endif
                                
                                <button onclick="window.print()" class="btn btn-dark px-4 fw-bold rounded-0">
                                    <i class="fas fa-print me-2"></i> CETAK PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($data->attachments && $data->attachments->count() > 0)
                <div class="mt-4">
                    <p class="mb-1 fw-bold">Lampiran (gambar)</p>
                    <div class="row mb-3">
                        @foreach ($data->attachments as $attachment)
                            <div class="col-md-3 col-sm-6 mb-4" id="attachment-{{ $attachment->id }}">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="d-flex align-items-center justify-content-center bg-light rounded-top"
                                        style="height: 200px; overflow: hidden;">
                                        <img src="{{ route('storage.external', ['folder' => 'workplace_control', 'filename' => basename($attachment->file_path)]) }}"
                                            style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain;"
                                            alt="Lampiran"
                                            onerror="this.onerror=null;this.src='{{ asset('assets/img/no-image.png') }}';">
                                    </div>
                                    <div class="card-body p-2 border-top d-flex flex-column justify-content-center">
                                        <p class="mb-0 text-truncate text-muted text-center"
                                            style="font-size: 0.8rem;">
                                            {{ $attachment->file_name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.sa-delete');

            if (deleteBtn) {
                e.preventDefault();

                const form = deleteBtn.closest('form');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    border: 'none',
                    customClass: {
                        confirmButton: 'rounded-pill px-4',
                        cancelButton: 'rounded-pill px-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });

        function updateSelectColor(el) {
            if (!el) return;
            const value = el.value;
            el.style.setProperty('background-color', '#f8f9fa', 'important');
            el.style.setProperty('color', '#6c757d', 'important');
            el.style.borderColor = '#dee2e6';
            if (value === 'Y') {
                el.style.setProperty('background-color', '#28a745', 'important'); // Hijau Solid
                el.style.setProperty('color', '#ffffff', 'important'); // Teks Putih
                el.style.borderColor = '#28a745';
            } else if (value === 'N') {
                el.style.setProperty('background-color', '#dc3545', 'important'); // Merah Solid
                el.style.setProperty('color', '#ffffff', 'important'); // Teks Putih
                el.style.borderColor = '#dc3545';
            } else if (value === 'NA') {
                el.style.setProperty('background-color', '#6c757d', 'important'); // Abu-abu Solid
                el.style.setProperty('color', '#ffffff', 'important'); // Teks Putih
                el.style.borderColor = '#6c757d';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const selectors = 'select.status-color-control';
            document.querySelectorAll(selectors).forEach(select => {
                updateSelectColor(select);
                select.addEventListener('change', function() {
                    updateSelectColor(this);
                });
            });
        });
    </script>
</x-app-layout>
