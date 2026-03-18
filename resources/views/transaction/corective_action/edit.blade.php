<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <a href="{{ route('transaction-correctiveAction.index') }}" class="btn fs-4 btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Edit Corrective Action</h4>
            </div>
        </div>
    </div>
    <form action="{{ route('transaction-correctiveAction.update', $corr->id) }}" method="POST" name="type">
        @csrf
        @method('PUT')
        {{-- @if ($corr->next_action === 'INPUT_EVIDENCE')
            <input type="hidden" name="type" value="input_evidence">
        @endif --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Edit Form Details: {{ $corr->source_no }}</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Source</label>
                                @php
                                    // Cek apakah source adalah nilai khusus/sistem
                                    $isSystemSource =
                                        $corr->source && !in_array($corr->source, ['AUDIT', 'MEETING', 'OTHER']);
                                @endphp

                                <select name="source"
                                    class="form-select rounded-pill text-secondary border-2 {{ $isSystemSource ? 'bg-light' : '' }}"
                                    style="border-color: #dee2e6; padding-left: 1.5rem; {{ $isSystemSource ? 'pointer-events: none;' : '' }}"
                                    {{ $isSystemSource ? 'tabindex=-1' : '' }}>

                                    <option value="">Select Source</option>

                                    @if ($isSystemSource)
                                        {{-- Jika system source, hanya tampilkan ini saja agar benar-benar "readonly" secara visual --}}
                                        <option value="{{ $corr->source }}" selected>{{ $corr->source }}</option>
                                    @else
                                        {{-- Jika bukan system source, tampilkan pilihan normal --}}
                                        <option value="AUDIT"
                                            {{ old('source', $corr->source) == 'AUDIT' ? 'selected' : '' }}>Audit
                                        </option>
                                        <option value="MEETING"
                                            {{ old('source', $corr->source) == 'MEETING' ? 'selected' : '' }}>Meeting
                                        </option>
                                        <option value="OTHER"
                                            {{ old('source', $corr->source) == 'OTHER' ? 'selected' : '' }}>Other
                                        </option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Risk Issue</label>
                                <input type="date" name="risk_issuer_date"
                                    class="form-control rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    value="{{ $corr->risk_issue_date ? \Carbon\Carbon::parse($corr->risk_issue_date)->format('Y-m-d') : '' }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Description of Risk Issue</label>
                                <input type="text" name="risk_description" class="form-control rounded-pill border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    value="{{ $corr->risk_description }}" placeholder="Enter description">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <select name="location" class="form-select rounded-pill text-secondary border-2"
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
                                <select name="department_id" class="form-select rounded-pill text-secondary border-2"
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
                                    class="form-control rounded-pill text-secondary border-2"
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
                                    class="form-control rounded-pill border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    value="{{ $corr->corrective_action }}" placeholder="Enter action">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Due Date</label>
                                <input type="date" name="due_date" class="form-control rounded-pill border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    value="{{ $corr->due_date ? \Carbon\Carbon::parse($corr->due_date)->format('Y-m-d') : '' }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-secondary">Status</label>
                                <select name="status" class="form-select rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;">

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
                        <div class="row">
                            <div class="col-md-6 mb-3">

                            </div>
                        </div>


                    </div>
                </div>
                {{-- <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="text-muted">Input Evidence</h4>
                        </div>
                        <div class="mb-3">
                            <label for="">Remarks</label>
                            <textarea name="remark[]" id="" cols="30" class="form-control rounded-5 shadow" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="">File</label>
                            <input type="file" class="rounded-pill form-control shadows" name="file_path[]">
                        </div>
                    </div>
                </div> --}}
                <div class="d-flex justify-content-end gap-2 mt-4 mb-4">
                    <a href="{{ route('transaction-correctiveAction.edit', $corr->id) }}" class="btn btn-cancel"
                        style="border: 1px solid #ced4da;">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-submit" style="padding: 0.5rem 2.5rem;">
                        <i class="fas fa-save me-1"></i> Submit
                    </button>
                    {{-- @if ($corr->next_action === 'INPUT_EVIDENCE')
                        <button type="submit" class="btn btn-submit" style="padding: 0.5rem 2.5rem;"
                            value="input_evidence">
                            <i class="fas fa-save me-1"></i> Input Evidence
                        </button>
                    @endif --}}
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
