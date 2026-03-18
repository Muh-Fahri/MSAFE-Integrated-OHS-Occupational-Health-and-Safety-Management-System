<x-app-layout>
    <style>
        label,
        .col-form-label {
            color: rgba(0, 0, 0, 0.6) !important;
            font-weight: 500;
        }

        .form-control::placeholder,
        .form-select::placeholder,
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: rgba(0, 0, 0, 0.2) !important;
            opacity: 1;
        }

        .form-control,
        .form-select {
            border-color: rgba(0, 0, 0, 0.15);
        }
    </style>
    <div id="hazard-detail-container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center gap-2">
                    <div class="page-title-left">
                        <a href="{{ route('transaction-hazards.index') }}" class="btn-back fs-4">
                            <i class="fas fa-arrow-left me-1"></i>
                        </a>
                    </div>
                    <h4 class="mb-0">Add New Hazard</h4>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="card border-0 shadow-sm mb-4" style="border-left: 5px solid #dc3545;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-exclamation-triangle text-danger me-2" style="font-size: 1.2rem;"></i>
                                <h6 class="card-title mb-0 text-danger fw-bold">Terjadi Kesalahan Input</h6>
                            </div>
                            <ul class="mb-0 ps-3 text-secondary" style="font-size: 0.9rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <form action="{{ route('transaction-hazards.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reporter_id" class="form-label fw-bold" small>Report By (Name) <span
                                        class="text-danger">*</span></label>
                                <select class="form-select rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" id="reporter_id"
                                    name="reporter_id" required>
                                    <option value="">Select Reporter</option>
                                    @foreach ($list_user as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('reporter_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reporter_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="assignee_id" class="form-label fw-bold">Assign To (Name) <span
                                        class="text-danger">*</span></label>
                                <select
                                    class="form-select rounded-pill text-secondary border-2  @error('assignee_id') is-invalid @enderror"
                                    id="assignee_id" name="assignee_id"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                                    <option value="">Select Assignee</option>
                                    @foreach ($list_user as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('assignee_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assignee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="report_datetime" class="form-label fw-bold">Report Date/Time <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    class="form-control rounded-pill border-2 @error('report_datetime') is-invalid @enderror"
                                    name="report_datetime" id="report_datetime" value="{{ old('report_datetime') }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Location <span class="text-danger">*</span></label>
                                <select name="location" id="location_test"
                                    class="form-select rounded-pill border-2 text-secondary @error('location') is-invalid @enderror"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                                    <option value="">Select Location</option>
                                    @foreach ($list_location as $loc)
                                        <option value="{{ $loc->name }}"
                                            {{ old('location') == $loc->name ? 'selected' : '' }}>
                                            {{ $loc->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location')
                                    <div class="invalid-feedback ps-3">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="hazard_status" class="form-label fw-bold">Status <span
                                        class="text-danger">*</span></label>
                                <select style="border-color: #dee2e6; padding-left: 1.5rem;" name="status"
                                    id="hazard_status" class="form-select rounded-pill text-secondary border-2"
                                    required>
                                    @foreach ($list_status as $k=>$v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-3">
                                <label for="hazard_description" class="form-label fw-bold"> Hazard Description <span
                                        class="text-danger">*</span> </label>
                                <textarea style="border-color: #dee2e6; padding-left: 1.5rem;" name="hazard_description" class="form-control rounded-5"
                                    id="" cols="5" rows="3">{{ old('hazard_description') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="immediate_actions" class="form-label fw-bold"> Immediate Actions <span
                                        class="text-danger">*</span> </label>
                                <textarea style="border-color: #dee2e6; padding-left: 1.5rem;" name="immediate_actions"
                                    class="form-control rounded-5" id="" cols="5" rows="3">{{ old('immediate_actions') }}</textarea>
                            </div>
                            <div class="mb-3" id="container_corrective" style="display: none;">
                                <label for="corrective_action" class="form-label fw-bold">Corrective Action <span
                                        class="text-danger">*</span></label>
                                <textarea style="border-color: #dee2e6; padding-left: 1.5rem;" name="corrective_action" id="corrective_action"
                                    class="form-control rounded-5" rows="3">{{ old('corrective_action') }}</textarea>
                            </div>
                            
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="fw-bold">Report By (Department)</label>
                                <select style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    class="form-select rounded-pill text-secondary border-2" id="report_depart"
                                    name="reporter_department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($list_department as $v)
                                        <option value="{{ $v->id }}"
                                            {{ old('reporter_department_id', $hazardReport->reporter_department_id ?? '') == $v->id ? 'selected' : '' }}>
                                            {{ $v->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="" class="fw-bold">Assign to (Department)</label>
                                <select style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    class="form-select rounded-pill text-secondary border-2" id="assign_dept"
                                    name="assignee_department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($list_department as $v)
                                        <option value="{{ $v->id }}"
                                            {{ old('assigne_department_id', $hazardReport->reporter_department_id ?? '') == $v->id ? 'selected' : '' }}>
                                            {{ $v->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="hazard_source" class="form-label fw-bold">Hazard Source <span
                                        class="text-danger">*</span></label>
                                <select style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    class="form-select rounded-pill text-secondary border-2" id="hazard_source"
                                    name="hazard_source" required>
                                    <option value="">Select Hazard Source</option>
                                    @foreach ($list_hazard_source as $v)
                                        <option value="{{ $v->name }}"
                                            {{ old('hazard_source') == $v->name ? 'selected' : '' }}>
                                            {{ $v->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="hazard_type" class="form-label fw-bold">Hazard Type <span
                                        class="text-danger">*</span></label>
                                <select style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    class="form-select rounded-pill text-secondary border-2" id="hazard_type"
                                    name="hazard_type" required>
                                    <option value="">Select Hazard Type</option>
                                    @foreach ($list_hazard_type as $v)
                                        <option value="{{ $v->name }}"
                                            {{ old('hazard_type') == $v->name ? 'selected' : '' }}>{{ $v->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- DUE DATE DI BAWAH LOCATION (KANAN) --}}
                            <div class="mb-3" id="container_due_date" style="display: none;">
                                <label for="due_date_input" class="form-label fw-bold">Due Date <span
                                        class="text-danger">*</span></label>
                                <input style="border-color: #dee2e6; padding-left: 1.5rem;" type="date"
                                    name="due_date" id="due_date_input" class="form-control rounded-pill">
                            </div>
                            <div id="completed_date" class="mb-3" style="display: none;">
                                <label class="form-label fw-bold">Completed Date <span
                                        class="text-danger">*</span></label>
                                <input style="border-color: #dee2e6; padding-left: 1.5rem;" type="date"
                                    name="completed_date" id="completed_date_input"
                                    class="form-control rounded-pill">
                            </div>

                            <div class="row g-3"> {{-- g-3 memberikan jarak antar kolom --}}
                                <div class="col-12 col-md-6">
                                    <label for="file_1" class="form-label fw-bold">File 1</label>
                                    <input type="file" id="file_1" name="file_1_path"
                                        class="form-control rounded-pill">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="file_2" class="form-label fw-bold">File 2</label>
                                    <input type="file" id="file_2" name="file_2_path"
                                        class="form-control rounded-pill">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="file_3" class="form-label fw-bold">File 3</label>
                                    <input type="file" id="file_3" name="file_3_path"
                                        class="form-control rounded-pill">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="file_4" class="form-label fw-bold">File 4</label>
                                    <input type="file" id="file_4" name="file_4_path"
                                        class="form-control rounded-pill">
                                </div>
                                <div class="mb-4">
                                    <span class="text-muted">You can upload a maximum of 2MB each files</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 border-top pt-3 text-end gap-3">
                        <a href="{{ route('transaction-hazards.create') }}" id="btn-cancel"
                            class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-save me-1"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#reporter_id, #assignee_id, #location_test, #report_depart, #assign_dept, #location').select2({
                    width: '100%'
                });
                // --- Kode Swal untuk Cancel ---
                $('#btn-cancel').on('click', function(e) {
                    e.preventDefault(); // Berhenti dulu, jangan langsung pindah halaman
                    const link = $(this).attr('href'); // Ambil url tujuannya

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Any unsaved changes will be lost!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, cancel it!',
                        cancelButtonText: 'No, stay here'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = link;
                        }
                    });
                });
                $('#hazard_status').on('change', function() {
                    const val = $(this).val();
                    $('#container_corrective, #container_due_date, #completed_date').hide();
                    $('#corrective_action, #due_date_input, #completed_date_input').prop('required', false);

                    if (val === 'ACTION_REQUIRED') {
                        $('#container_corrective, #container_due_date').show();
                        $('#corrective_action, #due_date_input').prop('required', true);
                    } else if (val === 'COMPLETED') {
                        $('#completed_date').show();
                        $('#completed_date_input').prop('required', true);
                    }
                }).trigger('change');
                $('.btn-add-file').click(function() {
                    let newRow = `
            <div class="d-flex gap-2 mb-2 file-row">
                <input style="border-color: #dee2e6; padding-left: 1.5rem;"
                       type="file" name="hazard_files[]" class="form-control rounded-pill">
                <button type="button" class="btn btn-danger btn-remove-file">
                    <i class="fas fa-trash"></i>
                </button>
            </div>`;
                    $('#file-container').append(newRow);
                });

                $(document).on('click', '.btn-remove-file', function() {
                    $(this).closest('.file-row').remove();
                });
            });
        </script>
    @endpush

</x-app-layout>
