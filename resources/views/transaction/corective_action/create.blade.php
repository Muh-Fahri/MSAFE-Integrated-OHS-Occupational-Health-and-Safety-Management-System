<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center gap-2 mb-3">
                <a href="{{ route('transaction-correctiveAction.index') }}" class="btn fs-4 btn-back btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Create Corrective Action</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4"
                            role="alert">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle mt-1"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="alert-heading fs-6 fw-bold">Periksa kembali inputan Anda:</h5>
                                    <ul class="mb-0 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('transaction-correctiveAction.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Source</label>
                                <select name="source" class="form-select rounded-pill text-secondary border-2">
                                    <option value="">Select Source</option>
                                    <option value="AUDIT" {{ old('source') == 'AUDIT' ? 'selected' : '' }}>Audit
                                    </option>
                                    <option value="MEETING" {{ old('source') == 'MEETING' ? 'selected' : '' }}>Meeting
                                    </option>
                                    <option value="OTHER" {{ old('source') == 'OTHER' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Risk Issue</label>
                                <input type="date" name="risk_issue_date" value="{{ old('risk_issue_date') }}"
                                    class="form-control rounded-pill text-secondary border-2">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Description of Risk Issue/Hazard</label>
                                <input type="text" name="risk_description" value="{{ old('risk_description') }}"
                                    class="form-control rounded-pill text-secondary border-2"
                                    placeholder="Enter description">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <select name="location" class="form-select rounded-pill text-secondary border-2">
                                    <option value="">Select locations</option>
                                    @foreach ($loc as $l)
                                        <option value="{{ $l->name }}"
                                            {{ old('location') == $l->name ? 'selected' : '' }}>
                                            {{ $l->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department</label>
                                <select name="department_id" class="form-select rounded-pill text-secondary border-2">
                                    <option value="">Select departments</option>
                                    @foreach ($depart as $dept)
                                        <option value="{{ $dept->id }}"
                                            {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Responsible Person</label>
                                <select name="responsible_person_id"
                                    class="form-select rounded-pill text-secondary border-2">
                                    <option value="">Select person</option>
                                    @foreach ($respon as $res)
                                        <option value="{{ $res->id }}"
                                            {{ old('responsible_person_id') == $res->id ? 'selected' : '' }}>
                                            {{ $res->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Corrective Action</label>
                                <input type="text" name="corrective_action" value="{{ old('corrective_action') }}"
                                    class="form-control rounded-pill text-secondary border-2"
                                    placeholder="Enter action taken">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date Required for Completion (Due Date)</label>
                                <input type="date" name="due_date" value="{{ old('due_date') }}"
                                    class="form-control rounded-pill text-secondary border-2">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select rounded-pill text-secondary border-2">
                                    <option value="ACTION_REQUIRED"
                                        {{ old('status') == 'ACTION_REQUIRED' ? 'selected' : '' }}>Action Required
                                    </option>
                                    <option value="COMPLETED" {{ old('status') == 'COMPLETED' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('transaction-correctiveAction.create') }}"
                                class="btn btn-cancel">Cancel</a>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save me-1"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .form-label {
                font-weight: 600;
                color: #495057;
            }

            .card {
                border-radius: 0.75rem;
                border: none;
            }

            .form-control,
            .form-select {
                border-radius: 0.5rem;
                padding: 0.6rem 0.75rem;
            }
        </style>
    @endpush
</x-app-layout>
