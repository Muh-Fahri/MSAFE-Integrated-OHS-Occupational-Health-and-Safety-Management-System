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
                    <h4 class="mb-0">Edit Hazard</h4>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('transaction-hazards.admin_update', $data->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- KOLOM KIRI --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reporter_id" class="form-label fw-bold">Report By (Name) <span
                                        class="text-danger">*</span></label>
                                <select class="form-select rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" id="reporter_id"
                                    name="reporter_id" required>
                                    <option value="">Select Reporter</option>
                                    @foreach ($list_user as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('reporter_id', $data->requestor_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="assignee_id" class="form-label fw-bold">Assign To (Name) <span
                                        class="text-danger">*</span></label>
                                <select
                                    class="form-select rounded-pill text-secondary border-2 @error('assignee_id') is-invalid @enderror"
                                    id="assignee_id" name="assignee_id"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                                    <option value="">Select Assignee</option>
                                    @foreach ($list_user as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('assignee_id', $data->assignee_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="report_datetime" class="form-label fw-bold">Report Date/Time <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    class="form-control rounded-pill border-2" name="report_datetime"
                                    id="report_datetime"
                                    value="{{ old('report_datetime', $data->report_datetime ? \Carbon\Carbon::parse($data->report_date_time)->format('Y-m-d\TH:i') : '') }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label fw-bold">Location <span
                                        class="text-danger">*</span></label>
                                <select style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    class="form-select rounded-pill border-2 text-secondary" id="location"
                                    name="location" required>
                                    <option value="">Select Location</option>
                                    @foreach ($list_location as $loc)
                                        <option value="{{ $loc->name }}"
                                            {{ old('location', $data->location) == $loc->name ? 'selected' : '' }}>
                                            {{ $loc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="hazard_status" class="form-label fw-bold">Status <span
                                        class="text-danger">*</span></label>
                                <select style="border-color: #dee2e6; padding-left: 1.5rem;" name="status"
                                    id="hazard_status" class="form-select rounded-pill text-secondary border-2"
                                    required>
                                    @foreach ($list_status as $k => $v)
                                        <option value="{{ $k }}"
                                            {{ old('status', $data->status) == $k ? 'selected' : '' }}>
                                            {{ $v }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3" id="container_corrective" style="display: none;">
                                <label for="corrective_action" class="form-label fw-bold">Corrective Action <span
                                        class="text-danger">*</span></label>
                                <textarea style="border-color: #dee2e6; padding-left: 1.5rem;" name="corrective_action" id="corrective_action"
                                    class="form-control rounded-5" rows="3">{{ old('corrective_action', $data->corrective_action) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="hazard_description" class="form-label fw-bold"> Hazard Description <span
                                        class="text-danger">*</span> </label>
                                <textarea style="border-color: #dee2e6; padding-left: 1.5rem;" name="hazard_description" class="form-control rounded-5"
                                    rows="3" required>{{ old('hazard_description', $data->hazard_description) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="immediate_actions" class="form-label fw-bold"> Immediate Actions <span
                                        class="text-danger">*</span> </label>
                                <textarea style="border-color: #dee2e6; padding-left: 1.5rem;" name="immediate_actions" class="form-control rounded-5"
                                    rows="3" required>{{ old('immediate_actions', $data->immediate_actions) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Report By (Department)</label>
                                <select style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    class="form-select rounded-pill text-secondary border-2" id="report_dept"
                                    name="reporter_department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($list_department as $v)
                                        <option value="{{ $v->id }}"
                                            {{ old('reporter_department_id', $data->reporter_department_id) == $v->id ? 'selected' : '' }}>
                                            {{ $v->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Assign to (Department)</label>
                                <select style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    class="form-select rounded-pill text-secondary border-2" id="assignee_dept"
                                    name="assignee_department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($list_department as $v)
                                        <option value="{{ $v->id }}"
                                            {{ old('assignee_department_id', $data->assignee_department_id) == $v->id ? 'selected' : '' }}>
                                            {{ $v->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="hazard_source" class="form-label fw-bold">Hazard Source <span
                                        class="text-danger">*</span></label>
                                <select style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    class="form-select rounded-pill text-secondary border-2" name="hazard_source"
                                    required>
                                    <option value="">Select Hazard Source</option>
                                    @foreach ($hazard_source as $v)
                                        <option value="{{ $v->name }}"
                                            {{ old('hazard_source', $data->hazard_source) == $v->name ? 'selected' : '' }}>
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
                                            {{ old('hazard_type', $data->hazard_type) == $v->name ? 'selected' : '' }}>{{ $v->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3" id="container_due_date" style="display: none;">
                                <label for="due_date_input" class="form-label fw-bold">Due Date <span
                                        class="text-danger">*</span></label>
                                <input style="border-color: #dee2e6; padding-left: 1.5rem;" type="date"
                                    name="due_date" id="due_date_input" class="form-control rounded-pill"
                                    value="{{ old('due_date', $data->due_date ? date('Y-m-d', strtotime($data->due_date)) : '') }}">
                            </div>

                            <div id="completed_date" class="mb-3" style="display: none;">
                                <label class="form-label fw-bold">Completed Date <span
                                        class="text-danger">*</span></label>
                                <input style="border-color: #dee2e6; padding-left: 1.5rem;" type="date"
                                    name="completed_date" id="completed_date_input" class="form-control rounded-pill"
                                    value="{{ old('completed_date', $data->completed_date ? date('Y-m-d', strtotime($data->completed_date)) : '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Attachments</label>
                                {{-- Bagian Preview Gambar (Gunakan route storage.external) --}}
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    @for ($i = 1; $i <= 4; $i++)
                                        @php
                                            $column = "file_{$i}_path";
                                            $fullPath = $data->$column;
                                        @endphp
                                        @if ($fullPath)
                                            @php
                                                $cleanPath = trim($fullPath, '/');
                                            @endphp
                                            <div class="text-center">
                                                {{-- Kirim fullPath ke parameter 'filename' --}}
                                                <a href="{{ route('storage.external', ['folder' => 'hazard', 'filename' => $cleanPath]) }}"
                                                    target="_blank">
                                                    <img src="{{ route('storage.external', ['folder' => 'hazard', 'filename' => $cleanPath]) }}"
                                                        alt="File {{ $i }}" class="img-thumbnail mb-2"
                                                        style="width: 120px; height: 120px; object-fit: cover;">
                                                </a>
                                                <div class="mt-1 small text-muted">File {{ $i }}</div>
                                            </div>
                                        @endif
                                    @endfor
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="small mb-1 fw-bold">Update File 1</label>
                                        <input type="file" name="file_1_path"
                                            class="form-control form-control-sm rounded-pill">
                                    </div>
                                    <div class="col-6">
                                        <label class="small mb-1 fw-bold">Update File 2</label>
                                        <input type="file" name="file_2_path"
                                            class="form-control form-control-sm rounded-pill">
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="small mb-1 fw-bold">Update File 3</label>
                                        <input type="file" name="file_3_path"
                                            class="form-control form-control-sm rounded-pill">
                                    </div>
                                    <div class="col-6">
                                        <label class="small mb-1 fw-bold">Update File 4</label>
                                        <input type="file" name="file_4_path"
                                            class="form-control form-control-sm rounded-pill">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-4 mt-2">
                                            <span class="text-muted">You can upload a maximum of 2MB each files</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 border-top pt-3 text-end">
                        <a href="{{ route('transaction-hazards.index') }}"
                            class="btn btn-light px-4 btn-cancel border">Cancel</a>
                        <button type="submit" class="btn btn-submit px-4 shadow-sm rounded-pill">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#reporter_id, #assignee_id, #assignee_dept, #report_dept, #location').select2({
                    width: '100%'
                });
                $('.btn-cancel').on('click', function(e) {
                    e.preventDefault();
                    const targetUrl = $(this).attr('href');

                    Swal.fire({
                        title: 'Discard Changes?',
                        text: "Are you sure you want to cancel? Any unsaved data will be lost.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, discard it!',
                        cancelButtonText: 'No, stay here'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = targetUrl;
                        }
                    });
                });

                function toggleFields() {
                    const val = $('#hazard_status').val();
                    $('#container_corrective, #container_due_date, #completed_date').hide();
                    $('#corrective_action, #due_date_input, #completed_date_input').prop('required', false);

                    if (val === 'ACTION_REQUIRED') {
                        $('#container_corrective, #container_due_date').show();
                        $('#corrective_action, #due_date_input').prop('required', true);
                    } else if (val === 'COMPLETED') {
                        $('#completed_date').show();
                        $('#completed_date_input').prop('required', true);
                    }
                }

                $('#hazard_status').on('change', toggleFields);
                toggleFields();
            });
        </script>
    @endpush
</x-app-layout>
