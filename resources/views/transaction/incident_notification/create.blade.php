<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center gap-2">
                <div class="page-title-right">
                    <a href="{{ route('transaction-incidentNotification.index') }}" class="btn fs-4 btn-back btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                </div>
                <h4 class="mb-0">Add New Incident</h4>

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
            <form action="{{ route('transaction-incidentNotification.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-4">
                            <h1 class="card-title mb-0 text-secondary fw-bold">Event Details</h1>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Event Title
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <input type="text" name="event_title"
                                    class="form-control rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" placeholder="Enter event title"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Date/Time
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <input type="datetime-local" name="event_datetime" onclick="this.showPicker()"
                                    class="form-control rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Reported Date
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <input type="date" name="report_date" onclick="this.showPicker()"
                                    class="form-control rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Company
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <div class="select-custom-wrapper" data-placeholder="Select Company">
                                    <select class="form-select rounded-pill scrollable-select text-secondary border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" id="comp_select"
                                        name="company_id" required>
                                        <option value="">Select Company</option>
                                        @foreach ($company as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Department
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <div class="select-custom-wrapper" data-placeholder="Select Department">
                                    <select class="form-select rounded-pill scrollable-select text-secondary border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" name="department_id" id="select_dept" required>
                                        <option value="">Select Department</option>
                                        @foreach ($department as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Location
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <select class="form-select rounded-pill text-secondary border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;" name="location_type"
                                            id="location_type" onchange="toggleLocationInput()" required>
                                            <option value="">Location Type</option>
                                            @foreach ($loc_type as $l)
                                                <option value="{{ $l }}"
                                                    {{ old('location_type', $item->location_type ?? '') == $l ? 'selected' : '' }}>
                                                    {{ $l }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <div id="location_select_wrapper">
                                            <select name="location" id="location_select"
                                                class="form-select rounded-pill text-secondary border-2"
                                                style="border-color: #dee2e6; padding-left: 1.5rem;" id="location_select" required>
                                                <option value="">Select Location</option>
                                                @foreach ($location as $lo)
                                                    <option value="{{ $lo->name }}"
                                                        {{ old('location', $item->location ?? '') == $lo->name ? 'selected' : '' }}>
                                                        {{ $lo->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div id="location_input_wrapper" class="d-none">
                                            <input type="text" name="location_custom" id="location_custom"
                                                class="form-control rounded-pill text-secondary border-2"
                                                placeholder="Enter Off-site Location Name"
                                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                                value="{{ old('location', $item->location ?? '') }}">
                                        </div>
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
                                    <option value="">Select Event Type</option>
                                    @foreach ($ev_type as $et)
                                        <option value="{{ $et }}">{{ $et }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Severity Level
                            </label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Actual Severity</label>
                                        <select class="form-select rounded-pill text-secondary border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            name="severity_level_actual">
                                            <option value="-">NaN</option>
                                            @foreach ($act_sev as $as)
                                                <option value="{{ $as }}">{{ $as }}</option>
                                            @endforeach
                                        </select><br>
                                        <input type="text"
                                            class="form-control rounded-pill text-secondary border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            name="severity_level_actual_remarks" placeholder="Actual remarks">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">Potential Severity</label>
                                        <select class="form-select rounded-pill text-secondary border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            name="severity_level_potential">
                                            <option value="-">NaN</option>
                                            @foreach ($pot_sev as $pv)
                                                <option value="{{ $pv }}">{{ $pv }}</option>
                                            @endforeach
                                        </select><br>
                                        <input type="text" name="severity_level_potential_remarks"
                                            class="form-control rounded-pill text-secondary border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            placeholder="Potential remarks">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Work Related
                            </label>
                            <div class="col-md-10">
                                <select class="form-select rounded-pill text-secondary border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" name="work_related">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
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
                                <textarea name="incident_description" placeholder="Describe what happened..." class="form-control" rows="4" required></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Immediate Actions
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <textarea name="immediate_actions" placeholder="What was done immediately?" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Evidence Photos
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6 mb-2"><input type="file" name="photo_1_path"
                                            class="form-control" required></div>
                                    <div class="col-md-6 mb-2"><input type="file" name="photo_2_path"
                                            class="form-control"></div>
                                    <div class="col-md-6 mb-2"><input type="file" name="photo_3_path"
                                            class="form-control"></div>
                                    <div class="col-md-6 mb-2"><input type="file" name="photo_4_path"
                                            class="form-control"></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end align-items-center gap-2">

                                <a href="{{ route('transaction-incidentNotification.create') }}"
                                    class="btn btn-cancel px-4">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-submit px-4">
                                    <i class="fas fa-save me-1"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#comp_select, #select_dept, #location_select').select2({
                    width: '100%',
                });
            });

            function toggleLocationInput() {
                const typeSelect = document.getElementById('location_type');
                if (!typeSelect) return; // Guard clause agar tidak error jika element tidak ada

                const selectWrapper = document.getElementById('location_select_wrapper');
                const inputWrapper = document.getElementById('location_input_wrapper');
                const selectElem = document.getElementById('location_select');
                const inputElem = document.getElementById('location_custom');

                if (typeSelect.value.toUpperCase() === "OFF SITE") {
                    selectWrapper.classList.add('d-none');
                    inputWrapper.classList.remove('d-none');

                    selectElem.setAttribute('name', 'location_disabled');
                    inputElem.setAttribute('name', 'location');
                } else {
                    selectWrapper.classList.remove('d-none');
                    inputWrapper.classList.add('d-none');

                    selectElem.setAttribute('name', 'location');
                    inputElem.setAttribute('name', 'location_custom');
                }
            }
        </script>
    @endpush
</x-app-layout>
