<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <a href="{{ route('transaction-correctiveAction.index') }}" class="btn fs-4 btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Show Corrective Action</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Source</label>
                            <input type="text" name="source_no" readonly
                                class="bg-light form-control rounded-pill border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ $corr->source_no }}" placeholder="Source No">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Risk Issue</label>
                            <input type="date" name="risk_issuer_date" readonly
                                class="form-control bg-light rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ $corr->risk_issue_date ? \Carbon\Carbon::parse($corr->risk_issue_date)->format('Y-m-d') : '' }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Description of Risk Issue</label>
                            <input type="text" name="risk_description" readonly
                                class="bg-light form-control rounded-pill border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ $corr->risk_description }}" placeholder="Enter description">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location</label>
                            <select name="location" disabled
                                class="bg-light form-control rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;">
                                <option value="">Select Location</option>
                                @foreach ($loc as $l)
                                    <option value="{{ $l->name }}"
                                        {{ $corr->location == $l->name ? 'selected' : '' }}>
                                        {{ $l->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" disabled
                                class="bg-light form-control rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;">
                                <option value="">Select Department</option>
                                @foreach ($depart as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ $corr->department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Responsible Person</label>
                            <select name="responsible_person_id"
                                class="form-control bg-light rounded-pill text-secondary border-2" disabled
                                style="border-color: #dee2e6; padding-left: 1.5rem;">
                                <option value="">Select Responsible</option>
                                @foreach ($respon as $res)
                                    <option value="{{ $res->id }}"
                                        {{ $corr->responsible_person_id == $res->id ? 'selected' : '' }}>
                                        {{ $res->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Corrective Action</label>
                            <input type="text" name="corrective_action"
                                class="form-control bg-light rounded-pill border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" readonly
                                value="{{ $corr->corrective_action }}" placeholder="Enter action">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control rounded-pill border-2 bg-light"
                                readonly style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ $corr->due_date ? \Carbon\Carbon::parse($corr->due_date)->format('Y-m-d') : '' }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary">Status</label>
                            <select name="status" class="form-control rounded-pill bg-light text-secondary border-2"
                                disabled style="border-color: #dee2e6; padding-left: 1.5rem;">

                                <option value="">Select Action</option>

                                {{-- Jika status di DB adalah ACTION_REQUIRED, otomatis terpilih --}}
                                <option value="ACTION_REQUIRED"
                                    {{ $corr->status == 'ACTION_REQUIRED' ? 'selected' : '' }}>
                                    Action Required
                                </option>

                                {{-- Jika status di DB adalah COMPLETED, otomatis terpilih --}}
                                <option value="COMPLETED" {{ $corr->status == 'COMPLETED' ? 'selected' : '' }}>
                                    Completed
                                </option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <div>
                <h6 class="mb-0 fw-bold text-muted">
                    <i class="fas fa-paperclip me-2"></i>Evidences
                </h6>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Action Taken</label>
                    <textarea style="border-color: #dee2e6; padding-left: 1.5rem;" name="action_taken" class="form-control rounded-5" rows="3" readonly>{{ $corr->action_taken }}</textarea>
                </div>
                @forelse($corr->evidences as $ev)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 border shadow-none overflow-hidden hover-shadow">
                            <div class="position-relative">
                                <a href="{{ route('storage.external', ['folder' => 'corrective_action', 'filename' => $ev->file_path]) }}"
                                    target="_blank">
                                    <img src="{{ route('storage.external', ['folder' => 'corrective_action', 'filename' => $ev->file_path]) }}"
                                        class="card-img-top" alt="Evidence" style="height: 160px; object-fit: cover;">
                                </a>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-dark bg-opacity-50 blur-filter small">
                                        {{ strtoupper(pathinfo($ev->file_path, PATHINFO_EXTENSION)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body p-2">
                                <p class="card-text small mb-1 text-truncate" title="{{ $ev->remarks }}">
                                    <strong>Remarks:</strong> {{ $ev->remarks ?? 'No description' }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted" style="font-size: 0.75rem;">
                                        <i class="far fa-clock me-1"></i>{{ $ev->created_at->diffForHumans() }}
                                    </span>
                                    <a href="{{ route('storage.external', ['folder' => 'corrective_action', 'filename' => $ev->file_path]) }}"
                                        class="btn btn-link btn-sm p-0 text-decoration-none" target="_blank">
                                        View <i class="fas fa-external-link-alt ms-1" style="font-size: 0.7rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5 border border-dashed rounded bg-light">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="empty"
                                style="width: 60px; opacity: 0.5;">
                            <p class="mt-3 text-muted">No evidence has been uploaded yet.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex gap-2">
                            @if($corr->next_action=='INPUT_EVIDENCE' && (in_array($user->id, $next_user_ids) || $delegated))
                                <a href="javascript:void(0)" class="btn btn-success btn-action" data-status="ACTION_REQUIRED" data-action="INPUT_EVIDENCE">INPUT EVIDENCE</a> 
                            @endif
                            @if($corr->status=='APPROVAL_REQUIRED' && (in_array($user->id, $next_user_ids) || $delegated))
                                <a href="javascript:void(0)" class="btn btn-success btn-action" data-status="APPROVAL_REQUIRED" data-action="APPROVE" style="margin-left: 4px;">APPROVE</a> 
                                <a href="javascript:void(0)" class="btn btn-danger btn-action" data-status="APPROVAL_REQUIRED" data-action="REJECT" style="margin-left: 4px;">REJECT</a> 
                                <a href="javascript:void(0)" class="btn btn-warning btn-action" data-status="APPROVAL_REQUIRED" data-action="CHANGE_ASSIGNEE" style="margin-left: 4px;">CHANGE ASSIGNEE</a> 
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="win-approval" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">{{ $corr->next_action=='INPUT_EVIDENCE' ? 'Input Evidence' : 'Approval' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('transaction-correctiveAction.approve', $corr->id) }}" method="POST"
                    enctype="multipart/form-data" id="form-approval">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="id" value="{{ $corr->id }}">
                    <input type="hidden" name="action" value="" id="js-action">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="Catatan bukti..."></textarea>
                        </div>

                        @if($corr->next_action=='INPUT_EVIDENCE')
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload File 1 (Max 2Mb)</label>
                            <input type="file" name="file_1" class="form-control mb-2" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload File 2 (Max 2Mb)</label>
                            <input type="file" name="file_2" class="form-control mb-2" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload File 3 (Max 2Mb)</label>
                            <input type="file" name="file_3" class="form-control mb-2" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload File 4 (Max 2Mb)</label>
                            <input type="file" name="file_4" class="form-control mb-2" accept="image/*">
                        </div>
                        @endif
                        @if($corr->next_action=='APPROVAL')
                        <div class="box-change-assignee">
                            <div class="mb-3 d-flex flex-column">
                                <label for="department_id" class="form-label fw-bold mb-1">First Aid Trainer:</label>
                                <select class="form-control select2 @error('department_id') is-invalid @enderror"
                                    id="department_id" name="department_id" style="width: 100%;" required>
                                    @foreach ($list_department as $department_id => $user_name)
                                        <option value="{{ $department_id }}"
                                            {{ old('department_id', $corr->department_id) == $department_id ? 'selected' : '' }}>
                                            {{ $user_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 d-flex flex-column">
                                <label for="responsible_person_id" class="form-label fw-bold mb-1">Responsible Person:</label>
                                <select class="form-control select2 @error('responsible_person_id') is-invalid @enderror"
                                    id="responsible_person_id" name="responsible_person_id" style="width: 100%;" required>
                                    @foreach ($list_user as $user_id => $user_name)
                                        <option value="{{ $user_id }}"
                                            {{ old('responsible_person_id', $corr->responsible_person_id) == $user_id ? 'selected' : '' }}>
                                            {{ $user_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsible_person_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>		
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.btn-action').on('click', function(){
                var status = $(this).data('status');
                var action = $(this).data('action');
                if(action=='CHANGE_ASSIGNEE'){
                    $('.box-change-assignee').show();
                } else {
                    $('.box-change-assignee').hide();
                }
                $('#js-action').val(action);
                $('#win-approval').modal('show');
            });
            $("#form-approval").submit(function () {
                $(".btn-save").attr("disabled", true);
                return true;
            });
        });
    </script>
    @endpush
</x-app-layout>
