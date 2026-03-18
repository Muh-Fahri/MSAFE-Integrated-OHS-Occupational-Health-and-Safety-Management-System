<x-app-layout>
    <style>
        .section-header {
            background-color: #d4a017;
            color: #000;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 15px 15px 0 0;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .btn-submit {
            background-color: #d4a017;
            color: white;
            border-radius: 50px;
            padding: 10px 30px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background-color: #b88a14;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background-color: #f8f9fa;
            color: #6c757d;
            border-radius: 50px;
            padding: 10px 30px;
            font-weight: 600;
            border: 1px solid #dee2e6;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .table thead {
            background-color: #fff9e6;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('transaction-monthly-reports.index') }}" class="text-dark fs-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="mb-0 fw-bold text-secondary">Monthly Report Contractor</h4>
        </div>

        <div class="bg-white shadow-sm mb-5" style="border-radius: 25px; overflow: hidden; border: 1px solid #dee2e6;">
            <div class="section-header"
                style="background-color: #d4a017; color: #000; font-weight: 700; padding: 12px 20px; text-transform: uppercase; font-size: 0.85rem;">
                A. INFORMASI UMUM
            </div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">PERUSAHAAN</label>
                        <select name="company_id" class="form-control rounded-pill border-2 shadow-none bg-light"
                            disabled>
                            <option value="">Pilih Perusahaan...</option>
                            @foreach ($comp as $c)
                                <option value="{{ $c->id }}"
                                    {{ $report->company_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NAMA PJO</label>
                        <input type="text" name="operational_person_name"
                            class="form-control rounded-pill border-2 shadow-none bg-light" disabled
                            value="{{ $report->operational_person_name }}" placeholder="Masukkan nama PJO / Perusahaan">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">BULAN / TAHUN</label>
                        <input type="month" name="report_date"
                            class="form-control rounded-pill border-2 shadow-none bg-light" disabled
                            value="{{ $report->report_date ? $report->report_date->format('Y-m') : '' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NO LAPORAN</label>
                        <input type="text" name="report_no"
                            class="form-control rounded-pill border-2 shadow-none bg-light" disabled
                            value="{{ $report->report_no }}" placeholder="Masukkan Nomor">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">BIDANG USAHA</label>
                        <input type="text" name="business_field"
                            class="form-control rounded-pill border-2 shadow-none bg-light" disabled
                            value="{{ $report->business_field }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">TGL TERBIT</label>
                        <input type="date" name="issue_date"
                            class="form-control rounded-pill border-2 shadow-none bg-light" disabled
                            value="{{ $report->issue_date ? $report->issue_date->format('Y-m-d') : '' }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">TGL BERAKHIR</label>
                        <input type="date" name="expiry_date"
                            class="form-control rounded-pill border-2 shadow-none bg-light" disabled
                            value="{{ $report->expiry_date ? $report->expiry_date->format('Y-m-d') : '' }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm mb-5" style="border-radius: 25px; overflow: hidden; border: 1px solid #dee2e6;">
            <div class="section-header">B. JUMLAH & JAM KERJA KARYAWAN</div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h6 class="fw-bold mb-3"><i class="fas fa-hard-hat me-2 text-warning"></i>B1. Kontraktor
                        </h6>
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="text-center">
                                <tr>
                                    <th>Kategori</th>
                                    <th width="100px">Orang</th>
                                    <th width="100px">Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-3">Operasional</td>
                                    <td>
                                        <input type="number" name="operational_employee_total"
                                            class="form-control form-control-sm border-0 text-center count-emp bg-light"
                                            disabled value="{{ $report->operational_employee_total ?? 0 }}">
                                    </td>
                                    <td>
                                        <input type="number" name="operational_hours"
                                            class="form-control form-control-sm border-0 text-center count-hours bg-light"
                                            disabled value="{{ $report->operational_hours ?? 0 }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3">Administrasi</td>
                                    <td>
                                        <input type="number" name="administration_operational_total"
                                            class="form-control form-control-sm border-0 text-center count-emp bg-light"
                                            disabled value="{{ $report->administration_operational_total ?? 0 }}">
                                    </td>
                                    <td>
                                        <input type="number" name="administration_hours"
                                            class="form-control form-control-sm border-0 text-center count-hours bg-light"
                                            disabled value="{{ $report->administration_hours ?? 0 }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3">Pengawas</td>
                                    <td>
                                        <input type="number" name="supervision_employee_total"
                                            class="form-control form-control-sm border-0 text-center count-emp bg-light"
                                            disabled value="{{ $report->supervision_employee_total ?? 0 }}">
                                    </td>
                                    <td>
                                        <input type="number" name="supervision_hours"
                                            class="form-control form-control-sm border-0 text-center count-hours bg-light"
                                            disabled value="{{ $report->supervision_hours ?? 0 }}">
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td class="ps-3">Total</td>
                                    <td class="text-center" id="total_emp_b1">
                                        {{ ($report->operational_employee_total ?? 0) + ($report->administration_operational_total ?? 0) + ($report->supervision_employee_total ?? 0) }}
                                    </td>
                                    <td class="text-center" id="total_hours_b1">
                                        {{ ($report->operational_hours ?? 0) + ($report->administration_hours ?? 0) + ($report->supervision_hours ?? 0) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="col-lg-6">
                        <h6 class="fw-bold mb-3"><i class="fas fa-users-cog me-2 text-warning"></i>B2.
                            Sub-Kontraktor</h6>
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="text-center">
                                <tr>
                                    <th>Kategori</th>
                                    <th width="100px">Orang</th>
                                    <th width="100px">Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-3">Operasional</td>
                                    <td>
                                        <input type="number" name="subcon_operational_employee_total"
                                            class="form-control form-control-sm border-0 text-center count-emp bg-light"
                                            disabled value="{{ $report->subcon_operational_employee_total ?? 0 }}">
                                    </td>
                                    <td>
                                        <input type="number" name="subcon_operational_hours"
                                            class="form-control form-control-sm border-0 text-center count-hours bg-light"
                                            disabled value="{{ $report->subcon_operational_hours ?? 0 }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3">Administrasi</td>
                                    <td>
                                        <input type="number" name="subcon_admin_total"
                                            class="form-control form-control-sm border-0 text-center count-emp bg-light"
                                            disabled value="{{ $report->subcon_admin_total ?? 0 }}">
                                    </td>
                                    <td>
                                        <input type="number" name="subcon_admin_hours"
                                            class="form-control form-control-sm border-0 text-center count-hours bg-light"
                                            disabled value="{{ $report->subcon_admin_hours ?? 0 }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3">Pengawas</td>
                                    <td>
                                        <input type="number" name="subcon_supervision_total"
                                            class="form-control form-control-sm border-0 text-center count-emp bg-light"
                                            disabled value="{{ $report->subcon_supervision_total ?? 0 }}">
                                    </td>
                                    <td>
                                        <input type="number" name="subcon_supervision_hours"
                                            class="form-control form-control-sm border-0 text-center count-hours bg-light"
                                            disabled value="{{ $report->subcon_supervision_hours ?? 0 }}">
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td class="ps-3">Total</td>
                                    <td class="text-center" id="total_emp_b2">
                                        {{ ($report->subcon_operational_employee_total ?? 0) + ($report->subcon_admin_total ?? 0) + ($report->subcon_supervision_total ?? 0) }}
                                    </td>
                                    <td class="text-center" id="total_hours_b2">
                                        {{ ($report->subcon_operational_hours ?? 0) + ($report->subcon_admin_hours ?? 0) + ($report->subcon_supervision_hours ?? 0) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="row g-3 mt-3 p-4 rounded-4" style="background-color: #fff9e6; border: 1px solid #ffeeba;">
                    <div class="col-12 mb-2">
                        <h5 class="fw-bold text-dark"><i class="fas fa-users me-2"></i>JUMLAH TENAGA KERJA</h5>
                    </div>

                    <div class="col-md-4">
                        <label class="small fw-bold text-muted">LOKAL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-2 border-end-0 rounded-start-pill">
                                <i class="fas fa-map-marker-alt text-warning"></i>
                            </span>
                            <input type="number" name="local_employee_total bg-light" disabled
                                class="form-control form-control-sm border-2 border-start-0 rounded-end-pill shadow-none"
                                value="{{ $report->local_employee_total ?? 0 }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="small fw-bold text-muted">NASIONAL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-2 border-end-0 rounded-start-pill">
                                <i class="fas fa-flag text-warning"></i>
                            </span>
                            <input type="number" name="national_employee_total"
                                class="form-control form-control-sm border-2 border-start-0 rounded-end-pill shadow-none bg-light"
                                disabled value="{{ $report->national_employee_total ?? 0 }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="small fw-bold text-muted">ASING</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-2 border-end-0 rounded-start-pill">
                                <i class="fas fa-globe text-warning"></i>
                            </span>
                            <input type="number" name="foreign_employee_total"
                                class="form-control form-control-sm border-2 border-start-0 rounded-end-pill shadow-none bg-light"
                                disabled value="{{ $report->foreign_employee_total ?? 0 }}">
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <hr class="text-warning opacity-25">
                        <label class="small fw-bold text-dark"><i class="fas fa-clock me-2"></i>WORKING HOURS
                            MACHINE</label>
                        <div class="input-group mt-1" style="max-width: 300px;">
                            <input type="number" step="0.1" name="machine_working_hours bg-light" disabled
                                id="machine_working_hours"
                                class="form-control form-control-lg rounded-pill border-2 shadow-none px-4"
                                value="{{ $report->machine_working_hours ?? 0 }}">
                            <span class="ms-2 d-flex align-items-center fw-bold text-muted">Hours</span>
                        </div>
                    </div>

                    <div class="row mt-4 g-3">
                        <div class="col-md-6">
                            <div class="p-3 border-start border-4 border-warning shadow-sm"
                                style="background-color: #fffaf0; border-radius: 15px;">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning rounded-circle p-2 me-3">
                                        <i class="fas fa-users text-white"></i>
                                    </div>
                                    <div>
                                        <small class="d-block text-uppercase fw-bold text-muted"
                                            style="font-size: 0.7rem;">Grand Total Karyawan</small>
                                        <h3 class="mb-0 fw-bold text-dark" id="grand_total_emp">
                                            {{ ($report->operational_employee_total ?? 0) + ($report->administration_operational_total ?? 0) + ($report->supervision_employee_total ?? 0) + ($report->subcon_operational_employee_total ?? 0) + ($report->subcon_admin_total ?? 0) + ($report->subcon_supervision_total ?? 0) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 border-start border-4 border-warning shadow-sm"
                                style="background-color: #fffaf0; border-radius: 15px;">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning rounded-circle p-2 me-3">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                    <div>
                                        <small class="d-block text-uppercase fw-bold text-muted"
                                            style="font-size: 0.7rem;">Grand Total Jam Kerja</small>
                                        <h3 class="mb-0 fw-bold text-dark" id="grand_total_hours">
                                            {{ ($report->operational_hours ?? 0) + ($report->administration_hours ?? 0) + ($report->supervision_hours ?? 0) + ($report->subcon_operational_hours ?? 0) + ($report->subcon_admin_hours ?? 0) + ($report->subcon_supervision_hours ?? 0) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm mb-5"
            style="border-radius: 25px; overflow: hidden; border: 1px solid #dee2e6;">
            <div class="section-header">C. KEGIATAN BERISIKO TINGGI</div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Bulan Lalu</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="text-center table-light">
                                    <tr>
                                        <th width="50px">No</th>
                                        <th>Aktifitas</th>
                                        <th width="50px"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-bulan-lalu">
                                    @php
                                        // Filter aktivitas yang tipenya PREV saja
                                        $prevActivities = $report->activities->where('type', 'PREV');
                                    @endphp

                                    @forelse ($prevActivities as $index => $act)
                                        <tr>
                                            <td class="text-center row-num">{{ $index + 1 }}</td>
                                            <td>
                                                <input type="text" name="prev_activity[]"
                                                    class="form-control border-0 shadow-none bg-light" disabled
                                                    value="{{ $act->activity }}" placeholder="Input aktifitas...">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center row-num">1</td>
                                            <td>
                                                <input type="text" name="prev_activity[]"
                                                    class="form-control border-0 shadow-none bg-light" disabled
                                                    placeholder="Input aktifitas...">
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Bulan Depan</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="text-center table-light">
                                    <tr>
                                        <th width="50px">No</th>
                                        <th>Aktifitas</th>
                                        <th width="50px"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-bulan-depan">
                                    @php
                                        $nextActivities = $report->activities->where('type', 'NEXT');
                                    @endphp

                                    @forelse ($nextActivities as $act)
                                        <tr>
                                            <td class="text-center row-num">{{ $loop->iteration }}</td>
                                            <td>
                                                <input type="text" name="next_activity[]"
                                                    class="form-control border-0 shadow-none bg-light" disabled
                                                    value="{{ $act->activity }}" placeholder="Input aktifitas...">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center row-num">1</td>
                                            <td>
                                                <input type="text" name="next_activity[]"
                                                    class="form-control border-0 shadow-none bg-light" disabled
                                                    placeholder="Input aktifitas...">
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm mb-5"
            style="border-radius: 25px; overflow: hidden; border: 1px solid #dee2e6;">
            <div class="section-header"
                style="background-color: #d4a017; color: #000; font-weight: 700; padding: 10px 20px; text-transform: uppercase; font-size: 0.9rem;">
                D. PENGELOLAAN BAHAN BERBAHAYA BERACUN (B3)
            </div>
            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="text-center" style="background-color: #fff9e6;">
                            <tr>
                                <th rowspan="2" class="align-middle">Nama Bahan</th>
                                <th colspan="4" class="py-2">Stok & Penggunaan</th>
                                <th rowspan="2" class="align-middle" width="100px">Satuan (Kg/Ltr)</th>
                                <th rowspan="2" class="align-middle d-none" width="50px"></th>
                            </tr>
                            <tr style="font-size: 0.8rem;">
                                <th width="120px">Sisa Bulan Sebelumnya</th>
                                <th width="120px">Penerimaan</th>
                                <th width="120px">Jumlah Pemakaian</th>
                                <th width="120px">Sisa Akhir Bulan</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-b3">
                            @forelse ($report->materials as $mat)
                                <tr>
                                    <td>
                                        <input type="text" name="name[]"
                                            class="form-control border-0 shadow-none bg-light"
                                            value="{{ $mat->name }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="materials_qty[]"
                                            class="form-control border-0 shadow-none text-center prev-stock bg-light"
                                            value="{{ $mat->materials_qty ?? 0 }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="received_qty[]"
                                            class="form-control border-0 shadow-none text-center received-qty bg-light"
                                            value="{{ $mat->received_qty ?? 0 }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="used_qty[]"
                                            class="form-control border-0 shadow-none text-center used-qty bg-light"
                                            value="{{ $mat->used_qty ?? 0 }}" readonly>
                                    </td>
                                    <td>
                                        <div
                                            class="bg-light text-center fw-bold last-stock-display py-2 border rounded">
                                            {{ ($mat->materials_qty ?? 0) + ($mat->received_qty ?? 0) - ($mat->used_qty ?? 0) }}
                                        </div>
                                        <input type="hidden" name="remaining_qty[]" class="last-stock-value"
                                            value="{{ ($mat->materials_qty ?? 0) + ($mat->received_qty ?? 0) - ($mat->used_qty ?? 0) }}">
                                    </td>
                                    <td>
                                        <select class="form-select bg-light" disabled>
                                            <option value="KG" {{ $mat->uom == 'KG' ? 'selected' : '' }}>KG
                                            </option>
                                            <option value="LTR" {{ $mat->uom == 'LTR' ? 'selected' : '' }}>Ltr
                                            </option>
                                        </select>
                                        <input type="hidden" name="uom[]" value="{{ $mat->uom }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted bg-light py-3">Tidak ada data
                                        bahan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="bg-white shadow-sm mb-5"
            style="border-radius: 25px; overflow: hidden; border: 1px solid #dee2e6;">
            <div class="section-header"
                style="background-color: #d4a017; color: #000; font-weight: 700; padding: 12px 20px; text-transform: uppercase; font-size: 0.85rem; line-height: 1.4;">
                E. RINGKASAN KEGIATAN / INSIDEN BULAN INI / ISU UTAMA DAN STATUS PENYELESAIANNYA
            </div>
            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="text-center" style="background-color: #fff9e6;">
                            <tr>
                                <th width="60px" class="py-3">No</th>
                                <th class="py-3">Ringkasan Insiden / Isu Utama</th>
                                <th width="250px" class="py-3">Status Penyelesaian</th>
                                <th width="50px" class="py-3"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-insiden">
                            @forelse ($report->incidents as $inc)
                                <tr>
                                    <td class="text-center fw-bold text-muted row-num">{{ $loop->iteration }}</td>
                                    <td>
                                        <textarea name="incident[]" class="form-control border-0 shadow-none bg-light" rows="2" readonly
                                            placeholder="Tuliskan ringkasan insiden..." readonly>{{ $inc->incident }}</textarea>
                                    </td>
                                    <td>
                                        <select class="form-select border-0 shadow-none bg-light" disabled>
                                            <option value="Open" {{ $inc->status == 'Open' ? 'selected' : '' }}>Open
                                            </option>
                                            <option value="In Progress"
                                                {{ $inc->status == 'In Progress' ? 'selected' : '' }}>In Progress
                                            </option>
                                            <option value="Closed" {{ $inc->status == 'Closed' ? 'selected' : '' }}>
                                                Closed</option>
                                        </select>
                                        <input type="hidden" name="status[]" value="{{ $inc->status }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center fw-bold text-muted row-num">1</td>
                                    <td>
                                        <textarea name="incident[]" class="form-control border-0 shadow-none" rows="2" readonly
                                            placeholder="Tuliskan ringkasan insiden atau isu utama di sini..."></textarea>
                                    </td>
                                    <td>
                                        <select name="status[]" class="form-control border-0 shadow-none">
                                            <option value="Open">Open</option>
                                            <option value="In Progress">In Progress</option>
                                            <option value="Closed">Closed</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="bg-white shadow-sm mb-5"
            style="border-radius: 25px; overflow: hidden; border: 1px solid #dee2e6;">
            <div class="section-header"
                style="background-color: #d4a017; color: #000; font-weight: 700; padding: 12px 20px; text-transform: uppercase; font-size: 0.85rem;">
                F. INDIKATOR PROSES (LEADING INDICATOR)
            </div>
            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="text-center" style="background-color: #fff9e6;">
                            <tr>
                                <th width="60px" class="py-3">No</th>
                                <th class="py-3">Aktifitas</th>
                                <th width="200px" class="py-3">Jumlah Pelaksanaan</th>
                                <th class="py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $leading_activities = [
                                    ['name' => 'Audit (SMKP, ISO Management system, etc.)', 'placeholder' => ''],
                                    ['name' => 'Risk management (IBPR, JSA, JHA, etc.)', 'placeholder' => ' '],
                                    ['name' => 'Safety Talks / Toolbox Meeting', 'placeholder' => ''],
                                    ['name' => 'Hazard Identification Report', 'placeholder' => ''],
                                    ['name' => 'TAKE 5', 'placeholder' => ''],
                                    ['name' => 'Planned Task Observation (PTO)', 'placeholder' => ''],
                                    ['name' => 'Procedures Review (SOP, IK)', 'placeholder' => ''],
                                    ['name' => 'OHS Initiatives', 'placeholder' => ''],
                                    [
                                        'name' => 'OHS Promotion (Banner, Poster, Brochure, Competition, etc.)',
                                        'placeholder' => '',
                                    ],
                                    ['name' => 'Reporting & Investigation of Near Miss', 'placeholder' => ''],
                                    ['name' => 'Fatigue Check', 'placeholder' => ''],
                                    ['name' => 'Alcohol Test', 'placeholder' => ''],
                                    ['name' => 'Drug Test', 'placeholder' => ''],
                                ];
                            @endphp

                            @foreach ($leading_activities as $index => $act)
                                @php
                                    $savedData = $report->indicators->firstWhere('activity', $act['name']);
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold text-muted bg-light">{{ $index + 1 }}</td>
                                    <td class="ps-3 fw-medium bg-light">
                                        {{ $act['name'] }}
                                        <input type="hidden" name="activity[]" value="{{ $act['name'] }}">
                                    </td>
                                    <td>
                                        <input type="number" name="jumlah_pelaksana[]"
                                            class="form-control border-0 shadow-none text-center bg-light"
                                            value="{{ $savedData->jumlah_pelaksana ?? 0 }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="remarks[]"
                                            class="form-control border-0 shadow-none bg-light"
                                            value="{{ $savedData->remarks ?? '' }}"
                                            placeholder="{{ $act['placeholder'] }}" readonly>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 p-3 rounded-4" style="background-color: #fff9e6; border: 1px dashed #d4a017;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-info-circle text-warning"></i>
                        <small class="text-dark fw-bold">Data Indikator Proses wajib diisi sesuai dengan aktifitas
                            yang telah dilakukan selama bulan berjalan.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm mb-5"
            style="border-radius: 25px; overflow: hidden; border: 1px solid #dee2e6;">
            <div class="section-header"
                style="background-color: #d4a017; color: #000; font-weight: 700; padding: 12px 20px; text-transform: uppercase; font-size: 0.85rem;">
                G. INDIKATOR HASIL (LAGGING INDICATOR)
            </div>

            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="text-center" style="background-color: #fff9e6;">
                            <tr>
                                <th width="50px">No</th>
                                <th>Jumlah Kategori</th>
                                <th width="150px">Threshold / Target</th>
                                <th width="120px">Aktual</th>
                                <th width="100px">FR</th>
                                <th width="120px">Hari Hilang</th>
                                <th width="100px">SR</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-lagging">
                            @php
                                $categories = [
                                    'NR (Non Recordable)',
                                    'NM (Near Miss)',
                                    'PD (Property Damage)',
                                    'OI (Occupational Ilness)',
                                    'FA (First Aid)',
                                    'MTC (Medical Treatment Case)',
                                    'RWC (Restricted Work Case)',
                                    'LTI (Lost Time Injury Case)',
                                    'Fatality',
                                    'Lost Work Days RWC',
                                    'Lost Work Days LTI',
                                ];
                            @endphp

                            @foreach ($categories as $index => $cat)
                                @php
                                    $savedLagging = $report->safetyMetrics?->firstWhere('category', $cat);
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold text-muted bg-light">{{ $index + 1 }}</td>
                                    <td class="ps-3 fw-medium bg-light">
                                        {{ $cat }}
                                        <input type="hidden" name="category[]" value="{{ $cat }}">
                                    </td>
                                    <td>
                                        <input type="number" name="target[]"
                                            class="form-control border-0 shadow-none text-center bg-light"
                                            value="{{ $savedLagging->target ?? 0 }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="actual[]"
                                            class="form-control border-0 shadow-none text-center actual-val bg-light"
                                            value="{{ $savedLagging->actual ?? 0 }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="fr[]"
                                            class="bg-light form-control text-center fw-bold fr-val"
                                            value="{{ $savedLagging->fr ?? '0.00' }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="lost_days[]"
                                            class="form-control border-0 shadow-none text-center lost-days-val bg-light"
                                            value="{{ $savedLagging->lost_days ?? 0 }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="sr[]"
                                            class="bg-light form-control text-center fw-bold sr-val"
                                            value="{{ $savedLagging->sr ?? '0.00' }}" readonly>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 p-3 rounded-4" style="background-color: #f8f9fa; border: 1px dashed #dee2e6;">
                    <small class="text-muted d-block">
                        <strong>Catatan Rumus:</strong><br>
                        * FR = (Jumlah Aktual / Total Jam Kerja) x 1.000.000<br>
                        * SR = (Jumlah Hari Hilang / Total Jam Kerja) x 1.000.000
                    </small>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-5" style="border-radius: 20px; overflow: hidden; border: 1px solid #dee2e6;">
            <div class="section-header"
                style="background-color: #d4a017; color: #000; font-weight: 700; padding: 15px 20px; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                <i class="fas fa-camera me-2"></i> H. DOKUMENTASI KEGIATAN
            </div>
            <div class="p-4 bg-white">
                <div class="row g-4" id="document-grid">
                    @forelse ($report->documentations as $doc)
                        <div class="col-xl-3 col-lg-4 col-md-6 doc-item">
                            <div class="card h-100 border-0 shadow-sm"
                                style="border-radius: 15px; background-color: #f8f9fa;">
                                <div class="position-relative">
                                    <div style="height: 200px; overflow: hidden; border-radius: 15px 15px 0 0;">
                                        @if ($doc->image)
                                            <img src="{{ route('storage.external', ['folder' => 'monthly_contractor', 'filename' => basename($doc->image)]) }}"
                                                class="w-100 h-100 object-fit-cover" alt="Dokumentasi">
                                        @else
                                            <div
                                                class="d-flex align-items-center justify-content-center h-100 bg-secondary text-white">
                                                <i class="fas fa-image fa-2x"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <label class="text-uppercase small fw-bold text-muted mb-1"
                                        style="font-size: 0.65rem;">Keterangan</label>
                                    <p class="card-text small text-dark mb-0" style="min-height: 40px;">
                                        {{ $doc->remarks ?: 'Tidak ada keterangan.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-images fa-3x text-light mb-3"></i>
                                <p class="text-muted italic">Tidak ada dokumentasi yang dilampirkan.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="mb-5">
                    <b>Activity History</b>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Event</th>
                                <th>Status</th>
                                <th>DateTime</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($log ?? [] as $l)
                                <td>{{ $l->user_name }}</td>
                                <td>{{ $l->event }}</td>
                                <td>{{ $l->status }}</td>
                                <td>{{ $l->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada riwayat aktivitas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-5" style="border-radius: 15px;">
            <div class="card-body p-4">
                {{-- Input Catatan --}}
                @if ($report->next_user_id === Auth::user()->id)
                    <div class="mb-4">
                        <label for="approval_remarks" class="fw-bold mb-2">
                            <i class="fas fa-comment-dots me-1"></i> Catatan Persetujuan (Optional)
                        </label>
                        {{-- ID form di bawah harus SAMA dengan atribut form di sini --}}
                        <textarea name="remarks" id="approval_remarks" form="form-approval" class="form-control border-0 bg-light"
                            rows="3" placeholder="Masukkan catatan atau alasan persetujuan di sini..." style="border-radius: 10px;"></textarea>
                    </div>
                @endif

                <div class="d-flex flex-wrap gap-3 justify-content-center align-items-center">
                    @if ($report->next_user_id === Auth::user()->id)
                        <form action="{{ route('transaction-monthly-reports.approve', $report->id) }}" method="POST"
                            id="form-approval" class="d-flex gap-3">
                            @method('PUT')
                            @csrf
                            <button type="button" name="aksi" value="approve"
                                class="btn btn-success px-4 py-2 fw-bold shadow-sm btn-approve"
                                style="border-radius: 10px;">
                                <i class="fas fa-check-circle me-2"></i> APPROVE
                            </button>

                            <button type="button" name="aksi" value="reject"
                                class="btn btn-danger px-4 py-2 fw-bold shadow-sm btn-reject"
                                style="border-radius: 10px;">
                                <i class="fas fa-times-circle me-2"></i> REJECT
                            </button>

                            <input type="hidden" name="aksi" id="aksi_value">
                        </form>
                    @endif

                    {{-- Bagian Tombol EDIT --}}
                    @if ($report->requestor_id === Auth::user()->id)
                        <a href="{{ route('transaction-monthly-reports.edit', $report->id) }}"
                            class="btn btn-warning px-4 py-2 fw-bold text-white shadow-sm"
                            style="border-radius: 10px;">
                            <i class="fas fa-edit me-2"></i> EDIT
                        </a>
                    @endif

                    {{-- Bagian Tombol HAPUS --}}
                    @if ($report->requestor_id === Auth::user()->id)
                        <form action="{{ route('transaction-monthly-reports.destroy', $report->id) }}" method="POST"
                            class="form-delete m-0">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                class="btn btn-danger px-4 py-2 fw-bold shadow-sm btn-delete-action"
                                style="border-radius: 10px;">
                                <i class="fas fa-trash-alt me-2"></i> HAPUS
                            </button>
                        </form>
                    @endif

                    {{-- Tombol PDF (Baru ditambahkan) --}}
                    <a href="{{ route('transaction-monthly-reports.view_pdf_contractor', $report->id) }}"
                        target="_blank" class="btn btn-info px-4 py-2 fw-bold text-white shadow-sm"
                        style="border-radius: 10px;">
                        <i class="fas fa-file-pdf me-2"></i> PDF
                    </a>

                    {{-- Tombol KEMBALI --}}
                    <a href="{{ route('transaction-monthly-reports.index') }}"
                        class="btn btn-secondary px-4 py-2 fw-bold shadow-sm" style="border-radius: 10px;">
                        <i class="fas fa-arrow-left me-2"></i> KEMBALI
                    </a>
                </div>
            </div>
        </div>
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    // 1. Helper Loading Spinner
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

                    // 2. Logika Approve & Reject
                    const btnApprove = document.querySelector('.btn-approve');
                    const btnReject = document.querySelector('.btn-reject');
                    const formApproval = document.getElementById('form-approval');
                    const aksiInput = document.getElementById('aksi_value');

                    if (btnApprove) {
                        btnApprove.addEventListener('click', function() {
                            Swal.fire({
                                title: 'Approve Document?',
                                text: "Are you sure you want to approve this report?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#28a745',
                                cancelButtonColor: '#818181',
                                confirmButtonText: 'Yes, Approve!',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    if (aksiInput) aksiInput.value = 'approve';
                                    showLoading('Approving Report...');
                                    formApproval.submit();
                                }
                            });
                        });
                    }

                    if (btnReject) {
                        btnReject.addEventListener('click', function() {
                            Swal.fire({
                                title: 'Reject Document?',
                                text: "This action will reject the report. Proceed?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#F76361',
                                cancelButtonColor: '#818181',
                                confirmButtonText: 'Yes, Reject it!',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    if (aksiInput) aksiInput.value = 'reject';
                                    showLoading('Rejecting Report...');
                                    formApproval.submit();
                                }
                            });
                        });
                    }
                    document.addEventListener('click', function(e) {
                        if (e.target.closest('.btn-danger') && e.target.closest('.form-delete')) {
                            const button = e.target.closest('.btn-danger');
                            const form = button.closest('.form-delete');

                            // Jika tombol bertipe submit, kita cegah dulu
                            e.preventDefault();

                            Swal.fire({
                                title: 'Delete Data?',
                                text: "All related data will be permanently deleted!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#F76361',
                                cancelButtonColor: '#818181',
                                confirmButtonText: 'Yes, Delete!',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    showLoading('Deleting...');
                                    form.submit();
                                }
                            });
                        }
                    });
                });
                $(document).on('input', '.actual-val, .lost-days-val', function() {
                    // Ambil total jam kerja dari Section B (pastikan input total jam kerja punya id/class 'total-hours-all')
                    let totalHours = parseFloat($('input[name="total_hours_sum"]').val()) || 0;

                    $('#tbody-lagging tr').each(function() {
                        let actual = parseFloat($(this).find('.actual-val').val()) || 0;
                        let lostDays = parseFloat($(this).find('.lost-days-val').val()) || 0;

                        if (totalHours > 0) {
                            let fr = (actual / totalHours) * 1000000;
                            let sr = (lostDays / totalHours) * 1000000;

                            $(this).find('.fr-val').text(fr.toFixed(2));
                            $(this).find('.sr-val').text(sr.toFixed(2));
                        } else {
                            $(this).find('.fr-val').text('0.00');
                            $(this).find('.sr-val').text('0.00');
                        }
                    });
                });

                $(document).ready(function() {

                    function calculateTotals() {
                        // --- LOGIKA ASLI KAMU (JANGAN DIUBAH) ---
                        let empB1 = 0;
                        let hoursB1 = 0;
                        $('input[name="operational_employee_total"], input[name="administration_operational_total"], input[name="supervision_employee_total"]')
                            .each(function() {
                                empB1 += parseFloat($(this).val()) || 0;
                            });
                        $('input[name="operational_hours"], input[name="administration_hours"], input[name="supervision_hours"]')
                            .each(function() {
                                hoursB1 += parseFloat($(this).val()) || 0;
                            });

                        $('#total_emp_b1').text(empB1);
                        $('#total_hours_b1').text(hoursB1);

                        let empB2 = 0;
                        let hoursB2 = 0;
                        $('input[name="subcon_operational_employee_total"], input[name="subcon_admin_total"], input[name="subcon_supervision_total"]')
                            .each(function() {
                                empB2 += parseFloat($(this).val()) || 0;
                            });
                        $('input[name="subcon_operational_hours"], input[name="subcon_admin_hours"], input[name="subcon_supervision_hours"]')
                            .each(function() {
                                hoursB2 += parseFloat($(this).val()) || 0;
                            });

                        $('#total_emp_b2').text(empB2);
                        $('#total_hours_b2').text(hoursB2);

                        // Update Grand Total
                        let grandTotalHours = hoursB1 + hoursB2;
                        $('#grand_total_emp').text(empB1 + empB2);
                        $('#grand_total_hours').text(grandTotalHours);

                        // --- TAMBAHAN: Panggil fungsi hitung FR/SR ---
                        calculateLagging(grandTotalHours);
                    }

                    // Jalankan fungsi setiap kali ada input yang berubah
                    $(document).on('input', '.count-emp, .count-hours', function() {
                        calculateTotals();
                    });

                    // Jalankan sekali saat halaman pertama kali dimuat
                    calculateTotals();

                    function calculateLagging(totalHours) {
                        // Jika totalHours tidak dikirim, ambil dari teks yang sudah dihitung di atas
                        if (totalHours === undefined) {
                            totalHours = parseFloat($('#grand_total_hours').text()) || 0;
                        }

                        $('#tbody-lagging tr').each(function() {
                            let row = $(this);
                            let actual = parseFloat(row.find('.actual-val').val()) || 0;
                            let lostDays = parseFloat(row.find('.lost-days-val').val()) || 0;

                            if (totalHours > 0) {
                                let fr = (actual / totalHours) * 1000000;
                                let sr = (lostDays / totalHours) * 1000000;

                                // Mengisi hasil ke input FR dan SR
                                row.find('.fr-val').val(fr.toFixed(2));
                                row.find('.sr-val').val(sr.toFixed(2));
                            } else {
                                row.find('.fr-val').val('0.00');
                                row.find('.sr-val').val('0.00');
                            }
                        });
                    }

                    // Jalankan fungsi setiap kali ada perubahan angka
                    $(document).on('input', '.count-hours, .count-emp, .actual-val, .lost-days-val', function() {
                        calculateTotals(); // Hitung total jam dulu, lalu otomatis hitung lagging
                    });
                    calculateTotals();
                    // Logic Tambah Baris
                    $('.btn-add').click(function() {
                        let target = $(this).data('target');
                        let $tbody = $(target);
                        let $newRow = $tbody.find('tr:first').clone();

                        $newRow.find('input').val('');
                        $newRow.find('.btn-remove').removeClass('d-none');
                        $tbody.append($newRow);
                        updateIndex($tbody);
                    });

                    $(document).on('input', '.prev-stock, .received-qty, .used-qty', function() {
                        let $row = $(this).closest('tr');
                        let prev = parseFloat($row.find('.prev-stock').val()) || 0;
                        let received = parseFloat($row.find('.received-qty').val()) || 0;
                        let used = parseFloat($row.find('.used-qty').val()) || 0;

                        let total = (prev + received) - used;

                        // Update tampilan dan input hidden
                        $row.find('.last-stock-display').text(total);
                        $row.find('.last-stock-value').val(total);

                        // Kasih warna merah kalau stok minus
                        if (total < 0) {
                            $row.find('.last-stock-display').addClass('text-danger');
                        } else {
                            $row.find('.last-stock-display').removeClass('text-danger');
                        }
                    });

                    // Logic Hapus Baris
                    $(document).on('click', '.btn-remove', function() {
                        let $tbody = $(this).closest('tbody');
                        $(this).closest('tr').remove();
                        updateIndex($tbody);
                    });

                    function updateIndex($tbody) {
                        $tbody.find('tr').each(function(i) {
                            $(this).find('.row-num').text(i + 1);
                        });
                    }

                    $('.btn-add-docs').click(function() {
                        let $tbody = $('#tbody-docs');
                        let $newRow = $tbody.find('tr:first').clone();

                        // Reset input file dan text
                        $newRow.find('input').val('');

                        // Tampilkan tombol hapus untuk baris baru
                        $newRow.find('.btn-remove-docs').removeClass('d-none');

                        $tbody.append($newRow);
                        updateDocNumbers();
                    });

                    // Fungsi Hapus Baris
                    $(document).on('click', '.btn-remove-docs', function() {
                        $(this).closest('tr').remove();
                        updateDocNumbers();
                    });

                    // Update Nomor Urut
                    function updateDocNumbers() {
                        $('#tbody-docs tr').each(function(index) {
                            $(this).find('.row-num').text(index + 1);
                        });
                    }
                });

                $('.btn-add-b3').click(function() {
                    let $tbody = $('#tbody-b3');
                    let $newRow = $tbody.find('tr:first').clone();
                    $newRow.find('input').val('');
                    $newRow.find('input[type="number"]').val(0);
                    $newRow.find('.last-stock').text(0);
                    $newRow.find('.btn-remove-b3').removeClass('d-none');
                    $tbody.append($newRow);
                });

                // Hapus Baris B3
                $(document).on('click', '.btn-remove-b3', function() {
                    $(this).closest('tr').remove();
                });

                // Hitung Otomatis Sisa Akhir (Sisa Lalu + Terima - Pakai)
                $(document).on('input', '.prev-stock, .received-qty, .used-qty', function() {
                    let $row = $(this).closest('tr');
                    let prev = parseFloat($row.find('.prev-stock').val()) || 0;
                    let received = parseFloat($row.find('.received-qty').val()) || 0;
                    let used = parseFloat($row.find('.used-qty').val()) || 0;

                    let total = (prev + received) - used;
                    $row.find('.last-stock').text(total);
                });

                $('.btn-add-insiden').click(function() {
                    let $tbody = $('#tbody-insiden');
                    let $newRow = $tbody.find('tr:first').clone();

                    // Reset isi
                    $newRow.find('textarea').val('');
                    $newRow.find('select').val('Open');

                    // Tampilkan tombol hapus
                    $newRow.find('.btn-remove-insiden').removeClass('d-none');

                    $tbody.append($newRow);
                    updateIncidentNumbers();
                });

                // Hapus Baris Insiden
                $(document).on('click', '.btn-remove-insiden', function() {
                    $(this).closest('tr').remove();
                    updateIncidentNumbers();
                });

                function updateIncidentNumbers() {
                    $('#tbody-insiden tr').each(function(index) {
                        $(this).find('.row-num').text(index + 1);
                    });
                }

                $('.btn-add-leading').click(function() {
                    let $tbody = $('#tbody-leading');
                    let $newRow = $tbody.find('tr:first').clone();

                    // Reset input values
                    $newRow.find('input').val('');
                    $newRow.find('input[type="number"]').val(0);

                    // Tampilkan tombol hapus
                    $newRow.find('.btn-remove-leading').removeClass('d-none');

                    $tbody.append($newRow);
                    updateLeadingNumbers();
                });

                // Hapus Baris Leading Indicator
                $(document).on('click', '.btn-remove-leading', function() {
                    $(this).closest('tr').remove();
                    updateLeadingNumbers();
                });

                function updateLeadingNumbers() {
                    $('#tbody-leading tr').each(function(index) {
                        $(this).find('.row-num').text(index + 1);
                    });
                }
            </script>
        @endpush
</x-app-layout>
