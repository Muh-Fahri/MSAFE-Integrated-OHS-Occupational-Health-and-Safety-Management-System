<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center gap-2 mb-3">
                <a href="{{ route('transaction-badge.index') }}" class="btn fs-4 btn-back btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">New Badge Request</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Form Badge Details</h5>

                    <form action="{{ route('transaction-badge.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Requestor</label>
                                    <input type="text"
                                        class="form-control rounded-pill text-secondary bg-light border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        value="{{ auth()->user()->name }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Company</label>
                                    <select name="company_id" class="form-select rounded-pill text-secondary border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;">
                                        <option value="">Select Company</option>
                                        @foreach ($com as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Sub Company</label>
                                    <select name="sub_company_id"
                                        class="form-select rounded-pill text-secondary border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;">
                                        <option value="">Select Sub Company</option>
                                        <option value="1">Sub Unit A</option>
                                        <option value="2">Sub Unit B</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Document No.</label>
                                    <input type="text" name="request_no"
                                        class="form-control bg-light rounded-pill text-secondary border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" value="{{ $nextNo }}"
                                        readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Request Date</label>
                                    <div class="input-group">
                                        <input type="date" name="request_date"
                                            class="form-control rounded-pill text-secondary border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            onclick="this.showPicker()">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Location</label>
                                    <select name="location" class="form-select rounded-pill text-secondary border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;">
                                        <option value="Head office jakarta">Select Location</option>
                                        @foreach ($loc as $l)
                                            <option value="{{ $l }}">{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="col-md-12 mb-4">
                                <label class="form-label mb-3 fw-bold text-uppercase small">II. Personnel
                                    Details</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="person-table">
                                        <thead class="table-light small text-uppercase">
                                            <tr>
                                                <th>Employee ID</th>
                                                <th>Citizen ID (NIK)</th>
                                                <th>Full Name</th>
                                                <th>Position</th>
                                                <th width="150">Status</th>
                                                <th width="100">Period</th>
                                                <th width="120">Documents</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr data-index="0">
                                                <td><input type="text" name="employee_id[]"
                                                        class="form-control form-control-sm"></td>
                                                <td><input type="number" name="citizen_id[]"
                                                        class="form-control form-control-sm"></td>
                                                <td><input type="text" name="name[]"
                                                        class="form-control form-control-sm person-name"></td>
                                                <td><input type="text" name="title[]"
                                                        class="form-control form-control-sm"></td>
                                                <td>
                                                    <select name="status[]" class="form-select form-select-sm">
                                                        @foreach ($stat as $s)
                                                            <option value="{{ $s }}">{{ $s }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="number" name="active_period[]"
                                                        class="form-control form-control-sm"></td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-outline-primary btn-sm btn-manage-docs"
                                                        data-bs-toggle="modal" data-bs-target="#modalDocs"
                                                        data-index="0">
                                                        <i class="fas fa-file-upload"></i> Docs
                                                    </button>
                                                    <div class="doc-inputs-container" id="doc-container-0">
                                                        <input type="file" name="file_path_photo[0]"
                                                            class="d-none">
                                                        <input type="file" name="file_path_ktp[0]" class="d-none">
                                                        <input type="file" name="file_path_ftw[0]" class="d-none">
                                                        <input type="file" name="file_path_induksi[0]"
                                                            class="d-none">
                                                    </div>
                                                </td>
                                                <td class="text-center"><i class="fas fa-lock text-muted"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    <button type="button" class="btn btn-add btn-sm" id="add-person">
                                         Add
                                    </button>
                                </div>
                            </div>

                            <div class="modal fade" id="modalDocs" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <h6 class="modal-title fw-bold">UPLOAD DOCUMENTS: <span
                                                    id="modal-person-name" class="text-primary"></span></h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" id="current-edit-index" value="">
                                            <div class="row g-3 text-center">
                                                <div class="col-md-3">
                                                    <div class="border p-2 rounded bg-light">
                                                        <label class="small fw-bold d-block mb-2">Pas Foto
                                                            (4x6)</label>
                                                        <input type="file" id="temp_photo"
                                                            class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="border p-2 rounded bg-light">
                                                        <label class="small fw-bold d-block mb-2">KTP (ID Card)</label>
                                                        <input type="file" id="temp_ktp"
                                                            class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="border p-2 rounded bg-light">
                                                        <label class="small fw-bold d-block mb-2">Cert. FTW</label>
                                                        <input type="file" id="temp_ftw"
                                                            class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="border p-2 rounded bg-light">
                                                        <label class="small fw-bold d-block mb-2">Cert. Induksi</label>
                                                        <input type="file" id="temp_induksi"
                                                            class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary btn-sm w-100"
                                                data-bs-dismiss="modal">Save Documents for this Person</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('transaction-badge.create') }}" class="btn btn-cancel"
                                style="border: 1px solid #ced4da;">Cancel</a>
                            <button type="submit" class="btn btn-submit px-4">
                                <i class="fas fa-save me-1"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let personIndex = 0; // Mulai dari 0 sesuai row pertama

            // 1. Tambah Baris Personil Baru
            document.getElementById('add-person').addEventListener('click', function() {
                personIndex++;
                const tbody = document.querySelector('#person-table tbody');
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-index', personIndex);
                newRow.innerHTML = `
            <td><input type="text" name="employee_id[]" class="form-control form-control-sm"></td>
            <td><input type="number" name="citizen_id[]" class="form-control form-control-sm"></td>
            <td><input type="text" name="name[]" class="form-control form-control-sm person-name"></td>
            <td><input type="text" name="title[]" class="form-control form-control-sm"></td>
            <td>
                <select name="status[]" class="form-select form-select-sm">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </td>
            <td><input type="number" name="active_period[]" class="form-control form-control-sm"></td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-primary btn-sm btn-manage-docs" data-bs-toggle="modal" data-bs-target="#modalDocs" data-index="${personIndex}">
                    <i class="fas fa-file-upload"></i> Docs
                </button>
                <div class="doc-inputs-container" id="doc-container-${personIndex}">
                    <input type="file" name="file_path_photo[${personIndex}]" class="d-none">
                    <input type="file" name="file_path_ktp[${personIndex}]" class="d-none">
                    <input type="file" name="file_path_ftw[${personIndex}]" class="d-none">
                    <input type="file" name="file_path_induksi[${personIndex}]" class="d-none">
                </div>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-link text-danger remove-person p-0"><i class="fas fa-trash"></i></button>
            </td>
        `;
                tbody.appendChild(newRow);
            });

            // 2. Logika Modal (Buka Modal & Set Target Baris)
            document.querySelector('#person-table').addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-manage-docs');
                if (btn) {
                    const index = btn.getAttribute('data-index');
                    const row = btn.closest('tr');
                    const personName = row.querySelector('.person-name').value || "Unnamed Person";

                    document.getElementById('current-edit-index').value = index;
                    document.getElementById('modal-person-name').innerText = personName;

                    // Reset input modal dengan file yang sudah ada (jika ada)
                    // Catatan: Secara security, kita tidak bisa 'mengisi' input file,
                    // tapi kita bisa meresetnya jika ingin user upload ulang.
                    document.getElementById('temp_photo').value = "";
                    document.getElementById('temp_ktp').value = "";
                    document.getElementById('temp_ftw').value = "";
                    document.getElementById('temp_induksi').value = "";
                }

                // Hapus Baris
                if (e.target.closest('.remove-person')) {
                    e.target.closest('tr').remove();
                }
            });

            // 3. Sinkronisasi File dari Modal ke Input Hidden di Baris
            const syncFile = (modalInputId, hiddenInputNamePart) => {
                document.getElementById(modalInputId).addEventListener('change', function() {
                    const index = document.getElementById('current-edit-index').value;
                    const container = document.getElementById(`doc-container-${index}`);
                    const hiddenInput = container.querySelector(`input[name^="${hiddenInputNamePart}"]`);

                    // Pindahkan file menggunakan DataTransfer
                    const dt = new DataTransfer();
                    if (this.files[0]) dt.items.add(this.files[0]);
                    hiddenInput.files = dt.files;

                    // Efek visual jika sudah terisi
                    const btn = document.querySelector(`.btn-manage-docs[data-index="${index}"]`);
                    btn.classList.replace('btn-outline-primary', 'btn-success');
                });
            };

            syncFile('temp_photo', 'file_path_photo');
            syncFile('temp_ktp', 'file_path_ktp');
            syncFile('temp_ftw', 'file_path_ftw');
            syncFile('temp_induksi', 'file_path_induksi');
        </script>
    @endpush
</x-app-layout>
