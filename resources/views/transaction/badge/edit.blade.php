<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center gap-2 mb-3">
                <a href="{{ route('transaction-badge.index') }}" class="btn btn-back fs-4 btn-sm">
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

                    <form action="{{ route('transaction-badge.update', $badgeReq->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Requestor</label>
                                    <input type="text"
                                        class="form-control rounded-pill text-secondary bg-light border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        value="{{ $badgeReq->requestor_name }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Company</label>
                                    <select name="company_id" class="form-select rounded-pill text-secondary border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;">
                                        <option value="">Select Company</option>
                                        @foreach ($com as $c)
                                            <option value="{{ $c->id }}"
                                                {{ $badgeReq->company_id == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Sub Company</label>
                                    <select name="sub_company_id"
                                        class="form-control rounded-pill text-secondary border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;">
                                        <option value="">Select Sub Company</option>
                                        {{-- Sesuai dengan value di form create Anda --}}
                                        <option value="1" {{ $badgeReq->sub_company_id == 1 ? 'selected' : '' }}>
                                            Sub Unit A</option>
                                        <option value="2" {{ $badgeReq->sub_company_id == 2 ? 'selected' : '' }}>
                                            Sub Unit B</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Document No.</label>
                                    <input type="text" name="request_no"
                                        class="form-control rounded-pill text-secondary border-2 bg-light"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        value="{{ $badgeReq->request_no }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Request Date</label>
                                    <div class="input-group">
                                        <input type="date" name="request_date"
                                            class="form-control rounded-pill text-secondary border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            value="{{ $badgeReq->request_date }}" onclick="this.showPicker()">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Location</label>
                                    <select name="location" class="form-select rounded-pill text-secondary border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;">
                                        <option value="">Select Location</option>
                                        @foreach ($loc as $l)
                                            <option value="{{ $l }}"
                                                {{ $badgeReq->location == $l ? 'selected' : '' }}>
                                                {{ $l }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="col-md-12 mb-4">
                                <label class="form-label mb-0 fw-bold text-uppercase small">II. Personnel
                                    Details</label>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="person-table">
                                        <thead class="table-light small text-uppercase">
                                            <tr>
                                                <th>Employee ID</th>
                                                <th>Citizen ID (NIK)</th>
                                                <th>Full Name</th>
                                                <th>Position</th>
                                                <th width="120">Status</th>
                                                <th width="80">Period</th>
                                                <th width="180">Current Files</th>
                                                <th width="100">Upload</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($badgeReq->lines as $index => $line)
                                                <tr data-index="{{ $index }}">
                                                    <td><input type="text" name="employee_id[]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $line->employee_id }}"></td>
                                                    <td><input type="number" name="citizen_id[]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $line->citizen_id }}"></td>
                                                    <td><input type="text" name="name[]"
                                                            class="form-control form-control-sm person-name"
                                                            value="{{ $line->name }}"></td>
                                                    <td><input type="text" name="title[]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $line->title }}"></td>
                                                    <td>
                                                        <select name="status[]" class="form-select form-select-sm">
                                                            @foreach ($stat as $s)
                                                                <option value="{{ $s }}"
                                                                    @selected($s == $line->status)>{{ $s }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="number" name="active_period[]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $line->active_period }}"></td>

                                                    <td>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @php
                                                                $docs = [
                                                                    [
                                                                        'path' => $line->file_path_photo,
                                                                        'label' => 'Photo',
                                                                        'icon' => 'fa-image',
                                                                    ],
                                                                    [
                                                                        'path' => $line->file_path_ktp,
                                                                        'label' => 'KTP',
                                                                        'icon' => 'fa-id-card',
                                                                    ],
                                                                    [
                                                                        'path' => $line->file_path_ftw,
                                                                        'label' => 'FTW',
                                                                        'icon' => 'fa-file-medical',
                                                                    ],
                                                                    [
                                                                        'path' => $line->file_path_induksi,
                                                                        'label' => 'Induction',
                                                                        'icon' => 'fa-file-alt',
                                                                    ],
                                                                ];
                                                            @endphp

                                                            @foreach ($docs as $doc)
                                                                @if ($doc['path'])
                                                                    <a href="{{ route('storage.external', ['filename' => basename($doc['path'])]) }}"
                                                                        target="_blank"
                                                                        class="badge bg-info text-decoration-none"
                                                                        title="View {{ $doc['label'] }}"
                                                                        style="font-size: 10px;">
                                                                        <i class="fas {{ $doc['icon'] }}"></i>
                                                                        {{ $doc['label'] }}
                                                                    </a>
                                                                @else
                                                                    <span class="badge bg-light text-muted border"
                                                                        style="font-size: 10px;">
                                                                        <i class="fas fa-times text-danger"></i>
                                                                        {{ $doc['label'] }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn {{ $line->file_path_photo ? 'btn-success' : 'btn-outline-primary' }} btn-sm btn-manage-docs"
                                                            data-bs-toggle="modal" data-bs-target="#modalDocs"
                                                            data-index="{{ $index }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>

                                                        <div class="doc-inputs-container d-none"
                                                            id="doc-container-{{ $index }}">
                                                            <input type="hidden"
                                                                name="old_photo[{{ $index }}]"
                                                                value="{{ $line->file_path_photo }}">
                                                            <input type="hidden" name="old_ktp[{{ $index }}]"
                                                                value="{{ $line->file_path_ktp }}">
                                                            <input type="hidden" name="old_ftw[{{ $index }}]"
                                                                value="{{ $line->file_path_ftw }}">
                                                            <input type="hidden"
                                                                name="old_induksi[{{ $index }}]"
                                                                value="{{ $line->file_path_induksi }}">

                                                            <input type="file"
                                                                name="file_path_photo[{{ $index }}]"
                                                                class="row-photo">
                                                            <input type="file"
                                                                name="file_path_ktp[{{ $index }}]"
                                                                class="row-ktp">
                                                            <input type="file"
                                                                name="file_path_ftw[{{ $index }}]"
                                                                class="row-ftw">
                                                            <input type="file"
                                                                name="file_path_induksi[{{ $index }}]"
                                                                class="row-induksi">
                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        @if ($loop->first)
                                                            <i class="fas fa-lock text-muted"></i>
                                                        @else
                                                            <button type="button"
                                                                class="btn btn-link text-danger remove-person p-0">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{-- Button pindah ke sini (Tengah Bawah) --}}
                                    <div class="d-flex justify-content-center mt-3">
                                        <button type="button" class="btn btn-add btn-sm" id="add-person">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modalDocs" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <h6 class="modal-title fw-bold">UPLOAD DOCUMENTS: <span
                                                    id="modal-person-name" class="text-primary"></span></h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" id="current-edit-index" value="">

                                            <div class="row g-3">
                                                @php
                                                    $docTypes = [
                                                        'photo' => ['label' => 'Pas Foto (4x6)', 'icon' => 'fa-image'],
                                                        'ktp' => ['label' => 'KTP (ID Card)', 'icon' => 'fa-id-card'],
                                                        'ftw' => ['label' => 'Cert. FTW', 'icon' => 'fa-file-medical'],
                                                        'induksi' => [
                                                            'label' => 'Cert. Induksi',
                                                            'icon' => 'fa-file-certificate',
                                                        ],
                                                    ];
                                                @endphp

                                                @foreach ($docTypes as $key => $info)
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="small fw-bold mb-1">
                                                                {{ $info['label'] }}
                                                            </label>
                                                            <input type="file" id="temp_{{ $key }}"
                                                                name="temp_{{ $key }}[]"
                                                                class="form-control form-control-sm temp-file-input"
                                                                accept=".jpg,.jpeg,.png,.pdf">
                                                            <div id="filename-display-{{ $key }}"
                                                                class="text-muted mt-1" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-primary btn-sm w-100"
                                                data-bs-dismiss="modal">
                                                <i class="fas fa-save me-1"></i> Apply Changes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('transaction-badge.index') }}" class="btn btn-cancel">Cancel</a>
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
            let personIndex = {{ $badgeReq->lines->count() }};

            // FUNGSI UTAMA: Mengambil URL yang valid untuk Route Anda
            function getUrlForRoute(path) {
                if (!path || path === "null" || path === "") return '';

                // Ambil nama file saja, buang folder/path yang ada di database
                // Contoh: "uploads/foto.jpg" menjadi "foto.jpg"
                const fileNameOnly = path.split('/').pop().split('\\').pop();

                // Pastikan mengarah ke route storage.external kamu
                return `/view-storage/${fileNameOnly}`;
            }

            // 2. Tambah Baris Baru
            document.getElementById('add-person').addEventListener('click', function() {
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
                <input type="file" name="file_path_photo[${personIndex}]" class="d-none row-photo" data-old="">
                <input type="file" name="file_path_ktp[${personIndex}]" class="d-none row-ktp" data-old="">
                <input type="file" name="file_path_ftw[${personIndex}]" class="d-none row-ftw" data-old="">
                <input type="file" name="file_path_induksi[${personIndex}]" class="d-none row-induksi" data-old="">
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-link text-danger remove-person p-0"><i class="fas fa-trash"></i></button>
        </td>
    `;
                tbody.appendChild(newRow);
                personIndex++;
            });

            // 3. Click Handler untuk buka Modal & Load Preview
            document.querySelector('#person-table').addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-manage-docs');
                if (btn) {
                    const index = btn.getAttribute('data-index');
                    const container = document.getElementById(`doc-container-${index}`);
                    document.getElementById('current-edit-index').value = index;

                    const personName = btn.closest('tr').querySelector('.person-name').value;
                    const modalTitle = document.querySelector('#modalDocs .modal-title');
                    if (modalTitle) modalTitle.innerText = `Manage Documents: ${personName || 'New Personnel'}`;

                    ['photo', 'ktp', 'ftw', 'induksi'].forEach(type => {
                        const hiddenInput = container.querySelector(`.row-${type}`);
                        const previewDiv = document.getElementById(`preview-${type}`);
                        const tempInput = document.getElementById(`temp_${type}`);

                        tempInput.value = ""; // Reset modal input
                        previewDiv.innerHTML = '';

                        // JIKA ADA FILE BARU (Belum Disave)
                        if (hiddenInput.files && hiddenInput.files[0]) {
                            const file = hiddenInput.files[0];
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                if (file.type.match('image.*')) {
                                    previewDiv.innerHTML =
                                        `<img src="${e.target.result}" class="img-fluid rounded shadow-sm" style="max-height: 120px; object-fit: contain;">`;
                                } else {
                                    previewDiv.innerHTML =
                                        `<div class="text-center"><i class="fas fa-file-pdf fa-3x text-danger mb-2"></i><br><small>${file.name}</small></div>`;
                                }
                            }
                            reader.readAsDataURL(file);
                        }
                        // JIKA FILE LAMA (Dari Database)
                        else {
                            const rawPath = hiddenInput.getAttribute('data-old');
                            console.log("Loading path from DB:", rawPath); // Debugging

                            if (rawPath && rawPath !== "null" && rawPath !== "") {
                                const url = getUrlForRoute(rawPath);
                                const isImage = /\.(jpg|jpeg|png|webp|gif)$/i.test(rawPath);

                                if (isImage) {
                                    previewDiv.innerHTML = `
                <img src="${url}"
                     class="img-fluid rounded shadow-sm"
                     style="max-height: 120px; object-fit: contain;"
                     onerror="this.parentElement.innerHTML='<span class=\"text-danger small\">File Not Found</span>'">
            `;
                                } else {
                                    previewDiv.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-file-pdf fa-3x text-muted mb-2"></i><br>
                    <a href="${url}" target="_blank" class="btn btn-xs btn-outline-secondary">Open PDF</a>
                </div>
            `;
                                }
                            } else {
                                previewDiv.innerHTML = '<span class="text-muted small">No file</span>';
                            }
                        }
                    });
                }

                if (e.target.closest('.remove-person')) {
                    if (confirm('Are you sure you want to remove this row?')) {
                        e.target.closest('tr').remove();
                    }
                }
            });

            // 4. Sync File dari Modal ke Hidden Input
            ['photo', 'ktp', 'ftw', 'induksi'].forEach(type => {
                document.getElementById(`temp_${type}`).addEventListener('change', function() {
                    const index = document.getElementById('current-edit-index').value;
                    const container = document.getElementById(`doc-container-${index}`);
                    const hiddenInput = container.querySelector(`.row-${type}`);
                    const previewDiv = document.getElementById(`preview-${type}`);

                    if (this.files && this.files[0]) {
                        const file = this.files[0];

                        // Update Preview di Modal
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (file.type.match('image.*')) {
                                previewDiv.innerHTML =
                                    `<img src="${e.target.result}" class="img-fluid rounded shadow-sm" style="max-height: 120px; object-fit: contain;">`;
                            } else {
                                previewDiv.innerHTML =
                                    `<div class="text-center"><i class="fas fa-file-alt fa-3x text-primary mb-2"></i><br><span class="badge bg-primary">${file.name}</span></div>`;
                            }
                        }
                        reader.readAsDataURL(file);

                        // Copy file ke input asli
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        hiddenInput.files = dt.files;

                        // Hijaukan tombol Docs
                        const btn = document.querySelector(`.btn-manage-docs[data-index="${index}"]`);
                        btn.classList.remove('btn-outline-primary');
                        btn.classList.add('btn-success');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
