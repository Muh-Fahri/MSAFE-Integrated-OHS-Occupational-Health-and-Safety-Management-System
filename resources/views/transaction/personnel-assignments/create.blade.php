    <x-app-layout>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center">
                    <a href="{{ route('transaction-personnel-assignments.index') }}" class="btn fs-4 btn-back">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                    <h4 class="mb-0">Add New Assignment</h4>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                style="border-left: 5px solid #dc3545;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3"></i>
                    <div>
                        <strong>Oops! Ada kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                style="border-left: 5px solid #ffc107;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-bug me-3"></i>
                    <div>
                        <strong>System Error:</strong>
                        <p class="mb-0 small">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-triangle me-1"></i> Terjadi Kesalahan!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form action="{{ route('transaction-personnel-assignments.store_detail') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <label for="request_no" class="col-md-4 col-form-label">Request No</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control bg-light rounded-pill "
                                                style="border-color: #dee2e6; padding-left: 1.5rem;" name="request_no"
                                                id="request_no" value="{{ old('request_no') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="requestor_name" class="col-md-4 col-form-label">Requestor</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control bg-light rounded-pill"
                                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                name="requestor_name" id="requestor_name"
                                                value="{{ $data->requestor_name }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <label for="request_date" class="col-md-4 col-form-label">Request Date<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <input type="date"
                                                class="form-control rounded-pill @error('request_date') is-invalid @enderror"
                                                name="request_date" style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                id="request_date" value="{{ old('request_date') }}" required>
                                            @error('request_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="company_id" class="col-md-4 col-form-label">Company
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-8">
                                            <select
                                                class="form-select select2 rounded-pill @error('company_id') is-invalid @enderror"
                                                id="company_id" style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                name="company_id" required>
                                                <option value="">-- Select Company --</option>
                                                @foreach ($list_company as $company)
                                                    <option value="{{ $company->id }}"
                                                        {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('company_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Employee ID</th>
                                                <th>Name</th>
                                                <th>Title</th>
                                                <th>Department</th>
                                                <th>Assignment</th>
                                                <th>Field</th>
                                                <th>Attachments</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8" align="center">
                                                    <button type="button"
                                                        class="btn btn-sm btn-add btn_add_detail">ADD</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-end">
                                    <a href="{{ route('transaction-personnel-assignments.index') }}"
                                        class="btn btn-cancel">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-submit">
                                        <i class="fas fa-save me-1"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="modal_detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" hidden id="detail_request_date" readonly>
                        <input type="text" class="form-control" hidden id="detail_request_company_id" readonly>
                        <div class="mb-3 d-flex flex-column">
                            <label for="employee_id" class="form-label fw-bold mb-1">Employee ID / Name:</label>
                            <select class="form-control select2 @error('employee_id') is-invalid @enderror"
                                id="employee_id" name="employee_id" style="width: 100%;" required>
                                <option value="">-- Select Employee --</option>
                                @foreach ($list_employee as $employee)
                                    <option value="{{ $employee->employee_id }}"
                                        {{ old('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                                        {{ $employee->employee_id . ' - ' . $employee->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="employee_title" class="col-form-label fw-bold">Title:</label>
                            <input type="text" class="form-control bg-light rounded-pill" id="employee_title"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="employee_department" class="col-form-label fw-bold">Department:</label>
                            <input type="text" class="form-control bg-light rounded-pill" id="employee_department"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="employee_company" class="col-form-label fw-bold">Company:</label>
                            <input type="text" class="form-control bg-light rounded-pill"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" id="employee_company" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="assignment_type" class="col-form-label fw-bold">Assignment:</label>
                            <select class="form-select rounded-pill @error('assignment_type') is-invalid @enderror"
                                id="assignment_type" name="assignment_type"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                                <option value="">-- Select Assignment --</option>
                                @foreach ($list_assignment_type as $v)
                                    <option value="{{ $v->name }}"
                                        {{ old('assignment_type') == $v->name ? 'selected' : '' }}>
                                        {{ $v->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" id="box_assignment_field" style="display: none;">
                            <label for="assignment_field" class="col-form-label fw-bold ">Field:</label>
                            <select class="form-select rounded-pill @error('assignment_field') is-invalid @enderror"
                                id="assignment_field" name="assignment_field"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                                <option value="">-- Select Field --</option>
                                @foreach ($list_assignment_field as $v)
                                    <option value="{{ $v->name }}"
                                        {{ old('assignment_field') == $v->name ? 'selected' : '' }}>
                                        {{ $v->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label fw-bold">Attachments (Max 5):</label>
                            <div id="attachment_container">
                                <div class="input-group mb-2 attachment-row">
                                    <input type="file" name="file_1_path" class="form-control"
                                        accept=".jpg,.jpeg,.png,.pdf">
                                    <button type="button" class="btn btn-outline-danger btn-remove-file"
                                        style="display: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" id="btn_add_file" class="btn btn-sm btn-info rounded-pill mt-1">
                                <i class="fas fa-plus me-1"></i> Add More File
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="btn_add_to_table" class="btn btn-submit">Save
                                Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @push('scripts')
            <script>
                $(document).ready(function() {
                    const maxFiles = 5;
                    let rowIdx = 0;
                    $('#company_id').select2({
                        placeholder: "-- Select Company --",
                        allowClear: true
                    });
                    if ($('#employee_id').hasClass('select2')) {
                        $('#employee_id').select2({
                            dropdownParent: $('#modal_detail')
                        });
                    }
                    $('#btn_add_file').click(function() {
                        let currentRows = $('.attachment-row').length;
                        if (currentRows < maxFiles) {
                            let nextIndex = currentRows + 1;
                            let newRow = `
                    <div class="input-group mb-2 attachment-row">
                        <input type="file" name="file_${nextIndex}_path" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        <button type="button" class="btn btn-outline-danger btn-remove-file">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`;
                            $('#attachment_container').append(newRow);
                        }
                        checkMaxFiles();
                    });
                    $(document).on('click', '.btn-remove-file', function() {
                        $(this).closest('.attachment-row').remove();
                        reorderFileNames();
                        checkMaxFiles();
                    });

                    function reorderFileNames() {
                        $('#attachment_container .attachment-row').each(function(index) {
                            let fileCount = index + 1;
                            $(this).attr('id', 'row_file_' + fileCount);
                            $(this).find('input[type="file"]').attr('name', 'file_' + fileCount + '_path');

                            if ($('#attachment_container .attachment-row').length === 1) {
                                $(this).find('.btn-remove-file').hide();
                            } else {
                                $(this).find('.btn-remove-file').show();
                            }
                        });
                    }

                    function checkMaxFiles() {
                        if ($('.attachment-row').length >= maxFiles) {
                            $('#btn_add_file').hide();
                        } else {
                            $('#btn_add_file').show();
                        }
                    }
                    $('.btn_add_detail').on('click', function() {
                        let request_date = $('#request_date').val() || '';
                        let company_id = $('#company_id').val() || '';
                        $('#detail_request_date').val(request_date);
                        $('#detail_request_company_id').val(company_id);
                        var myModalEl = document.getElementById('modal_detail');
                        var myModal = bootstrap.Modal.getOrCreateInstance(myModalEl);
                        myModal.show();
                    });
                    $('#employee_id').on('change', function() {
                        var employee_id = $(this).val();
                        if (employee_id) {
                            $.ajax({
                                type: 'GET',
                                url: '/transaction/personnel-assignments/get_employee_detail',
                                data: {
                                    employee_id: employee_id
                                },
                                success: function(data) {
                                    var employee = (typeof data === 'string') ? JSON.parse(data) : data;
                                    $('#employee_title').val(employee.job_position);
                                    $('#employee_department').val(employee.organization);
                                    $('#employee_company').val(employee.company);
                                }
                            });
                        }
                    });
                    $('#btn_add_to_table').on('click', function(e) {
                        e.preventDefault();
                        let empId = $('#employee_id').val();
                        let empName = $('#employee_id option:selected').text().split(' - ')[1];
                        let title = $('#employee_title').val();
                        let dept = $('#employee_department').val();
                        let assignType = $('#assignment_type').val();
                        let assignField = $('#assignment_field').val() || '-';

                        if (!empId || !assignType) {
                            alert('Please select Employee and Assignment Type');
                            return;
                        }

                        rowIdx++; // Menambah index baris agar unik di database

                        let newRow = `
    <tr id="row_${rowIdx}">
        <td class="text-center serial-number"></td>
        <td>${empId} <input type="hidden" name="details[${rowIdx}][employee_id]" value="${empId}"></td>
        <td>${empName}</td>
        <td>${title} <input type="hidden" name="details[${rowIdx}][title]" value="${title}"></td>
        <td>${dept} <input type="hidden" name="details[${rowIdx}][department]" value="${dept}"></td>
        <td>${assignType} <input type="hidden" name="details[${rowIdx}][assignment_type]" value="${assignType}"></td>
        <td>${assignField} <input type="hidden" name="details[${rowIdx}][assignment_field]" value="${assignField}"></td>
        <td class="attachment-cell">
            <div class="file-names-list"></div>
            <div class="hidden-inputs" style="display:none;"></div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger btn-remove-row"><i class="fas fa-trash"></i></button>
        </td>
    </tr>`;

                        let $row = $(newRow);
                        let hasFiles = false;

                        // AMBIL FILE DARI MODAL
                        $('#attachment_container .attachment-row input[type="file"]').each(function(index) {
                            if (this.files && this.files.length > 0) {
                                hasFiles = true;
                                let fileName = this.files[0].name;
                                let fileNum = index + 1; // Akan jadi file_1_path, file_2_path, dst.

                                // Tampilkan nama file di tabel
                                $row.find('.file-names-list').append(
                                    `<div class="small text-truncate" style="max-width: 150px;">
                    <i class="fas fa-paperclip me-1 text-primary"></i>${fileName}
                </div>`
                                );
                                let $clone = $(this).clone();
                                $clone.attr('name', `details[${rowIdx}][file_${fileNum}_path]`);
                                $clone.prop('files', this.files);

                                $row.find('.hidden-inputs').append($clone);
                            }
                        });
                        if (!hasFiles) $row.find('.file-names-list').html('<span class="text-muted">-</span>');
                        $('table tbody').append($row);
                        updateSerialNumbers();
                        bootstrap.Modal.getInstance(document.getElementById('modal_detail')).hide();
                        $('#employee_id').val('').trigger('change');
                        $('#assignment_type').val('').trigger('change');
                        $('#attachment_container').html(`
        <div class="input-group mb-2 attachment-row">
            <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            <button type="button" class="btn btn-outline-danger btn-remove-file" style="display: none;"><i class="fas fa-trash"></i></button>
        </div>
    `);
                        checkMaxFiles();
                    });

                    function updateSerialNumbers() {
                        $('table tbody tr').each(function(index) {
                            $(this).find('.serial-number').text(index + 1);
                        });
                    }
                    $(document).on('click', '.btn-remove-row', function() {
                        if (confirm('Remove this employee?')) {
                            $(this).closest('tr').remove();
                            updateSerialNumbers();
                        }
                    });
                    $('#assignment_type').on('change', function() {
                        if ($(this).val() === 'Tenaga Teknis Pertambangan') {
                            $('#box_assignment_field').fadeIn();
                            $('#assignment_field').prop('required', true);
                        } else {
                            $('#box_assignment_field').fadeOut();
                            $('#assignment_field').prop('required', false).val('');
                        }
                    });
                });
            </script>
        @endpush
    </x-app-layout>
