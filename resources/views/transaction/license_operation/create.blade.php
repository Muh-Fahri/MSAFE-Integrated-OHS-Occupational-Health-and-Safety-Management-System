<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center gap-2">
                <div class="page-title-left">
                    <a href="{{ route('transaction-license.index') }}" class="btn btn-sm fs-4 btn-back">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                </div>
                <h4 class="mb-0">Data Pengajuan</h4>
            </div>
        </div>
    </div>
    {{-- Alert untuk Error Validasi (Input Kosong/Salah) --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <div>
                    <h5 class="alert-heading">Periksa Kembali Inputan Anda!</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alert untuk Error Session (Exception dari Controller) --}}
    @if (session('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-ban"></i> Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('transaction-license.create') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mt-3">
            <div class="card-body">
                <div class="row">
                    {{-- BARIS ATAS: Tipe Lisensi --}}
                    <div class="col-12 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-bold">Pilih Tipe Lisensi:</label>
                                <select class="form-select rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" id="type_license"
                                    name="type">
                                    <option value="KIMPER" {{ $type == 'KIMPER' ? 'selected' : '' }}>KIMPER(Kendaraan/Alat Berat)</option>
                                    <option value="KIMPAK" {{ $type == 'KIMPAK' ? 'selected' : '' }}>KIMPAK(Peralatan Kerja)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- BARIS BARU: KOLOM KIRI --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ID Karyawan <span class="text-danger">*</span></label>
                            <input name="employee_id" type="text" class="form-control rounded-pill border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" placeholder="Masukkan ID Karyawan"
                                value="{{ old('employee_id') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input name="name" type="text" class="form-control rounded-pill border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" placeholder="Nama sesuai identitas"
                                value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Posisi / Jabatan<span class="text-danger">*</span></label>
                            <input name="position" type="text" class="form-control rounded-pill border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" placeholder="Contoh: Operator"
                                value="{{ old('position') }}">
                        </div>
                        @if($type=="KIMPER")
                        <div class="mb-3" >
                            <label class="form-label fw-bold">Tanggal Berakhir SIM<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="date" name="driving_license_expiry_date" id="driving_license_expiry_date" 
                                    class="form-control date-input text-secondary rounded-pill border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    onclick="this.showPicker()" required>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- KOLOM KANAN --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Perusahaan <span class="text-danger">*</span></label>
                            <select name="company_id" class="form-select rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;">
                                <option value="">Select Company</option>
                                @foreach ($list_company as $company_id => $company_name)
                                    <option value="{{ $company_id }}"
                                        {{ old('company_id') == $company_id ? 'selected' : '' }}>
                                        {{ $company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                            <select name="department_id" class="form-select rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;">
                                <option value="">Select Department</option>
                                @foreach ($list_department as $department_id => $department_name)
                                    <option value="{{ $department_id }}"
                                        {{ old('department_id') == $department_id ? 'selected' : '' }}>
                                        {{ $department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alasan Pengajuan <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="reason" class="form-control rounded-5 text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" placeholder="Reason"
                                value="{{ old('reason') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Jenis {{$type}}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        @if($type=="KIMPER")
                        <thead>
                            <tr>
                                <th width="20%" style="background-color: #eee;">Klasifikasi</th>
                                <th width="5%" style="background-color: #eee;">Kode</th>
                                <th width="75%" style="background-color: #eee;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list_item_group as $key=>$val)
                            <tr>
                                <td>{{explode('_', $key)[1]}}</td>
                                <td>{{explode('_', $key)[0]}}</td>
                                <td style="padding: 2px;">
                                    <table border="1" style="border-color: #eee; width: 100%;">
                                        <tbody>
                                        <tr>
                                        @foreach($val as $key2=>$val2)
                                            <td width="25%" style="padding: 4px;">
                                                <input type="checkbox" class="js-item-id" name="item_id[{{ $val2->id }}]" value="{{ $val2->id }}" style="margin-right: 5px;">
                                                {{$val2->name}}
                                            </td>
                                            @if($key2 > 0 && $key2 % 3 == 0)
                                                </tr><tr>
                                            @endif
                                        @endforeach
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <tbody>
                            <tr>
                            @foreach($list_item as $key=>$val)
                                <td width="5%">
                                    <input type="checkbox" class="js-item-id" name="item_id[{{ $val->id }}]" value="{{ $val->id }}">
                                </td>
                                <td width="20%" style="padding: 4px;">
                                    <input type="hidden" name="line_item_code[{{ $val->id }}]" class="js-item-code-{{ $val->id }}" value="{{ $val->code }}">
                                    {{$val->name}}
                                    @if($val->code=="99")
                                    :
                                    <input type="text" name="item_name_99" class="js-item-name-99" class="form-control" readonly>
                                    @endif
                                </td>
                                @if($key > 0 && $key % 3 == 0)
                                    </tr><tr>
                                @endif
                            @endforeach
                            </tr>
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>

            @if($type=="KIMPER")
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Zonasi {{$type}}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="5%" style="background-color: #eee;">Pilih</th>
                                <th width="20%" style="background-color: #eee;">Zona</th>
                                <th width="75%" style="background-color: #eee;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list_zone as $key=>$val)
                            <tr>
                                <td><input type="checkbox" class="js-zone-id" name="zone_id[{{ $val->id }}]" value="{{ $val->id }}"></td>
                                <td style="background-color: #{{$val->value3}}; font-weight: bold;">{{$val->name}}</td>
                                <td>{!! nl2br($val->value1."\n".$val->value2) !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="card-body" id="attachment-container">
                <p><span class="text-danger">*</span> Dokumen pertama harus foto diri anda</p>
                @php
                    $oldNames = old('attachment_names', ['Foto Diri']);
                @endphp

                @foreach ($oldNames as $index => $oldName)
                    <div class="row mb-3 attachment-row align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Nama / Jenis Dokumen</label>
                            <input type="text" name="attachment_names[]"
                                class="form-control rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6;" placeholder="Foto Diri"
                                value="{{ $oldName }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Unggah File</label>
                            <input type="file" name="attachment_files[]" class="form-control rounded-pill" required>
                            @if ($errors->any())
                                <small class="text-warning" style="font-size: 0.75rem;">*Pilih ulang file</small>
                            @endif
                        </div>
                        <div class="col-md-1 text-center">
                            {{-- Tombol hapus muncul jika baris lebih dari satu --}}
                            <button type="button"
                                class="btn btn-outline-danger btn-remove-attachment {{ $loop->first ? 'd-none' : '' }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-4 pb-4">
                <button type="button" id="add-attachment" class="btn btn-sm rounded-pill btn-add">
                    <i class="fas fa-plus me-1"></i>Add
                </button>
            </div>
            <div class="mt-4 mb-4 border-top pt-3 d-flex justify-content-end p-2">
                <a href="{{ route('transaction-license.create') }}" class="btn btn-cancel px-4 me-2"
                    style="border: 1px solid #ced4da;">
                    Cancel
                </a>
                <button type="submit" class="btn btn-submit px-4">
                    <i class="fas fa-save me-1"></i> Kirim Pengajuan
                </button>
            </div>
    </form>
    @push('scripts')
        <script>
        $(function () {
            $(".js-item-id").change(function() {
                let item_id = $(this).val();
                let item_code = $('.js-item-code-' + item_id).val();
                if(item_code=="99") {
                    if(this.checked) { 
                        $('.js-item-name-99').attr('required', true);
                        $('.js-item-name-99').attr('readonly', false);
                    } else {
                        $('.js-item-name-99').attr('required', false);
                        $('.js-item-name-99').attr('readonly', true);
                        $('.js-item-name-99').val('');
                    }
                }
            });
            $('#type_license').on('change', function(){
                window.location = '?type=' + $(this).val();
            });

            const btnAddAttachment = document.getElementById('add-attachment');
            const attachmentContainer = document.getElementById('attachment-container');

            btnAddAttachment.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'row mb-3 attachment-row align-items-end';
                newRow.innerHTML = `<div class="col-md-5">
                    <label class="form-label fw-bold">Nama / Jenis Dokumen</label>
                    <input type="text" name="attachment_names[]"
                        class="form-control rounded-pill text-secondary border-2"
                        style="border-color: #dee2e6;" placeholder="Input Document"
                        value="{{ $oldName }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Unggah File</label>
                    <input type="file" name="attachment_files[]" class="form-control rounded-pill" required>
                    @if ($errors->any())
                        <small class="text-warning" style="font-size: 0.75rem;">*Pilih ulang file</small>
                    @endif
                </div>
                <div class="col-md-1 text-center">
                    <button type="button"
                        class="btn btn-outline-danger btn-remove-attachment">
                        <i class="fas fa-times"></i>
                    </button>
                </div>`;
                newRow.querySelector('.btn-remove-attachment').addEventListener('click', function() {
                    newRow.remove();
                });
                attachmentContainer.appendChild(newRow);
            });
        });
        </script>
    @endpush

</x-app-layout>
