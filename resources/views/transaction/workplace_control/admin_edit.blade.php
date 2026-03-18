<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <div class="page-title-right">
                    <a href="{{ route('transaction-workPlace.index') }}" class="btn fs-4 btn-back">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                </div>
                <h4 class="mb-0">Edit Workplace Control</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- FORM UTAMA --}}
            <form action="{{ route('transaction-workPlace.admin_update', $data->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card rounded-5 p-5">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select class="form-select border-secondary rounded-pill"
                                    onchange="window.location='?type='+this.value">
                                    @foreach ($type_sel as $t)
                                        {{-- Gunakan trim() untuk memastikan perbandingan bersih dari spasi --}}
                                        <option value="{{ trim($t) }}"
                                            {{ trim($type) == trim($t) ? 'selected' : '' }}>
                                            {{ str_replace('_', ' ', $t) }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="type" value="{{ $type }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="card w-100 mb-4 border shadow-none">
                                <div class="card-body p-0">
                                    <div class="row g-0">
                                        {{-- Bagian ini akan otomatis menyesuaikan isi berdasarkan type --}}
                                        @if ($type == 'INSPECTION_VEHICLE')
                                            {{-- Kolom Kiri --}}
                                            <div class="col-md-6 border-end">
                                                <div class="d-flex border-bottom">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Driver / Operator</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="text" name="operator_name"
                                                            class="form-control rounded-pill border-secondary"
                                                            value="{{ $data->operator_name }}"
                                                            placeholder="Nama Driver">
                                                    </div>
                                                </div>
                                                <div class="d-flex border-bottom border-md-bottom-0">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>ID Kendaraan</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="text" name="vehicle_code"
                                                            class="form-control rounded-pill border-secondary"
                                                            value="{{ $data->vehicle_code }}"
                                                            placeholder="Contoh: DT-102">
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Kolom Kanan --}}
                                            <div class="col-md-6">
                                                <div class="d-flex border-bottom">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Jenis Kendaraan</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <select class="form-select rounded-pill border-secondary"
                                                            name="vehicle_type">
                                                            <option value="LV"
                                                                {{ $data->vehicle_type == 'LV' ? 'selected' : '' }}>
                                                                Light Vehicle (LV)</option>
                                                            <option value="Bus"
                                                                {{ $data->vehicle_type == 'Bus' ? 'selected' : '' }}>Bus
                                                            </option>
                                                            <option value="Truck"
                                                                {{ $data->vehicle_type == 'Truck' ? 'selected' : '' }}>
                                                                Truck</option>
                                                            <option value="Heavy Equipment"
                                                                {{ $data->vehicle_type == 'Heavy Equipment' ? 'selected' : '' }}>
                                                                Heavy Equipment</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($type === 'INSPECTION_BUILDING')
                                            <div class="row">
                                                <div class="card w-100 mb-4">
                                                    <div class="card-body p-0">
                                                        <div class="row g-0">
                                                            {{-- --- KOLOM KIRI --- --}}
                                                            <div class="col-md-6 border-end">
                                                                {{-- Jenis Bangunan --}}
                                                                <div class="d-flex border-bottom">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Jenis Bangunan</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <input type="text" name="building_type"
                                                                            class="form-control rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                            placeholder="Contoh: Kantor / Gudang / Mess"
                                                                            value="{{ old('building_type', $data->building_type) }}"
                                                                            required>
                                                                    </div>
                                                                </div>

                                                                {{-- Department / Perusahaan --}}
                                                                <div class="d-flex">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Department / Perusahaan</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <select name="department_id"
                                                                            class="form-select rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                            required id="search">
                                                                            <option value="">Pilih Department
                                                                            </option>
                                                                            @foreach ($dept as $depart)
                                                                                <option value="{{ $depart->id }}"
                                                                                    {{ old('department_id', $data->department_id) == $depart->id ? 'selected' : '' }}>
                                                                                    {{ $depart->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- --- KOLOM KANAN --- --}}
                                                            <div class="col-md-6">
                                                                {{-- Tanggal Pemeriksaan --}}
                                                                <div class="d-flex border-bottom">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Tanggal Pemeriksaan</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <input type="date" name="date"
                                                                            class="form-control rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                            required
                                                                            value="{{ old('date', \Carbon\Carbon::parse($data->date)->format('Y-m-d')) }}">
                                                                    </div>
                                                                </div>

                                                                {{-- Lokasi Pemeriksaan --}}
                                                                <div class="d-flex">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Lokasi Pemeriksaan</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <select name="location"
                                                                            class="form-select rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                            required id="search_lok">
                                                                            <option value="">Pilih Lokasi
                                                                            </option>
                                                                            @foreach ($location as $loc)
                                                                                <option value="{{ $loc->name }}"
                                                                                    {{ old('location', $data->location) == $loc->name ? 'selected' : '' }}>
                                                                                    {{ $loc->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($type === 'PLANNED_TASK_OBSERVATION')
                                            <div class="row">
                                                <div class="card w-100 mb-4">
                                                    <div class="card-body p-0">
                                                        <div class="row g-0">
                                                            {{-- --- KOLOM KIRI --- --}}
                                                            <div class="col-md-6 border-end">
                                                                {{-- Nama Site --}}
                                                                <div class="d-flex border-bottom">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Nama Site</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <input type="text" name="site"
                                                                            class="form-control bg-light rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                            value="{{ old('site', $data->site ?? 'Awak Mas') }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>

                                                                {{-- Area Kerja (Lokasi) --}}
                                                                <div class="d-flex border-bottom">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Area Kerja</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <select name="location"
                                                                            class="form-select rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;" id="pto_area">
                                                                            <option value="">Select Area</option>
                                                                            @foreach ($location as $loc)
                                                                                <option value="{{ $loc->name }}"
                                                                                    {{ old('location', $data->location) == $loc->name ? 'selected' : '' }}>
                                                                                    {{ $loc->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                {{-- Aktivitas Yang Dilakukan --}}
                                                                <div class="d-flex border-bottom">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Aktivitas Dilakukan</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <input type="text" name="activity"
                                                                            class="form-control rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                            placeholder="Contoh: Pengangkatan Material"
                                                                            value="{{ old('activity', $data->activity) }}"
                                                                            required>
                                                                    </div>
                                                                </div>

                                                                {{-- Siapa Yang Melakukan Aktivitas? --}}
                                                                <div class="d-flex">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Personel Terlibat</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <input type="text" name="activity_person"
                                                                            class="form-control rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                            placeholder="Nama nama pekerja..."
                                                                            value="{{ old('activity_person', $data->activity_person) }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- --- KOLOM KANAN --- --}}
                                                            <div class="col-md-6">
                                                                {{-- Tanggal Observasi --}}
                                                                <div class="d-flex border-bottom">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Tanggal Observasi</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <input type="date" name="date"
                                                                            class="form-control bg-light rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                            value="{{ old('date', \Carbon\Carbon::parse($data->date)->format('Y-m-d')) }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>

                                                                {{-- Jumlah Pekerja Yang Terlibat --}}
                                                                <div class="d-flex border-bottom">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Jumlah Pekerja</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <input type="number" name="employee_count"
                                                                            class="form-control rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                            min="1" placeholder="0"
                                                                            value="{{ old('employee_count', $data->employee_count) }}">
                                                                    </div>
                                                                </div>

                                                                {{-- Pengawas Area --}}
                                                                <div class="d-flex border-bottom">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Pengawas Area</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <input type="text" name="area_supervisor"
                                                                            class="form-control rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                            placeholder="Nama Pengawas Area"
                                                                            value="{{ old('area_supervisor', $data->area_supervisor) }}">
                                                                    </div>
                                                                </div>

                                                                {{-- Alasan Observasi --}}
                                                                <div class="d-flex">
                                                                    <div class="bg-light px-3 py-3 border-end"
                                                                        style="width:45%;">
                                                                        <strong>Alasan Observasi</strong>
                                                                    </div>
                                                                    <div class="px-3 py-2 flex-grow-1">
                                                                        <input type="text"
                                                                            name="observation_reason"
                                                                            class="form-control rounded-pill text-secondary border-2"
                                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                            placeholder="Contoh: Kepatuhan Rutin"
                                                                            value="{{ old('observation_reason', $data->observation_reason) }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- --- BARIS BAWAH (PROSEDUR JSA KERJA TERKAIT) --- --}}
                                                        <div class="d-flex border-top">
                                                            <div class="bg-light px-3 py-3 border-end"
                                                                style="width:22.5%;">
                                                                <strong>Prosedur / JSA Terkait</strong>
                                                            </div>
                                                            <div class="px-3 py-2 flex-grow-1">
                                                                <input type="text" name="procedure"
                                                                    class="form-control rounded-pill text-secondary border-2"
                                                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                    placeholder="Masukkan Nomor JSA atau Judul Prosedur"
                                                                    value="{{ old('procedure', $data->procedure) }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif (in_array($type, [
                                                'INSPECTION_ROAD',
                                                'INSPECTION_DRILLING_AREA',
                                                'INSPECTION_CONSTRUCTION_AREA',
                                                'INSPECTION_DUMP_POINT_AREA',
                                                'INSPECTION_LOADING_POINT_AREA',
                                            ]))
                                            <div class="row">
                                                <div class="card w-100 mb-4">
                                                    <div class="card-body p-0">
                                                        {{-- Lokasi Pemeriksaan --}}
                                                        <div class="d-flex border-bottom">
                                                            <div class="bg-light px-3 py-3 border-end"
                                                                style="width:30%;">
                                                                <strong>Lokasi Pemeriksaan</strong>
                                                            </div>
                                                            <div class="px-3 py-2 flex-grow-1">
                                                                <select name="location"
                                                                    class="form-select rounded-pill text-secondary border-2"
                                                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                    required id="lok_pemeriksaan">
                                                                    <option value="">Select Lokasi</option>
                                                                    @foreach ($location as $loc)
                                                                        <option value="{{ $loc->name }}"
                                                                            {{ old('location', $data->location) == $loc->name ? 'selected' : '' }}>
                                                                            {{ $loc->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        {{-- Department / Perusahaan --}}
                                                        <div class="d-flex border-bottom">
                                                            <div class="bg-light px-3 py-3 border-end"
                                                                style="width:30%;">
                                                                <strong>Department / Perusahaan</strong>
                                                            </div>
                                                            <div class="px-3 py-2 flex-grow-1">
                                                                <select name="department_id"
                                                                    class="form-select rounded-pill text-secondary border-2"
                                                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                    required>
                                                                    <option value="">Select Department ---
                                                                    </option>
                                                                    @foreach ($dept as $depart)
                                                                        <option value="{{ $depart->id }}"
                                                                            {{ old('department_id', $data->department_id) == $depart->id ? 'selected' : '' }}>
                                                                            {{ $depart->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        {{-- Tanggal Pemeriksaan --}}
                                                        <div class="d-flex">
                                                            <div class="bg-light px-3 py-3 border-end"
                                                                style="width:30%;">
                                                                <strong>Tanggal Pemeriksaan</strong>
                                                            </div>
                                                            <div class="px-3 py-2 flex-grow-1">
                                                                <input type="date" name="date"
                                                                    class="form-control rounded-pill text-secondary border-2"
                                                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                                    required
                                                                    value="{{ old('date', \Carbon\Carbon::parse($data->date)->format('Y-m-d')) }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- =========================== --}}

                    {{-- TIM PEMERIKSA --}}
                    <div class="mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="fw-bold mb-0 text-secondary">
                                Tim Pemeriksa / Penilai
                            </h5>
                        </div>
                        <table class="table table-borderless bg-transparent">
                            <tbody id="team-table-body" class="bg-transparent">
                                @forelse ($data->teams as $index => $member)
                                    @php
                                        // Ambil nilai dari DB, pastikan jadi string untuk perbandingan
                                        $currentName = trim((string) ($member->name ?? ''));
                                        $currentDept = trim((string) ($member->department ?? ''));
                                    @endphp

                                    <tr class="border-bottom d-block pb-2 mb-3 bg-transparent team-row">
                                        <td class="d-block p-0 border-0 bg-transparent">
                                            <div class="row g-2 mb-2">

                                                <div class="col-md-4">
                                                    <label class="fw-bold mb-1 small text-muted">Nama Lengkap</label>
                                                    <select name="members[{{ $index }}][name]"
                                                        class="form-select shadow-sm rounded-pill text-secondary border-2 bg-transparent" id="search">
                                                        <option value="">-- Pilih Anggota --</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->name }}" {{-- BANDINGKAN DENGAN $member->name, BUKAN DENGAN $data->actions --}}
                                                                {{ (old("members.$index.name") ?? $member->name) == $user->name ? 'selected' : '' }}>
                                                                {{ $user->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="fw-bold mb-1 small text-muted">Role</label>
                                                    <input type="text" name="members[{{ $index }}][role]"
                                                        class="form-control shadow-sm rounded-pill text-secondary border-2 bg-transparent"
                                                        value="{{ $member->role ?? '' }}" placeholder="Role">
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="fw-bold mb-1 small text-muted">Department</label>
                                                    <select name="members[{{ $index }}][department]"
                                                        class="form-select shadow-sm rounded-pill text-secondary border-2 bg-transparent" id="dept">
                                                        <option value="">-- Pilih Dept --</option>
                                                        @foreach ($dept as $d)
                                                            <option value="{{ $d->name }}"
                                                                {{ $currentDept === trim((string) $d->name) ? 'selected' : '' }}>
                                                                {{ $d->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="d-flex justify-content-end pt-2">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger rounded-pill btn-remove-row">
                                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="empty-row">
                                        <td class="text-center text-muted py-4">Belum ada anggota. Klik "Add new line".
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>


                    {{-- ITEM PEMERIKSAAN --}}
                    <div class="d-flex align-items-center mt-5 mb-2">
                        <h5 class="fw-bold mb-0 text-secondary">
                            Pemeriksaan Item
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless table-sm mt-4 mb-0 bg-transparent"
                            style="table-layout: fixed; min-width: 900px; width: 100%;">
                            <thead class="bg-transparent">
                                <tr class="border-0">
                                    <th class="bg-light border-0 py-3 text-dark text-center align-middle"
                                        style="width: 50px;">No</th>
                                    <th class="bg-light border-0 py-3 text-dark align-middle">Item Pemeriksaan</th>
                                    <th class="bg-light border-0 py-3 text-dark text-center align-middle"
                                        style="width: 120px;">Hasil</th>
                                    <th class="bg-light border-0 py-3 text-dark align-middle" style="width: 450px;">
                                        Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-transparent">
                                @foreach ($checking_items as $index => $item)
                                    @php
                                        $savedItem = $data->items->where('checking_item_id', $item->id)->first();
                                    @endphp
                                    <tr class="bg-transparent border-bottom">
                                        <td class="bg-transparent text-center align-middle text-muted small">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="bg-transparent align-middle fw-bold text-secondary text-wrap"
                                            style="word-break: break-word;">
                                            {{ $item->name }}
                                            <input type="hidden" name="items[{{ $index }}][checking_item_id]"
                                                value="{{ $item->id }}">
                                            <input type="hidden"
                                                name="items[{{ $index }}][checking_item_name]"
                                                value="{{ $item->name }}">
                                        </td>

                                        <td class="bg-transparent align-middle px-2 text-center">
                                            <select name="items[{{ $index }}][result]"
                                                class="form-select rounded-pill status-color-control fw-bold"
                                                style="font-size: 0.85rem; height: 38px; transition: all 0.2s ease-in-out; cursor: pointer;">
                                                <option value=""
                                                    {{ $savedItem && $savedItem->result == '' ? 'selected' : '' }}>N/A
                                                </option>
                                                <option value="Y"
                                                    {{ $savedItem && $savedItem->result == 'Y' ? 'selected' : '' }}>Y
                                                </option>
                                                <option value="N"
                                                    {{ $savedItem && $savedItem->result == 'N' ? 'selected' : '' }}>N
                                                </option>
                                            </select>
                                        </td>

                                        <td class="bg-transparent align-middle px-2 py-2">
                                            <textarea name="items[{{ $index }}][remark]"
                                                class="form-control rounded-3 bg-light text-dark border-2 shadow-sm" rows="1"
                                                placeholder="Tulis keterangan lengkap di sini..."
                                                style="border-color: #dee2e6; font-size: 0.9rem; min-height: 50px; width: 100%; resize: vertical;">{{ $savedItem ? $savedItem->remarks : '' }}</textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- TEMUAN DI LUAR LIST --}}
                    <div class="d-flex align-items-center mt-5 mb-2 ps-2">
                        <h5 class="mb-0 fw-bold text-secondary">
                            Temuan yang tidak terdapat pada pemeriksaan di atas
                        </h5>
                    </div>

                    <div class="mb-3 mt-4">
                        <div id="finding-table">
                            <div class="table-responsive" style="overflow: visible;">
                                <table class="table table-borderless align-middle bg-transparent">
                                    <tbody id="findings-container" class="bg-transparent">
                                        @foreach ($existingFindings as $index => $finding)
                                            <tr class="bg-transparent">
                                                <td class="border-0 py-2" style="width: 65%;">
                                                    <label class="text-muted fw-bold mb-1 small ps-3">Temuan</label>
                                                    <textarea name="findings[{{ $index }}][name]" class="form-control border-0 shadow-sm px-4"
                                                        style="border-radius: 30px; min-height: 45px; background-color: #fff; resize: none;" rows="1"
                                                        placeholder="Tulis temuan di sini...">{{ $finding->checking_item_name }}</textarea>

                                                    <input type="hidden" name="findings[{{ $index }}][id]"
                                                        value="{{ $finding->id }}">
                                                </td>

                                                <td class="border-0 py-2" style="width: 25%;">
                                                    <label class="text-muted fw-bold mb-1 small ps-3">Hasil</label>
                                                    <select name="findings[{{ $index }}][status]"
                                                        class="form-select border-0 shadow-sm ps-4 pe-5 status-color-control fw-bold"
                                                        style="border-radius: 30px; height: 45px; background-color: #fff; font-size: 0.9rem;"
                                                        onchange="updateSelectColor(this)">
                                                        <option value="" class="text-dark">Pilih</option>
                                                        <option value="Temuan Positif" class="text-dark"
                                                            {{ $finding->result == 'Temuan Positif' ? 'selected' : '' }}>
                                                            Temuan Positif
                                                        </option>
                                                        <option value="Perlu Tindak Perbaikan" class="text-dark"
                                                            {{ $finding->result == 'Perlu Tindak Perbaikan' ? 'selected' : '' }}>
                                                            Perlu Tindak Perbaikan
                                                        </option>
                                                    </select>
                                                </td>

                                                <td class="border-0 py-2 text-center"
                                                    style="vertical-align: bottom; width: 10%;">
                                                    <div class="d-flex align-items-center justify-content-center"
                                                        style="height: 45px;">
                                                        <button type="button"
                                                            class="btn btn-link text-danger p-0 btn-remove-finding"
                                                            style="text-decoration: none; line-height: 1;"
                                                            title="Hapus">
                                                            <i class="fas fa-times-circle fa-lg"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if ($existingFindings->isEmpty())
                                            <tr class="bg-transparent">
                                                <td class="border-0 py-2">
                                                    <label class="text-muted fw-bold mb-1 small ps-3">Temuan</label>
                                                    <textarea name="findings[0][name]" class="form-control border-0 shadow-sm px-4"
                                                        style="border-radius: 30px; min-height: 45px; background-color: #fff; resize: none;" rows="1"
                                                        placeholder="Tulis temuan di sini..."></textarea>
                                                </td>
                                                <td class="border-0 py-2">
                                                    <label class="text-muted fw-bold mb-1 small ps-3">Hasil</label>
                                                    <select name="findings[0][status]"
                                                        class="form-select border-0 shadow-sm ps-4 pe-5 status-color-control"
                                                        style="border-radius: 30px; height: 45px; background-color: #fff; font-size: 0.9rem;">
                                                        <option value="" class="text-dark">Pilih</option>
                                                        <option value="Temuan Positif" class="text-dark">Temuan
                                                            Positif</option>
                                                        <option value="Perlu Tindak Perbaikan" class="text-dark">Perlu
                                                            Tindak Perbaikan</option>
                                                    </select>
                                                </td>
                                                <td class="border-0 py-2 text-center" style="vertical-align: bottom;">
                                                    <div class="d-flex align-items-center justify-content-center"
                                                        style="height: 45px;">
                                                        <button type="button"
                                                            class="btn btn-link text-danger p-0 btn-remove-finding"
                                                            style="line-height: 1;">
                                                            <i class="fas fa-times-circle fa-lg"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                                <div class="ps-2 mt-3">
                                    <button type="button" id="btn-add-finding"
                                        class="btn btn-sm rounded-pill px-4 shadow-sm border"
                                        style="background-color: #efefef; color: #6c757d; font-size: 0.75rem; font-weight: 500;">
                                        Add new line
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- TINDAK LANJUT TEMUAN --}}
                    <div class="container-fluid mt-5">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center mb-4 ps-2">
                                    <h5 class="fw-bold mb-0 text-secondary" style="font-size: 1.1rem;">Tindak Lanjut
                                        Temuan</h5>
                                </div>

                                <div class="table-responsive" style="overflow: visible;">
                                    <style>
                                        /* Hapus paksa semua garis bawaan bootstrap */
                                        #tindak_lanjut,
                                        #tindak_lanjut thead th,
                                        #tindak_lanjut tbody td,
                                        #tindak_lanjut tr {
                                            border: none !important;
                                            border-bottom: none !important;
                                            box-shadow: none;
                                        }

                                        /* Kasih jarak antar baris supaya rapi */
                                        #tindak_lanjut {
                                            border-collapse: separate;
                                            border-spacing: 0 12px;
                                        }
                                    </style>

                                    <table class="table table-borderless align-middle bg-transparent"
                                        id="tindak_lanjut">
                                        <thead>
                                            <tr class="text-secondary small">
                                                <th class="ps-3 text-muted fw-bold" style="width: 25%;">Tindakan
                                                    langsung untuk temuan</th>
                                                <th class="text-muted fw-bold" style="width: 15%;">Kategori Temuan
                                                </th>
                                                <th class="text-muted fw-bold" style="width: 15%;">Status</th>
                                                <th class="text-muted fw-bold" style="width: 20%;">PIC</th>
                                                <th class="text-muted fw-bold" style="width: 15%;">Batas Waktu</th>
                                                <th class="text-center" style="width: 50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data->actions as $index => $action)
                                                <tr>
                                                    <td class="p-0 pe-2">
                                                        <textarea name="actions[{{ $index }}][name]" class="form-control border-0 shadow-sm px-3" rows="1"
                                                            style="border-radius: 30px; min-height: 45px; background-color: #fff; resize: none;">{{ $action->name }}</textarea>
                                                    </td>
                                                    <td class="p-0 pe-2">
                                                        <select name="actions[{{ $index }}][category]"
                                                            class="form-select border-0 shadow-sm ps-3"
                                                            style="border-radius: 30px; height: 45px; background-color: #fff; font-size: 0.9rem;">
                                                            <option value="" disabled>Pilih...</option>
                                                            <option value="Kondisi Tidak Aman"
                                                                {{ $action->category == 'Kondisi Tidak Aman' ? 'selected' : '' }}>
                                                                Kondisi Tidak Aman</option>
                                                            <option value="Perilaku Tidak Aman"
                                                                {{ $action->category == 'Perilaku Tidak Aman' ? 'selected' : '' }}>
                                                                Perilaku Tidak Aman</option>
                                                        </select>
                                                    </td>
                                                    <td class="p-0 pe-2">
                                                        <select name="actions[{{ $index }}][status]"
                                                            class="form-select border-0 shadow-sm px-3"
                                                            style="border-radius: 30px; height: 45px; font-size: 0.85rem; background-color: #fff;">
                                                            <option value="ACT_REQ"
                                                                {{ $action->status == 'ACT_REQ' ? 'selected' : '' }}>
                                                                ACTION REQUIRED</option>
                                                            <option value="COMPLETED"
                                                                {{ $action->status == 'COMPLETED' ? 'selected' : '' }}>
                                                                COMPLETED</option>
                                                        </select>
                                                    </td>
                                                    <td class="p-0 pe-2">
                                                        <select name="actions[{{ $index }}][assignee_id]"
                                                            class="form-select border-0 shadow-sm px-3"
                                                            style="border-radius: 30px; height: 45px; background-color: #fff;" id="pic_user">
                                                            <option value="" disabled>Pilih PIC...</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}"
                                                                    {{ $action->assignee_id == $user->id ? 'selected' : '' }}>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="p-0 pe-2">
                                                        <input type="date"
                                                            name="actions[{{ $index }}][due_date]"
                                                            class="form-control border-0 shadow-sm px-3"
                                                            style="border-radius: 30px; height: 45px; background-color: #fff;"
                                                            value="{{ $action->due_date }}">
                                                    </td>
                                                    <td class="p-0 text-center">
                                                        <button type="button"
                                                            class="btn btn-link text-danger p-0 btn-remove-row"
                                                            style="text-decoration: none;">
                                                            <i class="fas fa-times-circle fa-lg"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="p-0 pe-2">
                                                        <textarea name="actions[0][name]" class="form-control border-0 shadow-sm px-3" rows="1"
                                                            style="border-radius: 30px; min-height: 45px; background-color: #fff; resize: none;"
                                                            placeholder="Input tindakan..."></textarea>
                                                    </td>
                                                    <td class="p-0 pe-2">
                                                        <select name="actions[0][category]"
                                                            class="form-select border-0 shadow-sm ps-3"
                                                            style="border-radius: 30px; height: 45px; background-color: #fff; font-size: 0.9rem;">
                                                            <option value="" selected disabled>Pilih...</option>
                                                            <option value="Kondisi Tidak Aman">Kondisi Tidak Aman
                                                            </option>
                                                            <option value="Perilaku Tidak Aman">Perilaku Tidak Aman
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="p-0 pe-2">
                                                        <select name="actions[0][status]"
                                                            class="form-select border-0 shadow-sm px-3"
                                                            style="border-radius: 30px; height: 45px; font-size: 0.85rem; background-color: #fff;">
                                                            <option value="ACT_REQ">ACTION REQUIRED</option>
                                                            <option value="COMPLETED">COMPLETED</option>
                                                        </select>
                                                    </td>
                                                    <td class="p-0 pe-2">
                                                        <select name="actions[0][assignee_id]"
                                                            class="form-select border-0 shadow-sm px-3"
                                                            style="border-radius: 30px; height: 45px; background-color: #fff;">
                                                            <option value="" selected disabled>Pilih PIC...
                                                            </option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}">
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="p-0 pe-2">
                                                        <input type="date" name="actions[0][due_date]"
                                                            class="form-control border-0 shadow-sm px-3"
                                                            style="border-radius: 30px; height: 45px; background-color: #fff;">
                                                    </td>
                                                    <td class="p-0 text-center">
                                                        <button type="button"
                                                            class="btn btn-link text-danger p-0 btn-remove-row">
                                                            <i class="fas fa-times-circle fa-lg"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <div class="ps-2 mt-2">
                                        <button type="button" id="btn-add-tindakLanjut"
                                            class="btn btn-sm rounded-pill px-4 shadow-sm"
                                            style="background-color: #e9ecef; color: #6c757d; border: none; font-size: 0.75rem; font-weight: 500; height: 32px;">
                                            Add new line
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- LAMPIRAN --}}
                    <div class="mt-4">
                        {{-- Bagian List Gambar Existing tetap sama --}}
                        @if ($data->attachments->count() > 0)
                            <div class="row mb-3">
                                @foreach ($data->attachments as $attachment)
                                    <div class="col-md-3 col-sm-6 mb-3" id="attachment-{{ $attachment->id }}">
                                        <div class="card shadow-sm border-0 h-100">
                                            <div class="d-flex align-items-center justify-content-center bg-dark rounded-top"
                                                style="height: 300px; overflow: hidden;">
                                                <img src="{{ route('storage.external', ['folder' => '-', 'filename' => basename($attachment->file_path)]) }}"
                                                    style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain;"
                                                    alt="Lampiran"
                                                    onerror="this.onerror=null;this.src='{{ asset('assets/img/no-image.png') }}';">
                                            </div>
                                            <div class="card-body p-2 bg-light border-top">
                                                <div
                                                    class="form-check d-flex justify-content-center align-items-center">
                                                    <input class="form-check-input me-2" type="checkbox"
                                                        name="remove_attachments[]" value="{{ $attachment->id }}"
                                                        id="del-{{ $attachment->id }}">
                                                    <label class="form-check-label text-danger fw-bold"
                                                        style="font-size: 0.8rem;" for="del-{{ $attachment->id }}">
                                                        Hapus
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="alert alert-warning py-2 shadow-sm" style="border-left: 5px solid #ffc107;">
                                <small class="mb-0"><i class="fas fa-info-circle me-1"></i> Centang "Hapus" pada
                                    gambar yang ingin dibuang.</small>
                            </div>
                        @endif

                        {{-- BAGIAN INPUT FILE SETENGAH LEBAR --}}
                        <div class="row">
                            <div class="col-md-6"> {{-- Ini yang membuatnya menjadi setengah (6 dari 12 kolom) --}}
                                <label class="form-label mb-1 fw-bold">Tambah Lampiran Baru:</label>
                                <input type="file" name="attachments[]"
                                    class="form-control shadow-sm rounded-pill" accept="image/*" multiple>
                                <small class="text-info d-block mt-1">
                                    <i class="fas fa-images me-1"></i> Bisa pilih lebih dari satu gambar sekaligus.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- TOMBOL SUBMIT --}}
                    <div class="mb-4 d-flex justify-content-end gap-3">
                        <a href="javascript:void(0)" data-url="{{ route('transaction-workPlace.index') }}"
                            class="btn btn-cancel fw-bold text-white rounded-pill shadow d-inline-flex align-items-center justify-content-center"
                            style="background-color: #00ffff; color: white !important; border: none; min-width: 120px; height: 40px;">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-submit px-4">
                            <i class="fas fa-save me-1"></i> Submit
                        </button>
                    </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                $('#search, #search_lok, #pto_area, #lok_pemeriksaan, #dept, #pic_user').select2({
                    width: '100%',
                });
            });
            document.querySelector('.btn-cancel').addEventListener('click', function(e) {
                let targetUrl = this.getAttribute('data-url');

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang sudah diinput tidak akan tersimpan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Tidak',
                    customClass: {
                        popup: 'rounded-5',
                        confirmButton: 'rounded-pill',
                        cancelButton: 'rounded-pill'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = targetUrl;
                    }
                });
            });

            function updateSelectColor(el) {
                if (!el) return;
                const value = el.value;

                // Reset ke default (Abu-abu muda)
                el.style.setProperty('background-color', '#f8f9fa', 'important');
                el.style.setProperty('color', '#6c757d', 'important');
                el.style.borderColor = '#dee2e6';

                if (value === 'Y') {
                    el.style.setProperty('background-color', '#28a745', 'important');
                    el.style.setProperty('color', '#ffffff', 'important');
                    el.style.borderColor = '#28a745';
                } else if (value === 'N') {
                    el.style.setProperty('background-color', '#dc3545', 'important');
                    el.style.setProperty('color', '#ffffff', 'important');
                    el.style.borderColor = '#dc3545';
                } else if (value === 'NA') {
                    el.style.setProperty('background-color', '#6c757d', 'important');
                    el.style.setProperty('color', '#ffffff', 'important');
                    el.style.borderColor = '#6c757d';
                }
            }

            /**
             * 2. INITIALIZATION
             */
            document.addEventListener('DOMContentLoaded', function() {
                const selectors = 'select.status-color-control';
                document.querySelectorAll(selectors).forEach(select => {
                    updateSelectColor(select);
                    select.addEventListener('change', function() {
                        updateSelectColor(this);
                    });
                });
            });
            const btnAddRow = document.getElementById('btn-add-row');
            if (btnAddRow) {
                btnAddRow.addEventListener('click', function() {
                    const tbody = document.getElementById('team-table-body');
                    const rowCount = tbody.querySelectorAll('.team-row').length;
                    const newRowHtml = `
                <tr class="border-bottom d-block pb-2 mb-3 bg-transparent team-row">
                    <td class="d-block p-0 border-0 bg-transparent">
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="fw-bold mb-1 small text-muted">Nama Lengkap</label>
                                <select name="members[${rowCount}][user_id]" class="form-select shadow-sm rounded-pill text-secondary border-2 bg-transparent">
                                    <option value="">-- Pilih Anggota --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold mb-1 small text-muted">Role</label>
                                <input type="text" name="members[${rowCount}][role]" class="form-control shadow-sm rounded-pill text-secondary border-2 bg-transparent" placeholder="Role">
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold mb-1 small text-muted">Department</label>
                                <select name="members[${rowCount}][department]" class="form-select shadow-sm rounded-pill text-secondary border-2 bg-transparent">
                                    <option value="">-- Pilih Dept --</option>
                                    @foreach ($dept as $d)
                                        <option value="{{ $d->name }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end pt-2">
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill btn-remove-row">
                                <i class="fas fa-trash-alt me-1"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>`;
                    const emptyRow = document.getElementById('empty-row');
                    if (emptyRow) emptyRow.remove();

                    tbody.insertAdjacentHTML('beforeend', newRowHtml);
                });
            }
            const btnAddFinding = document.getElementById('btn-add-finding');
            if (btnAddFinding) {
                btnAddFinding.addEventListener('click', function() {
                    const tbody = document.getElementById('finding-table').querySelector('tbody');
                    const rowCount = tbody.rows.length;
                    const newRow = tbody.rows[0].cloneNode(true);
                    newRow.querySelectorAll('textarea, select').forEach(el => {
                        el.value = '';
                        if (el.tagName === 'TEXTAREA') el.setAttribute('name', `findings[${rowCount}][name]`);
                        if (el.tagName === 'SELECT') {
                            el.setAttribute('name', `findings[${rowCount}][status]`);
                            updateSelectColor(el); // Reset warna ke default
                            el.addEventListener('change', function() {
                                updateSelectColor(this);
                            });
                        }
                    });

                    tbody.appendChild(newRow);
                });
            }
            const btnAddTL = document.getElementById('btn-add-tindakLanjut');
            if (btnAddTL) {
                btnAddTL.addEventListener('click', function() {
                    const tbody = document.getElementById('tindak_lanjut').querySelector('tbody');
                    const rowCount = tbody.rows.length;
                    const newRow = tbody.rows[0].cloneNode(true);

                    newRow.querySelectorAll('textarea, input, select').forEach((el) => {
                        let nameAttr = el.getAttribute('name');
                        if (nameAttr) {
                            let parts = nameAttr.split('][');
                            let baseName = parts[parts.length - 1]; // Mengambil 'status]' atau 'name]'
                            el.setAttribute('name', `actions[${rowCount}][${baseName}`);
                        }
                        if (el.tagName === 'SELECT' && el.name.includes('[status]')) {
                            el.selectedIndex = 0;
                        } else {
                            el.value = '';
                        }
                        el.classList.remove('is-invalid');
                    });

                    tbody.appendChild(newRow);
                });
            }
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-remove-row, .btn-remove-finding');
                if (btn) {
                    const tr = btn.closest('tr');
                    const tbody = tr.parentElement;
                    if (tbody.querySelectorAll('tr').length > 1 || tr.classList.contains('team-row')) {
                        if (confirm('Apakah Anda yakin ingin menghapus baris ini?')) {
                            tr.remove();
                            if (tbody.id === 'team-table-body' && tbody.querySelectorAll('.team-row').length === 0) {
                                tbody.innerHTML =
                                    `<tr id="empty-row"><td class="text-center text-muted py-4">Belum ada anggota. Klik "Add new line".</td></tr>`;
                            }
                        }
                    } else {
                        alert('Minimal harus ada satu baris tersisa.');
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
