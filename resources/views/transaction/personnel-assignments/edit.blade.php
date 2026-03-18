<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-left">
                    <h4 class="mb-0">
                        <a href="{{ route('transaction-personnel-assignments.index') }}">
                            <a href="{{ route('transaction-personnel-assignments.index') }}" class="btn-back fs-4">
                                <i class="fas fa-arrow-left me-1"></i>
                            </a>
                        </a>
                        Assignment
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form action="{{ route('transaction-personnel-assignments.update', $data->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="request_no" class="col-form-label fw-bold">Request No</label>
                                    <input type="text" class="form-control bg-light rounded-pill"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" name="request_no"
                                        id="request_no" value="{{ $data->request_no }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="requestor_name" class="col-form-label fw-bold">Requestor</label>
                                    <input type="text" class="form-control bg-light rounded-pill"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" name="requestor_name"
                                        id="requestor_name" value="{{ $data->requestor_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="request_date" class="col-form-label fw-bold">Request Date<span
                                            class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control rounded-pill @error('request_date') is-invalid @enderror"
                                        name="request_date" style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        id="request_date" value="{{ old('request_date', $data->request_date) }}"
                                        required>
                                    @error('request_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="company_id" class="col-form-label fw-bold">Company <span
                                            class="text-danger">*</span></label>
                                    <div>
                                        <select
                                            class="form-control select2 rounded-pill @error('company_id') is-invalid @enderror"
                                            id="company_id" name="company_id"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                                            <option value="">-- Select Company --</option>
                                            @foreach ($list_company as $company)
                                                <option value="{{ $company->id }}"
                                                    {{ old('company_id', $data->company_id) == $company->id ? 'selected' : '' }}>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('company_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_detail as $k => $v)
                                            <tr>
                                                <td>{{ $k + 1 }}</td>
                                                <td>{{ $v->employee_id }}</td>
                                                <td>{{ $v->employee_name }}</td>
                                                <td>{{ $v->employee_title }}</td>
                                                <td>{{ $v->employee_department }}</td>
                                                <td>{{ $v->assignment_type }}</td>
                                                <td>{{ $v->assignment_field }}</td>

                                                <td>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @php $hasFile = false; @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @php $pathField = "file_{$i}_path"; @endphp
                                                            @if (!empty($v->$pathField))
                                                                @php $hasFile = true; @endphp
                                                                <a href="{{ route('storage.external', ['filename' => $v->$pathField]) }}"
                                                                    target="_blank"
                                                                    class="btn btn-outline-info btn-sm p-1"
                                                                    title="View File {{ $i }}"
                                                                    style="font-size: 10px; line-height: 1;">
                                                                    <i class="fas fa-paperclip"></i>
                                                                    F{{ $i }}
                                                                </a>
                                                            @endif
                                                        @endfor

                                                        @if (!$hasFile)
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </div>
                                                </td>

                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm rounded-pill"
                                                        onclick="confirmDelete('{{ $v->id }}')">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="9" align="center">
                                                <button type="button" class="btn btn-add" data-bs-toggle="modal"
                                                    data-bs-target="#myModal">ADD</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row text-end">
                            <div class="col-md-12">
                                <a href="{{ route('transaction-personnel-assignments.index') }}"
                                    class="btn btn-cancel">Cancel</a>
                                <button type="submit" class="btn btn-submit">
                                    <i class="fas fa-save me-1"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL STORE DETAIL (DILUAR FORM UTAMA) --}}
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('transaction-personnel-assignments.store_detail') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="assignment_id" value="{{ $data->id }}" />
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="employee_id" class="col-form-label fw-bold">Employee ID / Name:</label>
                            <div>
                                <select class="form-control rounded-pill @error('employee_id') is-invalid @enderror"
                                    id="employee_id" style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    name="employee_id" required>
                                    <option value="">-- Select Employee --</option>
                                    @foreach ($list_employee as $employee)
                                        <option value="{{ $employee->employee_id }}"
                                            {{ old('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                                            {{ $employee->employee_id . ' - ' . $employee->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="employee_title" class="col-form-label fw-bold">Title:</label>
                            <input type="text" name="employee_title" class="form-control bg-light rounded-pill"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" id="employee_title" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="employee_department" class="col-form-label fw-bold">Department:</label>
                            <input type="text" name="employee_department"
                                class="form-control bg-light rounded-pill" id="employee_department"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="employee_company" class="col-form-label fw-boldl">Company:</label>
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
                            <label for="assignment_field" class="col-form-label fw-bold">Field:</label>
                            <select class="form-select rounded-pill @error('assignment_field') is-invalid @enderror"
                                id="assignment_field" style="border-color: #dee2e6; padding-left: 1.5rem;"
                                name="assignment_field">
                                <option value="">-- Select Field --</option>
                                @foreach ($list_assignment_field as $v)
                                    <option value="{{ $v->name }}"
                                        {{ old('assignment_field') == $v->name ? 'selected' : '' }}>
                                        {{ $v->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <hr class="my-4">
                        <h6 class="fw-bold mb-3"><i class="fas fa-paperclip me-2"></i>Attachments (Max 5 Files)</h6>
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="mb-3">
                                <label for="file_{{ $i }}_path"
                                    class="col-form-label small fw-bold text-muted">File {{ $i }}:</label>

                                <div id="existing_file_{{ $i }}_box" class="mb-1"
                                    style="display: none;">
                                    <span class="badge bg-info text-dark">
                                        <i class="fas fa-file-alt me-1"></i>
                                        <span id="label_file_{{ $i }}"></span>
                                    </span>
                                    <small class="text-muted d-block mt-1">Leave empty if no changes.</small>
                                </div>

                                <input type="file" name="file_{{ $i }}_path"
                                    id="file_{{ $i }}_path" class="form-control rounded-pill"
                                    style="border-color: #dee2e6; font-size: 0.85rem;">
                            </div>
                        @endfor
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel waves-effect"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-submit waves-effect waves-light">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- FORM DELETE SILUMAN --}}
    <form id="form-delete-detail" action="" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            // Fungsi Delete yang bener
            function confirmDelete(id) {
                if (confirm('Are you sure you want to delete this detail?')) {
                    let url = "{{ route('transaction-personnel-assignments.destroy_detail', ':id') }}";
                    url = url.replace(':id', id);
                    let form = document.getElementById('form-delete-detail');
                    form.action = url;
                    form.submit();
                }
            }

            $(document).ready(function() {
                $('#company_id').select2({
                    width: '100%'
                });
                $('#employee_id').select2({
                    width: '100%',
                    dropdownParent: $('#myModal')
                });

                $('#employee_id').on('change', function() {
                    var employee_id = $('#employee_id').val() || '';
                    $.ajax({
                        type: 'GET',
                        url: '/transaction/personnel-assignments/get_employee_detail?employee_id=' +
                            employee_id
                    }).then(function(data) {
                        var employee = JSON.parse(data);
                        $('#employee_title').val(employee.job_position);
                        $('#employee_department').val(employee.organization);
                        $('#employee_company').val(employee.company);
                    });
                });

                $('#assignment_type').on('change', function() {
                    var assignment_type = $(this).val() || '';
                    if (assignment_type == 'Tenaga Teknis Pertambangan') {
                        $('#box_assignment_field').show();
                        $('#assignment_field').prop('required', true);
                    } else {
                        $('#box_assignment_field').hide();
                        $('#assignment_field').prop('required', false);
                    }
                });
                $('.btn-edit-detail').on('click', function() {
                    let file1 = $(this).data('file1');
                    let file2 = $(this).data('file2');
                    let file3 = $(this).data('file3');
                    let file4 = $(this).data('file4');
                    let file5 = $(this).data('file5');
                    for (let i = 1; i <= 5; i++) {
                        let fileName = $(this).data('file' + i);
                        if (fileName) {
                            $('#existing_file_' + i + '_box').show();
                            $('#label_file_' + i).text(fileName);
                        } else {
                            $('#existing_file_' + i + '_box').hide();
                        }
                    }
                    $('#myModal').modal('show');
                });
            });
        </script>
    @endpush
</x-app-layout>
