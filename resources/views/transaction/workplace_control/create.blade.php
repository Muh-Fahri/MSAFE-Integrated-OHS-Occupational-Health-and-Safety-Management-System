<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <a href="{{ route('transaction-workPlace.index') }}" class="btn fs-4 btn-back btn-xl">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Add New Workplace Control</h4>
            </div>
        </div>
    </div>

    <div class="card rounded-5">
        <div class="card-body m-5">
            <div class="row">
                <div class="col-12">
                    @if ($errors->any())
                        <div id="error-section" class="card border-0 shadow-sm rounded-4 mb-4"
                            style="border-left: 5px solid #dc3545 !important;">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                    <h6 class="fw-bold text-danger mb-0">Mohon Periksa Kembali:</h6>
                                </div>
                                <ul class="mb-0 text-secondary small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const errorElement = document.getElementById('error-section');
                                if (errorElement) {
                                    errorElement.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                                }
                            });
                        </script>
                    @endif
                    <form action="{{ route('transaction-workPlace.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select class="form-select rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    onchange="window.location='?type='+this.value">
                                    @foreach ($type_sel as $t)
                                        <option value="{{ $t }}" {{ $type == $t ? 'selected' : '' }}>
                                            {{ str_replace('_', ' ', $t) }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- Hidden input agar saat disubmit (POST), tipenya ikut terkirim ke fungsi store --}}
                                <input type="hidden" name="type" value="{{ $type }}">
                            </div>
                        </div>

                        @if ($type === 'INSPECTION_VEHICLE')
                            <div class="row">
                                <div class="card w-100 mb-4">
                                    <div class="card-body p-0">
                                        <div class="row g-0">
                                            <div class="col-md-6 border-end">
                                                <div class="d-flex border-bottom">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Driver / Operator</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="text" name="operator_name"
                                                            class="form-control rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            placeholder="Nama Driver" required>
                                                    </div>
                                                </div>

                                                {{-- Department / Perusahaan --}}
                                                <div class="d-flex border-bottom">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Department / Perusahaan</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <select name="department_id"
                                                            class="form-select rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            required id="dept_select">
                                                            <option value="">Select Department</option>
                                                            @foreach ($dept as $depart)
                                                                <option value="{{ $depart->id }}">
                                                                    {{ $depart->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Lokasi Pemeriksaan --}}
                                                <div class="d-flex">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Lokasi Pemeriksaan</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <select name="location"
                                                            class="form-select rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            required>
                                                            <option value="">Select Location</option>
                                                            @foreach ($location as $loc)
                                                                <option value="{{ $loc->name }}">
                                                                    {{ $loc->name }}
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
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Tanggal Pemeriksaan</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="date" name="date"
                                                            class="form-control rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            required>
                                                    </div>
                                                </div>

                                                {{-- ID Kendaraan --}}
                                                <div class="d-flex border-bottom">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>ID Kendaraan</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="text" name="vehicle_code"
                                                            class="form-control rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            placeholder="Contoh: DT-102" required>
                                                    </div>
                                                </div>

                                                {{-- Jenis Kendaraan --}}
                                                <div class="d-flex">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Jenis Kendaraan</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <select class="form-select rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            name="vehicle_type" required>
                                                            <option value="">Select Type</option>
                                                            <option value="LV">Light Vehicle (LV)</option>
                                                            <option value="Bus">Bus</option>
                                                            <option value="Truck">Truck</option>
                                                            <option value="Heavy Equipment">Heavy Equipment
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Jenis Bangunan</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="text"
                                                            name="building_type"class="form-control rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            placeholder="Contoh: Kantor / Gudang / Mess" required>
                                                    </div>
                                                </div>

                                                {{-- Department / Perusahaan --}}
                                                <div class="d-flex">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Department / Perusahaan</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <select name="department_id"
                                                            class="form-select rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            required id="dept_select">
                                                            <option value="">Pilih Department</option>
                                                            @foreach ($dept as $depart)
                                                                <option value="{{ $depart->id }}">
                                                                    {{ $depart->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- --- KOLOM KANAN --- --}}
                                            <div class="col-md-6">
                                                {{-- Tanggal Pemeriksaan --}}
                                                <div class="d-flex border-bottom">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Tanggal Pemeriksaan</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="date" name="date"
                                                            class="form-control rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            class="form-control" required
                                                            value="{{ date('Y-m-d') }}">
                                                    </div>
                                                </div>

                                                {{-- Lokasi Pemeriksaan --}}
                                                <div class="d-flex">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Lokasi Pemeriksaan</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <select name="location"
                                                            class="form-select rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            required>
                                                            <option value="">Pilih Lokasi</option>
                                                            @foreach ($location as $loc)
                                                                <option value="{{ $loc->name }}">
                                                                    {{ $loc->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
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
                                        <div class="row g-0">
                                            {{-- --- KOLOM KIRI --- --}}
                                            <div class="d-flex border-bottom">
                                                <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                    <strong>Lokasi Pemeriksaan</strong>
                                                </div>
                                                <div class="px-3 py-2 flex-grow-1">
                                                    <select name="location"
                                                        class="form-select rounded-pill text-secondary border-2"
                                                        style="border-color: #dee2e6; padding-left: 1.5rem;" required id="select_lok">
                                                        <option value="">Select Lokasi</option>
                                                        @foreach ($location as $loc)
                                                            <option value="{{ $loc->name }}">
                                                                {{ $loc->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Department / Perusahaan --}}
                                            <div class="d-flex">
                                                <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                    <strong>Department / Perusahaan</strong>
                                                </div>
                                                <div class="px-3 py-2 flex-grow-1">
                                                    <select name="department_id"
                                                        class="form-select rounded-pill text-secondary border-2"
                                                        style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                                                        <option value="">Select Department ---</option>
                                                        @foreach ($dept as $depart)
                                                            <option value="{{ $depart->id }}">
                                                                {{ $depart->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                    <strong>Tanggal Pemeriksaan</strong>
                                                </div>
                                                <div class="px-3 py-2 flex-grow-1">
                                                    <input type="date" name="date"
                                                        class="form-control rounded-pill text-secondary border-2"
                                                        style="border-color: #dee2e6; padding-left: 1.5rem;" required
                                                        value="{{ date('Y-m-d') }}">
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
                                            <div class="col-md-6 border-end">
                                                <div class="d-flex border-bottom">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Nama Site</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="text" name="site"
                                                            class="form-control bg-light rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            value="Awak Mas" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex border-bottom">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Tanggal Observasi</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="date" name="date"
                                                            class="form-control rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex border-top">
                                            <div class="bg-light px-3 py-3 border-end" style="width:22.5%;">
                                                <strong>Area kerja / lokasi</strong>
                                            </div>
                                            <div class="px-3 py-2 flex-grow-1">
                                                <select name="location"
                                                    class="form-select rounded-pill text-secondary border-2"
                                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                    required>
                                                    <option value="">Select Area</option>
                                                    @foreach ($location as $loc)
                                                        <option value="{{ $loc->name }}">
                                                            {{ $loc->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex border-top">
                                            <div class="bg-light px-3 py-3 border-end" style="width:22.5%;">
                                                <strong>Aktivitas Dilakukan</strong>
                                            </div>
                                            <div class="px-3 py-2 flex-grow-1">
                                                <input type="text" name="activity"
                                                    class="form-control rounded-pill text-secondary border-2"
                                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                    placeholder="Contoh: Pengangkatan Material" required>
                                            </div>
                                        </div>
                                        <div class="row g-0">
                                            <div class="col-md-6 border-end">
                                                <div class="d-flex border-bottom">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Siapa yang melakukan aktivitas ?</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <select name="activity_company"
                                                            class="form-select rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            required>
                                                            @foreach ($list_activity_company as $key => $value)
                                                                <option value="{{ $key }}">{{ $value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex border-bottom">
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="text" name="activity_person"
                                                            class="form-control rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            placeholder="Nama Personel" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-0">
                                            <div class="col-md-6 border-end">
                                                <div class="d-flex border-bottom">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Jumlah Pekerja</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="number" name="employee_count"
                                                            class="form-control rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            min="1" placeholder="0" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex border-bottom">
                                                    <div class="bg-light px-3 py-3 border-end" style="width:45%;">
                                                        <strong>Pengawas area</strong>
                                                    </div>
                                                    <div class="px-3 py-2 flex-grow-1">
                                                        <input type="text" name="area_supervisor"
                                                            class="form-control rounded-pill text-secondary border-2"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            placeholder="Nama Pengawas Area" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- --- BARIS BAWAH (PROSEDUR JSA KERJA TERKAIT) --- --}}
                                        <div class="d-flex border-top">
                                            <div class="bg-light px-3 py-3 border-end" style="width:22.5%;">
                                                <strong>Prosedur / JSA Terkait</strong>
                                            </div>
                                            <div class="px-3 py-2 flex-grow-1">
                                                <input type="text" name="procedure"
                                                    class="form-control rounded-pill text-secondary border-2"
                                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                    placeholder="Masukkan Nomor JSA atau Judul Prosedur" required>
                                            </div>
                                        </div>
                                        <div class="d-flex border-top">
                                            <div class="bg-light px-3 py-3 border-end" style="width:22.5%;">
                                                <strong>Alasan Observasi</strong>
                                            </div>
                                            <div class="px-3 py-2 flex-grow-1">
                                                <select 
                                                name="observation_reason[]" 
                                                id="observation_reason"
                                                class="select2 form-control select2-multiple rounded-pill text-secondary border-2" 
                                                multiple="multiple">
                                                    @foreach ($list_observation_reason as $obs_reason)
                                                        <option value="{{ $obs_reason }}">{{ $obs_reason }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif
                        {{--  ========================================= --}}

                        {{-- TIM PEMERIKSA --}}
                        <div class="mb-3 mt-5">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="fw-bold mb-0 text-secondary">
                                    Tim Pemeriksa / Penilai
                                </h5>
                            </div>
                            <div class="mt-3">
                                <table class="table table-borderless bg-transparent" id="team-table">
                                    <tbody class="bg-transparent">
                                        <tr class="border-bottom d-block pb-2 mb-3 bg-transparent">
                                            <td class="d-block p-0 border-0 bg-transparent">
                                                <div class="row g-2 mb-2">
                                                    <div class="col-md-4">
                                                        <label class="fw-bold mb-1">Nama Lengkap</label>
                                                        <select
                                                            class="form-select shadow-sm rounded-pill text-secondary border-2 bg-transparent js-team-name"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            name="members[0][name]" required>
                                                            <option value="">Select to</option>
                                                            @foreach ($users as $u)
                                                                <option value="{{ $u->name }}"
                                                                    {{ old('members.0.name') == $u->name ? 'selected' : '' }}>
                                                                    {{ $u->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="fw-bold mb-1">Role</label>
                                                        <input type="text"
                                                            class="form-control shadow-sm rounded-pill text-secondary border-2 bg-transparent"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            name="members[0][role]" placeholder="Role">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="fw-bold mb-1">Department</label>
                                                        <select
                                                            class="form-select shadow-sm rounded-pill text-secondary border-2 bg-transparent js-team-department"
                                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                            name="members[0][department]" required>
                                                            <option value="">Select Department</option>
                                                            @foreach ($dept as $d)
                                                                <option value="{{ $d->name }}"
                                                                    {{ old('members.0.department') == $d->name ? 'selected' : '' }}>
                                                                    {{ $d->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end pt-2">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger rounded-pill btn-remove-finding">
                                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="mt-3">
                                    <button type="button" class="btn btn-sm btn-addRow" id="btn-add-row">
                                        Add new line
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-5 mb-2">
                            <h5 class="fw-bold mb-0 text-secondary">
                                Pemeriksaan Item
                            </h5>
                        </div>
                        <table class="table table-borderless table-sm mt-4 mb-0 bg-transparent">
                            <thead class="bg-transparent">
                                <tr class="border-0">
                                    <th class="bg-light border-0 py-3 text-dark text-center align-middle"
                                        style="width: 50px;">No</th>
                                    <th class="bg-light border-0 py-3 text-dark align-middle">Item Pemeriksaan
                                    </th>
                                    <th class="bg-light border-0 py-3 text-dark text-center align-middle"
                                        style="width: 150px;">Hasil</th>
                                    <th class="bg-light border-0 py-3 text-dark align-middle">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-transparent">
                                @foreach ($checking_items as $index => $item)
                                    <tr class="bg-transparent">
                                        <td class="bg-transparent border-start border-end text-center align-middle">
                                            {{ $loop->iteration }}</td>

                                        <td class="bg-transparent border-end align-middle fw-bold text-secondary">
                                            {{ $item->name }}
                                            <input type="hidden" name="items[{{ $index }}][checking_item_id]"
                                                value="{{ $item->id }}">
                                        </td>

                                        <td class="bg-transparent border-end align-middle px-2">
                                            <select name="items[{{ $index }}][result]"
                                                class="form-select rounded-pill text-dark border-2 bg-light status-color-control"
                                                style="border-color: #dee2e6; font-size: 0.9rem; transition: all 0.3s ease;">
                                                <option value="">N/A</option>
                                                <option value="Y">Y</option>
                                                <option value="N">N</option>
                                            </select>
                                        </td>
                                        <td class="bg-transparent border-end align-middle px-2">
                                            <textarea name="items[{{ $index }}][remarks]" class="form-control rounded-3 bg-light text-dark border-2"
                                                rows="1" style="border-color: #dee2e6; font-size: 0.9rem; min-height: 38px;"></textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- TEMUAN DI LUAR LIST --}}
                        <div class="mt-5 mb-2">
                            <h5 class="mb-3 fw-bold text-secondary">Temuan yang tidak terdapat pada daftar periksa di
                                atas</h5>
                        </div>

                        <div id="finding-table">
                            <div class="w-100">
                                <table class="table table-borderless mb-0 bg-transparent">
                                    <tbody id="findings-container" class="bg-transparent">
                                        <tr class="bg-transparent">
                                            <td class="p-0 pb-3 border-0 bg-transparent">
                                                <div class="row g-2">
                                                    <div class="col-md-7">
                                                        <label
                                                            class="text-secondary small fw-bold ms-2 mb-1">Temuan</label>
                                                        <textarea name="findings[0][name]" class="form-control shadow-sm border-0 px-4 py-2"
                                                            style="border-radius: 30px; min-height: 45px; resize: none;" rows="1"
                                                            placeholder="Tulis temuan di sini..."></textarea>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label
                                                            class="text-secondary small ms-2 fw-bold mb-1">Hasil</label>
                                                        <select name="findings[0][status]"
                                                            class="form-select shadow-sm border-0 px-4"
                                                            style="border-radius: 30px; height: 45px;">
                                                            <option value="" selected disabled>Pilih</option>
                                                            <option value="Temuan Positif">Temuan Positif</option>
                                                            <option value="Perlu Tindakan Perbaikan">Perlu Tindakan
                                                                Perbaikan</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-1 d-flex flex-column">
                                                        <label class="small mb-1"
                                                            style="color: transparent; user-select: none;">Aksi</label>

                                                        <div
                                                            class="d-flex align-items-center justify-content-center flex-grow-1">
                                                            <button type="button"
                                                                class="btn btn-link text-danger btn-remove-finding p-0 mt-1"
                                                                style="line-height: 1;" title="Hapus">
                                                                <i class="fas fa-times-circle fa-lg"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="mt-2">
                                    <button type="button"
                                        class="btn btn-sm btn-light border text-muted rounded-pill px-4 shadow-sm"
                                        style="background-color: #f0f0f0; font-size: 0.8rem;" id="btn-add-finding">
                                        Add new line
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- TINDAK LANJUT TEMUAN --}}
                        <div class="container-fluid mt-5">
                            <div class="d-flex justify-content-between align-items-center mb-4 ps-2">
                                <h5 class="fw-bold mb-0 text-secondary">
                                    Tindak Lanjut Temuan
                                </h5>
                            </div>

                            <div class="table-responsive" style="overflow: visible;">
                                <table class="table table-borderless align-middle" id="tindak_lanjut">
                                    <thead>
                                        <tr class="text-secondary small">
                                            <th class="border-0 ps-3 text-muted fw-bold" style="width: 25%;">Tindakan
                                                langsung untuk temuan</th>
                                            <th class="border-0 text-muted fw-bold" style="width: 15%;">Kategori
                                                Temuan</th>
                                            <th class="border-0 text-muted fw-bold" style="width: 15%;">Status</th>
                                            <th class="border-0 text-muted fw-bold" style="width: 20%;">PIC</th>
                                            <th class="border-0 text-muted fw-bold" style="width: 15%;">Batas Waktu
                                            </th>
                                            <th class="border-0 text-center text-muted fw-bold" style="width: 50px;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        <tr>
                                            <td class="border-0 py-2">
                                                <textarea name="actions[0][name]" class="form-control border-0 shadow-sm px-3" rows="1"
                                                    style="resize: none; border-radius: 30px; min-height: 45px; background-color: #fff;"></textarea>
                                            </td>
                                            <td class="border-0 py-2">
                                                <select name="actions[0][category]"
                                                    class="form-select border-0 shadow-sm ps-4 pe-5"
                                                    style="border-radius: 30px; height: 45px; background-color: #fff; font-size: 0.9rem; color: #6c757d;">

                                                    <option value="" selected disabled>Pilih Kategori...</option>

                                                    <option value="Kondisi Tidak Aman" class="text-dark">Kondisi Tidak
                                                        Aman</option>
                                                    <option value="Perilaku Tidak Aman" class="text-dark">Perilaku
                                                        Tidak Aman</option>
                                                </select>
                                            </td>
                                            <td class="border-0 py-2">
                                                <select name="actions[0][status]"
                                                    class="form-select border-0 shadow-sm px-3"
                                                    style="border-radius: 30px; height: 45px; font-size: 0.85rem; background-color: #fff;">
                                                    <option value="Open">Open</option>
                                                    <option value="Closed">Closed</option>
                                                </select>
                                            </td>
                                            <td class="border-0 py-2">
                                                <select name="actions[0][assignee_id]"
                                                    class="form-select border-0 shadow-sm px-3 js-action-assignee"
                                                    style="border-radius: 30px; height: 45px; background-color: #fff;">
                                                    <option value="" selected disabled>
                                                        <Pilih class=""></Pilih>
                                                    </option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="border-0 py-2">
                                                <input type="date" name="actions[0][due_date]"
                                                    class="form-control border-0 shadow-sm px-3"
                                                    style="border-radius: 30px; height: 45px; background-color: #fff;">
                                            </td>
                                            <td class="border-0 py-2 text-center" style="vertical-align: middle;">
                                                <div class="d-inline-flex align-items-center justify-content-center"
                                                    style="height: 45px; width: 45px;">
                                                    <button type="button"
                                                        class="btn btn-link text-danger p-0 btn-remove-finding"
                                                        style="line-height: 1; text-decoration: none;"
                                                        title="Hapus Baris">
                                                        <i class="fas fa-times-circle fa-lg"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="ps-2 mt-2">
                                    <button type="button" id="btn-add-tindakLanjut"
                                        class="btn btn-sm rounded-pill px-4 shadow-sm border"
                                        style="background-color: #efefef; color: #6c757d; font-size: 0.75rem; font-weight: 500;">
                                        Add new line
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- LAMPIRAN --}}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mt-4 mb-4">
                                    <p class="mb-1">Lampiran</p>
                                    <input type="file" name="attachments[]"
                                        class="form-control rounded-pill shadow" accept="image/*" multiple>
                                </div>
                            </div>
                        </div>

                        {{-- TOMBOL SUBMIT --}}
                        <div class="mb-5">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="javascript:void(0)" data-url="{{ route('transaction-workPlace.index') }}"
                                    class="btn btn-cancel fw-bold text-white rounded-pill shadow d-inline-flex align-items-center justify-content-center"
                                    style="background-color: #00ffff; color: white !important; border: none; min-width: 120px; height: 40px;">
                                    Cancel
                                </a>
                                <button type="submit" class="btn shadow btn-primary btn-submit rounded-pill"
                                    style="min-width: 120px;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .select2-selection__choice__display {
                padding-right: 6px !important;
            }
            .select2-selection__choice {
                border-radius: 20px !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                $('#dept_select, #select_lok, .js-team-name, .js-action-assignee, .js-team-department, #observation_reason').select2({
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

            function updateSelectColor(sel) {
                sel.classList.remove('select-y', 'select-n', 'select-na');
                if (sel.value === 'Y') {
                    sel.classList.add('select-y');
                } else if (sel.value === 'N') {
                    sel.classList.add('select-n');
                } else {
                    sel.classList.add('select-na');
                }
            }
            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('status-color-control')) {
                    updateSelectColor(e.target);
                }
            });
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.status-color-control').forEach(el => {
                    updateSelectColor(el);
                });
            });
            document.getElementById('btn-add-row').addEventListener('click', function() {
                $('.js-team-name, .js-team-department').select2('destroy');
                const table = document.getElementById('team-table').querySelector('tbody');
                const rowCount = table.rows.length;
                const newRow = table.rows[0].cloneNode(true);
                newRow.querySelectorAll('input, select').forEach((el) => {
                    if (el.tagName === 'SELECT') {
                        if (el.name.includes('[status]')) {
                            el.selectedIndex = 0;
                        } else {
                            el.value = '';
                        }
                    } else {
                        el.value = '';
                    }
                    let name = el.getAttribute('name');
                    if (name) {
                        el.setAttribute('name', name.replace(/\[\d+\]/, `[${rowCount}]`));
                    }
                });
                table.appendChild(newRow);
                $('.js-team-name, .js-team-department').select2({
                    width: '100%',
                });
            });
            document.getElementById('btn-add-finding').addEventListener('click', function() {
                const table = document.getElementById('finding-table').querySelector('tbody');
                const rowCount = table.rows.length;
                const newRow = table.rows[0].cloneNode(true);
                const txt = newRow.querySelector('textarea');
                txt.value = '';
                txt.setAttribute('name', `findings[${rowCount}][name]`);
                const sel = newRow.querySelector('select');
                sel.value = '';
                sel.setAttribute('name', `findings[${rowCount}][status]`);
                updateSelectColor(sel);
                table.appendChild(newRow);
            });
            document.getElementById('btn-add-tindakLanjut').addEventListener('click', function() {
                $('.js-action-assignee').select2('destroy');
                const table = document.getElementById('tindak_lanjut').querySelector('tbody');
                const rowCount = table.rows.length;
                const newRow = table.rows[0].cloneNode(true);
                newRow.querySelectorAll('textarea, input, select').forEach((el) => {
                    if (el.tagName === 'SELECT') {
                        if (el.name.includes('[status]')) {
                            el.selectedIndex = 0;
                        } else {
                            el.value = '';
                        }
                    } else {
                        el.value = '';
                    }
                    let name = el.getAttribute('name');
                    if (name) {
                        el.setAttribute('name', name.replace(/\[\d+\]/, `[${rowCount}]`));
                    }
                });
                const statusSelect = newRow.querySelector('.status-color-control');
                if (statusSelect) updateSelectColor(statusSelect);
                table.appendChild(newRow);
                $('.js-action-assignee').select2({
                    width: '100%',
                });
            });
            document.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.btn-remove-finding');
                if (removeBtn) {
                    const tr = removeBtn.closest('tr');
                    const tbody = tr.parentElement;
                    if (tbody.rows.length > 1) {
                        tr.remove();
                    } else {
                        alert("Minimal harus ada satu data.");
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
