<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center">
                <a href="{{ route('transaction-incidentNotification.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">View Incident Notification</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header" style="background-color: #2D3B41">
                    <h5 class="card-title mb-0 text-white">Event Details (Editing)</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Event Title</label>
                        <div class="col-md-10">
                            <input type="text" name="event_title" readonly
                                class="form-control bg-light rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" value="{{ $item->event_title }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Date/Time</label>
                        <div class="col-md-10">
                            <input type="datetime-local" name="event_datetime" readonly
                                class="form-control bg-light rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ $item->event_datetime ? date('Y-m-d\TH:i', strtotime($item->event_datetime)) : '' }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Reported Date</label>
                        <div class="col-md-10">
                            <input type="date" name="report_date"
                                class="form-control bg-light rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ date('Y-m-d', strtotime($item->report_date)) }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Company</label>
                        <div class="col-md-10">
                            <select class="form-control bg-light rounded-pill text-secondary border-2" disabled
                                style="border-color: #dee2e6; padding-left: 1.5rem;" name="company_id">
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
                        <label class="col-md-2 col-form-label">Department</label>
                        <div class="col-md-10">
                            <select class="form-control bg-light rounded-pill text-secondary border-2" disabled
                                style="border-color: #dee2e6; padding-left: 1.5rem;" name="department_id">
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
                        <label class="col-md-2 col-form-label">Location</label>
                        <div class="col-md-10">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <select class="form-control bg-light rounded-pill text-secondary border-2" disabled
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" name="location_type"
                                        id="location_type" onchange="toggleLocationInput()">
                                        @foreach ($loc_type as $l)
                                            <option value="{{ $l }}"
                                                {{ $item->location_type == $l ? 'selected' : '' }}>
                                                {{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6" id="location_container">
                                    <select
                                        class="form-control bg-light rounded-pill text-secondary border-2 {{ $item->location_type == 'Off Site' ? 'd-none' : '' }}"
                                        disabled name="location" id="location_select"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        {{ $item->location_type == 'Off Site' ? 'disabled' : '' }}>
                                        @foreach ($location as $lo)
                                            <option value="{{ $lo->name }}"
                                                {{ $item->location == $lo->name ? 'selected' : '' }}>
                                                {{ $lo->name }}</option>
                                        @endforeach
                                    </select>

                                    <input type="text" name="location" id="location_input" readonly
                                        class="form-control bg-light rounded-pill text-secondary borer-2 {{ $item->location_type == 'Off Site' ? '' : 'd-none' }}"
                                        value="{{ $item->location }}"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        {{ $item->location_type == 'Off Site' ? '' : 'disabled' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Event Type</label>
                        <div class="col-md-10">
                            <select class="form-control bg-light rounded-pill text-secondary border-2" disabled
                                style="border-color: #dee2e6; padding-left: 1.5rem;" name="event_type">
                                @foreach ($ev_type as $et)
                                    <option value="{{ $et }}"
                                        {{ $item->event_type == $et ? 'selected' : '' }}>{{ $et }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Severity Level</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small">Actual Severity</label>
                                    <select class="form-control bg-light rounded-pill text-secondary border-2 mb-3"
                                        disabled style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        name="severity_level_actual">
                                        @foreach ($act_sev as $as)
                                            <option value="{{ $as }}"
                                                {{ $item->severity_level_actual == $as ? 'selected' : '' }}>
                                                {{ $as }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text"
                                        class="form-control bg-light rounded-pill text-secondary border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" readonly
                                        name="severity_level_acttual_remarks"
                                        value="{{ $item->severity_level_acttual_remarks }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Potential Severity</label>
                                    <select class="form-control bg-light rounded-pill text-secondary border-2 mb-3"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" disabled
                                        name="severity_level_potential">
                                        @foreach ($pot_sev as $pv)
                                            <option value="{{ $pv }}"
                                                {{ $item->severity_level_potential == $pv ? 'selected' : '' }}>
                                                {{ $pv }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="severity_level_potential_remarks"
                                        class="form-control bg-light rounded-pill text-secondary border-2" readonly
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        value="{{ $item->severity_level_potential_remarks }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Work Related</label>
                        <div class="col-md-10">
                            <select class="form-control bg-light rounded-pill text-secondary border-2" disabled
                                style="border-color: #dee2e6; padding-left: 1.5rem;" name="work_related">
                                <option value="Yes" {{ $item->work_related == 'Yes' ? 'selected' : '' }}>Yes
                                </option>
                                <option value="No" {{ $item->work_related == 'No' ? 'selected' : '' }}>No
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header" style="background-color: #2D3B41">
                    <h5 class="card-title mb-0 text-white">Incident Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Description</label>
                        <div class="col-md-10">
                            <textarea name="incident_description" class="form-control bg-light rounded-3 text-secondary border-2" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4">{{ $item->incident_description }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Immediate Actions</label>
                        <div class="col-md-10">
                            <textarea name="incident_actions" class="form-control bg-light rounded-3 text-secondary border-2" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4">{{ $item->incident_actions }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Evidence Photos</label>
                        <div class="col-md-10">
                            {{-- Row pembungkus foto --}}
                            <div class="row">
                                @for ($i = 1; $i <= 4; $i++)
                                    @php $photoField = "photo_{$i}_path"; @endphp

                                    {{-- Ubah col-md-6 menjadi col-12 agar bersusun ke bawah --}}
                                    <div class="col-12 mb-4">
                                        @if (isset($item) && $item->$photoField)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $item->$photoField) }}"
                                                    alt="Evidence {{ $i }}" class="img-thumbnail"
                                                    style="max-height: 250px; width: auto; max-width: 100%; display: block; object-fit: contain; border: 2px solid #dee2e6;">
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
</x-app-layout>
