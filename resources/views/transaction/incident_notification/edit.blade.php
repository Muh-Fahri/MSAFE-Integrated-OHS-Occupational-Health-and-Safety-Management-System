<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center">
                <a href="{{ route('transaction-incidentNotification.index') }}" class="btn fs-4 btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Edit Incident</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2 fs-4"></i>
                        <div>
                            <strong>Oops! Terjadi Kesalahan:</strong><br>
                            {{ session('error') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-ban me-2 fs-4"></i>
                        <div>
                            <strong>Periksa kembali inputan Anda:</strong>
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
            <form action="{{ route('transaction-incidentNotification.update', $item->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="card-title mb-0 fw-bold">Event Details (Editing)</h5>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Event Title
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <input type="text" name="event_title"
                                    class="form-control rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    value="{{ $item->event_title }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Date/Time
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <input type="datetime-local" name="event_datetime"
                                    class="form-control rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    value="{{ date('Y-m-d\TH:i', strtotime($item->event_datetime)) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Reported Date
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <input type="date" name="report_date"
                                    class="form-control rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;"
                                    value="{{ $item->report_date }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Company
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <select class="form-select rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" id="company_sel"
                                    name="company_id" required>
                                    @foreach ($company as $c)
                                        <option value="{{ $c->id }}"
                                            {{ $item->company_id == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Department
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <select class="form-select rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" id="dept_sel"
                                    name="department_id" required>
                                    @foreach ($department as $d)
                                        <option value="{{ $d->id }}"
                                            {{ $item->department_id == $d->id ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Location
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="select-custom-wrapper"
                                            data-placeholder="{{ $item->location_type ?? 'Location Type' }}">
                                            <select
                                                class="form-select rounded-pill scrollable-select text-secondary border-2"
                                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                name="location_type" required>
                                                <option value="">Location Type</option>
                                                @foreach ($loc_type as $l)
                                                    <option value="{{ $l }}"
                                                        {{ $item->location_type == $l ? 'selected' : '' }}>
                                                        {{ $l }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="location_container">
                                        <div id="wrapper_location_select"
                                            class="select-custom-wrapper {{ $item->location_type == 'Off Site' ? 'd-none' : '' }}"
                                            data-placeholder="{{ $item->location ?? 'Select Location' }}">
                                            <select
                                                class="form-select rounded-pill scrollable-select text-secondary border-2"
                                                name="location" id="location_select"
                                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                {{ $item->location_type == 'Off Site' ? 'disabled' : '' }} required>
                                                <option value="">Select Location</option>
                                                @foreach ($location as $lo)
                                                    <option value="{{ $lo->name }}"
                                                        {{ $item->location == $lo->name ? 'selected' : '' }}>
                                                        {{ $lo->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <input type="text" name="location" id="location_input"
                                            class="form-control rounded-pill text-secondary border-2 {{ $item->location_type == 'Off Site' ? '' : 'd-none' }}"
                                            value="{{ $item->location }}"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            {{ $item->location_type == 'Off Site' ? '' : 'disabled' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Event Type
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <select class="form-select rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" name="event_type" required>
                                    @foreach ($ev_type as $et)
                                        <option value="{{ $et }}"
                                            {{ $item->event_type == $et ? 'selected' : '' }}>{{ $et }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Severity Level
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Actual Severity</label>
                                        <select class="form-select rounded-pill text-secondary border-2 mb-3"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            name="severity_level_actual" required>
                                            @foreach ($act_sev as $as)
                                                <option value="{{ $as }}"
                                                    {{ $item->severity_level_actual == $as ? 'selected' : '' }}>
                                                    {{ $as }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text"
                                            class="form-control rounded-pill text-secondary border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            name="severity_level_actual_remarks"
                                            value="{{ $item->severity_level_actual_remarks }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">Potential Severity</label>
                                        <select class="form-select rounded-pill text-secondary border-2 mb-3"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            name="severity_level_potential" required>
                                            @foreach ($pot_sev as $pv)
                                                <option value="{{ $pv }}"
                                                    {{ $item->severity_level_potential == $pv ? 'selected' : '' }}>
                                                    {{ $pv }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="severity_level_potential_remarks"
                                            class="form-control rounded-pill text-secondary border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            value="{{ $item->severity_level_potential_remarks }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Work Related</label>
                            <div class="col-md-10">
                                <select class="form-select rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" name="work_related">
                                    <option value="Yes" {{ $item->work_related == 'Yes' ? 'selected' : '' }}>
                                        Yes
                                    </option>
                                    <option value="No" {{ $item->work_related == 'No' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>


                </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="card-title mb-0 fw-bold">Incident Summary</h5>
                </div>
                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Description
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-10">
                        <textarea name="incident_description" class="form-control rounded-3 text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4" required>{{ $item->incident_description }}</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Immediate Actions
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-10">
                        <textarea name="immediate_actions" class="form-control rounded-3 text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4" required>{{ $item->immediate_actions }}</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Evidence Photos
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-10">
                        {{-- Row pembungkus foto --}}
                        <div class="row">
                            @for ($i = 1; $i <= 4; $i++)
                                @php
                                    $photoField = "photo_{$i}_path";
                                    $fullPath = $item->$photoField ?? null;
                                @endphp

                                <div class="col-12 mb-4">
                                    <label class="form-label text-secondary fw-bold">Photo {{ $i }}</label>

                                    @if ($fullPath)
                                        @php
                                            // JANGAN di-explode jika route hanya menerima satu parameter.
                                            // Gunakan $fullPath langsung sebagai filename.
                                            $filenameForRoute = $fullPath;
                                        @endphp

                                        <div class="mb-2">
                                            <a href="{{ route('storage.external', ['folder' => 'incident', 'filename' => $filenameForRoute]) }}"
                                                target="_blank">
                                                <img src="{{ route('storage.external', ['folder' => 'incident', 'filename' => $filenameForRoute]) }}"
                                                    alt="Evidence" class="img-thumbnail shadow-sm"
                                                    style="max-height: 200px; width: auto; max-width: 100%; display: block; object-fit: contain; border: 2px solid #dee2e6;">
                                            </a>

                                            <p class="small text-info mt-2 mb-1" style="font-style: italic;">
                                                <i class="fas fa-info-circle me-1"></i> Leave blank if you don't want
                                                to change this photo.
                                            </p>
                                        </div>
                                    @endif

                                    {{-- Input untuk upload file baru --}}
                                    <input type="file" name="photo_{{ $i }}_path"
                                        class="form-control shadow-sm rounded-pill" accept="image/*">

                                    @if ($i < 4)
                                        <hr class="mt-4 opacity-25">
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                @if ($item->next_user_id == Auth::id())
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Remarks</label>
                        <div class="col-md-10">
                            <textarea name="remarks" class="form-control rounded-3 text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" rows="3"
                                placeholder="Tambahkan catatan approval di sini..."></textarea>
                        </div>
                    </div>
                    <hr class="my-4">
                @endif

                <div class="row">
                    {{-- Menggunakan d-flex dan justify-content-end untuk memindahkan ke ujung kanan --}}
                    <div class="col-md-12 d-flex justify-content-end align-items-center gap-2">
                        <a href="{{ route('transaction-incidentNotification.edit', $item->id) }}"
                            class="btn btn-cancel px-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-submit px-4">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#company_sel, #dept_sel, #location_type, #location_select').select2({
                    width: '100%'
                });
            });

            function toggleLocationInput() {
                const locationType = document.getElementById('location_type').value;
                const selectElement = document.getElementById('location_select');
                const inputElement = document.getElementById('location_input');

                if (locationType === 'Off Site') {
                    selectElement.classList.add('d-none');
                    selectElement.disabled = true;
                    inputElement.classList.remove('d-none');
                    inputElement.disabled = false;
                } else {
                    selectElement.classList.remove('d-none');
                    selectElement.disabled = false;
                    inputElement.classList.add('d-none');
                    inputElement.disabled = true;
                }
            }
        </script>
    @endpush
</x-app-layout>
