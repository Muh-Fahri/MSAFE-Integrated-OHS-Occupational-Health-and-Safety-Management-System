<x-app-layout>
    <div id="hazard-detail-container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center">
                    <a href="{{ route('transaction-hazards.index') }}" class="btn fs-4 btn-back">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                    <div class="page-title-right">
                    </div>
                    <h4 class="mb-0">Show Hazard</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="reporter_id" class="col-md-2 col-form-label">Report By (Name)</label>
                            <div class="col-md-10">
                                <select class="form-control bg-light rounded-pill border-2" disabled>
                                    @foreach ($list_user as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $data->reporter_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <label for="assignee_id" class="col-md-2 col-form-label">Assign To (Name)</label>
                            <div class="col-md-10">
                                <select
                                    class="form-control rounded-pill bg-light border-2 @error('assignee_id') is-invalid @enderror"
                                    id="assignee_id" name="assignee_id" disabled>
                                    <option value="">Select Assignee</option>
                                    @foreach ($list_user as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('assignee_id', $data->assignee_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="report_datetime" class="col-md-2 col-form-label">Report Date/Time <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="datetime-local"
                                    class="form-control bg-light rounded-pill border-2 @error('report_datetime') is-invalid @enderror"
                                    name="report_datetime" id="report_datetime" {{-- Gunakan Carbon atau date() dengan pengecekan yang lebih kuat --}}
                                    value="{{ old('report_datetime', $data->report_datetime ? \Carbon\Carbon::parse($data->report_date_time)->format('Y-m-d\TH:i') : '') }}"
                                    readonly>

                                @error('report_datetime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="hazard_source" class="col-md-2 col-form-label">
                                Report By (Department) <span class="text-danger">*</span>
                            </label>

                            <div class="col-md-10">
                                <select name="reporter_department_id" id="reporter_department_id"
                                    class="form-control bg-light rounded-pill border-2 @error('reporter_department_id') is-invalid @enderror"
                                    disabled>

                                    <option value="">Select Department</option>

                                    @foreach ($list_department as $v)
                                        <option value="{{ $v->id }}"
                                            {{ old('reporter_department_id', $data->reporter_department_id ?? '') == $v->id ? 'selected' : '' }}>
                                            {{ $v->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('reporter_department_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="hazard_source" class="col-md-2 col-form-label">Hazard Source</label>
                            <div class="col-md-10">
                                <select class="form-control bg-light rounded-pill border-2" disabled id="hazard_source"
                                    name="hazard_source">
                                    <option value="">Select Hazard Source</option>
                                    @foreach ($hazard_source as $v)
                                        <option value="{{ $v->name }}"
                                            {{ old('hazard_source', $data->hazard_source) == $v->name ? 'selected' : '' }}>
                                            {{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="" class="col-md-2 col-form-label">Location</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control bg-light rounded-pill border-2" readonly
                                    value="{{ $data->location }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="hazard_description" class="col-md-2 col-form-label">Hazard Description <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <textarea rows="4" class="form-control bg-light" readonly name="hazard_description" required>{{ old('hazard_description', $data->hazard_description) }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="immediate_actions" class="col-md-2 col-form-label">Immediate Actions <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <textarea rows="4" class="form-control bg-light" readonly name="immediate_actions" required>{{ old('immediate_actions', $data->immediate_actions) }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="hazard_status" class="col-md-2 col-form-label">Status <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <select name="status" id="hazard_status"
                                    class="rounded-pill bg-light border-2 form-control" disabled>
                                    <option value="">Select Status</option>
                                    @foreach ($list_status as $k => $v)
                                        <option value="{{ $k }}"
                                            {{ old('status', $data->status) == $k ? 'selected' : '' }}>
                                            {{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="corr_actions" style="display: none" class="row mb-3">
                            <label for="corrective_action" class="col-md-2 col-form-label">Corrective Action <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <textarea id="corrective_action_input" rows="3" class="form-control bg-light" readonly
                                    name="corrective_action">{{ old('corrective_action', $data->corrective_action) }}</textarea>
                            </div>
                        </div>

                        <div id="due_date_box" style="display: none" class="row mb-3">
                            <label class="col-md-2 col-form-label">Due Date <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="date" name="due_date"
                                    class="form-control bg-light rounded-pill border-2" readonly
                                    value="{{ old('due_date', $data->due_date ? date('Y-m-d', strtotime($data->due_date)) : '') }}">
                            </div>
                        </div>

                        <div id="completed_date_box" style="display: none" class="row mb-3">
                            <label class="col-md-2 col-form-label">Completed Date <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="date" name="completed_date"
                                    class="form-control bg-light rounded-pill border-2" readonly
                                    value="{{ old('completed_date', $data->completed_date ? date('Y-m-d', strtotime($data->completed_date)) : '') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Attachments</label>
                            <div class="col-md-10">
                                @if ($data->file_1_path || $data->file_2_path || $data->file_3_path || $data->file_4_path)
                                    <div class="mb-3">
                                        <h6>Current Files:</h6>
                                        <div class="d-flex flex-wrap gap-3">
                                            @for ($i = 1; $i <= 4; $i++)
                                                {{-- Ubah jadi 4 jika ada file_4 --}}
                                                @php
                                                    $pathField = "file_{$i}_path";
                                                    $filePath = $data->$pathField;
                                                @endphp

                                                @if ($filePath)
                                                    @php
                                                        // Pecah path (contoh: "hazard/namafile.jpg") menjadi folder dan filename
                                                        $parts = explode('/', $filePath);
                                                        $folder = $parts[0] ?? 'hazard';
                                                        $filename = $parts[1] ?? (isset($parts[0]) ? $parts[0] : '');

                                                        // Jika path di database hanya "namafile.jpg", gunakan folder default 'hazard'
                                                        if (count($parts) == 1) {
                                                            $filename = $parts[0];
                                                            $folder = 'hazard';
                                                        }
                                                    @endphp

                                                        <div class="text-center p-2 border rounded bg-light">
                                                            <a href="{{ route('storage.external', ['folder' => $folder, 'filename' => $filename]) }}"
                                                                target="_blank">
                                                                <img src="{{ route('storage.external', ['folder' => $folder, 'filename' => $filename]) }}"
                                                                    alt="File {{ $i }}"
                                                                    class="img-thumbnail mb-2"
                                                                    style="width: 120px; height: 120px; object-fit: cover;">
                                                            </a>
                                                            <br>
                                                            <small class="text-muted d-block text-truncate"
                                                                style="max-width: 120px;">
                                                                {{ $filename }}
                                                            </small>
                                                        </div>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">No attachments available.</span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-end gap-2 pb-2 pl-3 pr-3"> {{-- Tambahkan padding agar tidak terlalu mepet dengan tepi --}}
                            @if($is_able_to_admin_edit)
                            <a href="{{ route('transaction-hazards.admin_edit', $data->id) }}"
                                class="btn btn-outline-primary px-4 mx-1 shadow-none" title="Edit Data">
                                <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Admin Edit</span>
                            </a>
                            @endif
                            @if($is_able_to_admin_delete)
                            <form action="{{ route('transaction-hazards.destroy', $data->id) }}" method="POST"
                                class="form-delete d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                    class="btn btn-outline-danger px-4 shadow-none btn-delete-confirm">
                                    <i class="fas fa-trash-alt me-2"></i> Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.btn-delete-confirm').on('click', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('form');
                    Swal.fire({
                        title: 'Hapus Asset?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });

                function handleStatusChange() {
                    const val = $('#hazard_status').val();

                    // Reset: Sembunyikan semua box tambahan
                    $('#corr_actions, #due_date_box, #completed_date_box').hide();
                    $('#corr_actions textarea, #due_date_box input, #completed_date_box input').prop('required', false);
                    if (val === 'ACTION_REQUIRED') {
                        $('#corr_actions, #due_date_box').show().css('display', 'flex');
                        $('#corr_actions textarea, #due_date_box input').prop('required', true);
                    } else if (val === 'COMPLETED') {
                        // Tampilkan box Completed Date
                        $('#completed_date_box').show().css('display', 'flex');
                        $('#completed_date_box input').prop('required', true);
                    }
                }

                $('#hazard_status').on('change', handleStatusChange);
                handleStatusChange();
                $('.btn-add-file').click(function() {
                    let newRow = `
                    <div class="d-flex gap-2 mb-2 file-row">
                        <input type="file" name="hazard_files[]" class="form-control">
                        <button type="button" class="btn btn-danger btn-remove-file"><i class="fas fa-trash"></i></button>
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
