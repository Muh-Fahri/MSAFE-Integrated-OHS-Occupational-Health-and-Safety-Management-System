<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center">
                <a href="{{ route('transaction-incidentNotification.index') }}" class="btn btn-back fs-4">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">View Incident Investigation</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title mb-0 fw-bold">Event Details</h5>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Event Title</label>
                        <div class="col-md-10">
                            <input type="text" name="event_title" readonly
                                class="form-control bg-light rounded-pill text-secondary"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" value="{{ $incident->event_title }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Date/Time</label>
                        <div class="col-md-10">
                            <input type="datetime-local" name="event_datetime" readonly
                                class="form-control bg-light rounded-pill text-secondary"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ date('Y-m-d\TH:i', strtotime($incident->event_datetime)) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Reported Date</label>
                        <div class="col-md-10">
                            <input type="date" name="report_date"
                                class="form-control bg-light rounded-pill text-secondary"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ date('Y-m-d', strtotime($incident->report_date)) }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Company</label>
                        <div class="col-md-10">
                            <input type="text" name="company_name"
                                class="form-control bg-light rounded-pill text-secondary"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ $incident->company_name }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Department</label>
                        <div class="col-md-10">
                            <input type="text" name="department_name"
                                class="form-control bg-light rounded-pill text-secondary"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ $incident->department_name }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Location</label>
                        <div class="col-md-10">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" name="location_type"
                                        class="form-control bg-light rounded-pill text-secondary"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        value="{{ $incident->location_type }}" readonly>
                                </div>

                                <div class="col-md-6" id="location_container">
                                    <input type="text" name="location"
                                        class="form-control bg-light rounded-pill text-secondary"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        value="{{ $incident->location }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Event Type</label>
                        <div class="col-md-10">
                            <input type="text" name="event_type"
                                class="form-control bg-light rounded-pill text-secondary"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ $incident->event_type }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Severity Level</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small">Actual Severity</label>
                                    <input type="text" name="severity_level_actual"
                                        class="form-control bg-light rounded-pill text-secondary mb-3"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        value="{{ $incident->severity_level_actual }}" readonly>
                                    <input type="text"
                                        class="form-control bg-light rounded-pill text-secondary"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" readonly
                                        name="severity_level_actual_remarks"
                                        value="{{ $incident->severity_level_actual_remarks }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Potential Severity</label>
                                    <input type="text" name="severity_level_potential"
                                        class="form-control bg-light rounded-pill text-secondary mb-3"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        value="{{ $incident->severity_level_potential }}" readonly>
                                    <input type="text" name="severity_level_potential_remarks"
                                        class="form-control bg-light rounded-pill text-secondary" readonly
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        value="{{ $incident->severity_level_potential_remarks }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Work Related</label>
                        <div class="col-md-10">
                            <input type="text" name="work_related"
                                class="form-control bg-light rounded-pill text-secondary"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ $incident->work_related }}" readonly>
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
                        <label class="col-md-2 col-form-label">Description</label>
                        <div class="col-md-10">
                            <textarea name="incident_description" class="form-control bg-light rounded-3 text-secondary" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4">{{ $incident->incident_description }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Immediate Actions</label>
                        <div class="col-md-10">
                            <textarea name="incident_actions" class="form-control bg-light rounded-3 text-secondary" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4">{{ $incident->incident_actions }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Evidence Photos</label>
                        <div class="col-md-10">
                            {{-- Row pembungkus foto --}}
                            <div class="row">
                                @for ($i = 1; $i <= 4; $i++)
                                    @php
                                        $photoField = "photo_{$i}_path";
                                        $fullPath = $incident->$photoField;
                                    @endphp

                                    {{-- Ubah col-md-6 menjadi col-12 agar bersusun ke bawah --}}
                                    <div class="col-12 mb-4">
                                        @if ($fullPath && trim($fullPath) !== '')
                                            <div class="mb-2">
                                                <a href="{{ route('storage.external', ['folder' => 'incident', 'filename' => $fullPath]) }}" target="_blank">
                                                    <img src="{{ route('storage.external', ['folder' => 'incident', 'filename' => $fullPath]) }}"
                                                        alt="Evidence {{ $i }}" class="img-thumbnail"
                                                        style="max-height: 250px; width: auto; max-width: 100%; display: block; object-fit: contain; border: 1px solid #dee2e6;">
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DYNAMIC TABLE FOR ACTIONS REQUIRED --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><b>Investigation Team </b></h5>

                        {{-- Tombol Input/Edit Investigation (Hanya muncul jika kondisi terpenuhi) --}}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered w-100 mb-0" id="table-actions">
                            <thead class="bg-light text-secondary">
                                <tr class="align-middle">
                                    <th style="width: 5%" class="text-center small fw-bold">No</th>
                                    <th style="width: 45%" class="small fw-bold">Name</th>
                                    <th style="width: 50%" class="small fw-bold">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($incident->teams as $index => $team)
                                    <tr>
                                        <td class="text-center align-middle bg-light small fw-bold row-number">
                                            {{ $index + 1 }}</td>
                                        <td class="p-0">
                                            <input type="text" class="form-control border-0 bg-transparent rounded-0" value="{{ $team->name }}" readonly>
                                        </td>
                                        <td class="p-0">
                                            <input type="text" class="form-control border-0 bg-transparent rounded-0" value="{{ $team->role }}" readonly>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted small italic">No team data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- next disini --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title mb-0 text-white"><b>Impact</b></h5>
                    </div>
                    {{-- Injury Section --}}
                    <div class="mb-4 border-bottom pb-2">
                        <h5 class="text-uppercase">Injury</h5>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Classification</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="impact_injury_classification"
                                class="form-control bg-light rounded-pill text-secondary" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_injury_classification', $incident->impact_injury_classification) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Description</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="impact_injury_description"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_injury_description', $incident->impact_injury_description) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Treatment Details</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="impact_injury_treatment_details" readonly
                                class="form-control rounded-pill text-secondary bg-light"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_injury_treatment_details', $incident->impact_injury_treatment_details) }}">
                        </div>
                    </div>
                    <div class="mb-5 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Body Injury</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="impact_injury_body_injury"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_injury_body_injury', $incident->impact_injury_body_injury) }}">
                        </div>
                    </div>

                    {{-- Environmental Section --}}
                    <div class="mb-4 border-bottom pb-2">
                        <h5 class="text-uppercase">Environmental</h5>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Category</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="impact_environmental_category"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_environmental_category', $incident->impact_environmental_category) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Product Name</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="impact_environmental_product_name"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_environmental_product_name', $incident->impact_environmental_product_name) }}">
                        </div>
                    </div>
                    <div class="mb-5 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Quantity-UOM</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="impact_environmental_quantity_uom"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_environmental_quantity_uom', $incident->impact_environmental_quantity_uom) }}">
                        </div>
                    </div>

                    {{-- Property Damage Section --}}
                    <div class="mb-4 border-bottom pb-2">
                        <h5 class="text-uppercase">Property Damage</h5>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Property/ Plant Type</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="impact_property_damage_plant_type"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_property_damage_plant_type', $incident->impact_property_damage_plant_type) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Damage Cost</b></label>
                        <div class="col-sm-9">
                            <input type="number" name="impact_property_damage_cost"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_property_damage_cost', $incident->impact_property_damage_cost) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Asset Involved</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="impact_property_damage_asset_involved"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_property_damage_asset_involved', $incident->impact_property_damage_asset_involved) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Damage Info</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="impact_property_damage_info"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_property_damage_info', $incident->impact_property_damage_info) }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Asset Number</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="impact_property_damage_asset_number"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('impact_property_damage_asset_number', $incident->impact_property_damage_asset_number) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 2: FACT FINDINGS --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title mb-0 "><b>Fact Findings</b></h5>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>People</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="fact_finding_description_people"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('fact_finding_description_people', $incident->fact_finding_description_people) }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Environment</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="fact_finding_description_environment"
                                class="form-control rounded-pill text-secondary bg-light" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('fact_finding_description_environment', $incident->fact_finding_description_environment) }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Equipment</b></label>
                        <div class="col-sm-9">
                            <textarea name="fact_finding_description_equipment" class="form-control rounded-5 text-secondary bg-light"
                                disabled style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4">{{ old('fact_finding_description_equipment', $incident->fact_finding_description_equipment) }}</textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Procedures</b></label>
                        <div class="col-sm-9">
                            <textarea name="fact_finding_description_procedure"class="form-control rounded-5 text-secondary bg-light"
                                disabled style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4">{{ old('fact_finding_description_procedure', $incident->fact_finding_description_procedure) }}</textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-secondary"><b>Investigation Photos</b></label>
                        <div class="col-sm-9">
                            @for ($i = 1; $i <= 4; $i++)
                                @php
                                    $photoField = "fact_finding_photo_{$i}_path";
                                    $fullPath = $incident->$photoField;
                                @endphp

                                {{-- Setiap slot foto dibungkus div agar bersusun ke bawah --}}
                                <div class="mb-4 p-2">
                                    {{-- Tampilkan gambar hanya jika ada datanya --}}
                                    @if ($fullPath)
                                        <div class="mb-2">
                                            <a href="{{ route('storage.external', ['folder' => 'incident', 'filename' => $fullPath]) }}" target="_blank">
                                                <img src="{{ route('storage.external', ['folder' => 'incident', 'filename' => $fullPath]) }}"
                                                    class="img-thumbnail"
                                                    style="max-height: 200px; width: auto; object-fit: contain;">
                                            </a>
                                            <div class="small text-primary mt-1">
                                                <i class="fas fa-file-image"></i>
                                                {{ basename($incident->$photoField) }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="card-title mb-0"><b>Root Cause Analysis</b></h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered w-100 mb-0">
                            <tbody>
                                <tr>
                                    <th class="bg-light text-secondary align-middle px-3" style="width: 30%;">
                                        Causal Factor
                                    </th>
                                    <td class="p-0">
                                        <textarea name="fact_finding_causal_factor" class="form-control border-0 bg-white" readonly rows="3" required
                                            style="resize: none; border-radius: 0;">{{ old('fact_finding_causal_factor', $incident->fact_finding_causal_factor) }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-secondary align-middle px-3">
                                        Root Cause
                                    </th>
                                    <td class="p-0">
                                        <textarea name="fact_finding_root_cause" class="form-control border-0 bg-white" readonly rows="3" required
                                            style="resize: none; border-radius: 0;">{{ old('fact_finding_root_cause', $incident->fact_finding_root_cause) }}</textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- DYNAMIC TABLE FOR ACTIONS REQUIRED --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><b>Actions Required</b></h5>

                        {{-- Tombol Input/Edit Investigation (Hanya muncul jika kondisi terpenuhi) --}}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered w-100 mb-0" id="table-actions">
                            <thead class="bg-light text-secondary">
                                <tr class="align-middle">
                                    <th style="width: 5%" class="text-center small fw-bold">No</th>
                                    <th style="width: 40%" class="small fw-bold">Action Required</th>
                                    <th style="width: 25%" class="small fw-bold">Assign To</th>
                                    <th style="width: 20%" class="small fw-bold">Date of Completion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($incident->actions as $index => $action)
                                    <tr>
                                        <td class="text-center align-middle bg-light small fw-bold row-number">
                                            {{ $index + 1 }}</td>
                                        <td class="p-0">
                                            <textarea class="form-control border-0 bg-transparent rounded-0" readonly rows="2" style="resize: none;">{{ $action->name }}</textarea>
                                        </td>
                                        <td class="p-0">
                                            <input type="text"
                                                class="form-control border-0 bg-transparent rounded-0" readonly
                                                value="{{ $action->assignee_name }}">
                                        </td>
                                        <td class="p-0">
                                            <input type="date"
                                                class="form-control border-0 bg-transparent rounded-0" readonly
                                                value="{{ $action->due_date }}">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted small italic">No actions
                                            required data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                 <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2 pb-3">
                    <div>
                        @if((in_array($incident->status,['INVESTIGATION_REQUIRED', 'INVESTIGATION_REJECTED'])  && $incident->reporter_id==$user->id)  || ($incident->status=='INVESTIGATION_APPROVAL_REQUIRED' && $incident->approval_level==1 && $incident->reporter_id==$user->id))
                            <a href="{{ route('transaction-incidentInvestigation.edit', $incident->id) }}"
                                class="btn btn-primary rounded-pill px-4 shadow-sm" title="Input Investigation">
                                <i class="fas fa-microscope me-1"></i> Input Investigation
                            </a>
                        @endif
                        @if($incident->status=='INVESTIGATION_APPROVAL_REQUIRED' && (in_array($user->id, $next_user_ids) || $delegated))
                        <div class="d-flex gap-2 justify-content-center">
                            <form action="{{ route('transaction-incidentInvestigation.approve', $incident->id) }}"
                                method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="action" value="REJECT">
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-reject-swal px-3 rounded-pill">
                                    <i class="fas fa-times me-1"></i> REJECT
                                </button>
                            </form>
                            <form action="{{ route('transaction-incidentInvestigation.approve', $incident->id) }}"
                                method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="action" value="APPROVE">
                                <button type="button"
                                    class="btn btn-sm btn-outline-success btn-approve-swal px-3 rounded-pill">
                                    <i class="fas fa-check me-1"></i> APPROVE
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @push('scripts')
        <script>
            $(document).ready(function() {

                // --- KONFIRMASI APPROVE DENGAN REMARKS ---
                const approveButtons = document.querySelectorAll('.btn-approve-swal');
                approveButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        const form = this.closest('form');

                        Swal.fire({
                            title: 'Setujui Laporan?',
                            text: "Tambahkan catatan jika diperlukan:",
                            icon: 'question',
                            input: 'textarea',
                            inputPlaceholder: 'Tuliskan catatan persetujuan di sini...',
                            inputAttributes: {
                                'aria-label': 'Tuliskan catatan persetujuan di sini'
                            },
                            showCancelButton: true,
                            confirmButtonColor: '#198754',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, Setujui!',
                            customClass: {
                                popup: 'rounded-4'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Buat input hidden secara dinamis untuk mengirim remarks
                                const inputRemarks = document.createElement('input');
                                inputRemarks.type = 'hidden';
                                inputRemarks.name = 'remarks';
                                inputRemarks.value = result.value; // Nilai dari textarea SWAL
                                form.appendChild(inputRemarks);

                                form.submit();
                            }
                        });
                    });
                });

                // --- Logic SweetAlert untuk Delete ---
                $('.btn-confirm-delete').on('click', function(e) {
                    e.preventDefault(); // Mencegah form submit otomatis

                    let form = $(this).closest('form'); // Ambil form terdekat dari tombol yang diklik

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data investigasi insiden ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33', // Warna merah untuk hapus
                        cancelButtonColor: '#6e7881',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true // Membalik posisi tombol agar 'Batal' di kiri
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Jika OK, jalankan submit form
                        }
                    });
                });
            });

            // --- KONFIRMASI REJECT DENGAN REMARKS (WAJIB ISI) ---
            const rejectButtons = document.querySelectorAll('.btn-reject-swal');
            rejectButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Tolak Laporan?',
                        text: "Berikan alasan penolakan (Wajib):",
                        icon: 'error',
                        input: 'textarea',
                        inputPlaceholder: 'Alasan penolakan...',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Anda harus menuliskan alasan penolakan!'
                            }
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Tolak!',
                        customClass: {
                            popup: 'rounded-4'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const inputRemarks = document.createElement('input');
                            inputRemarks.type = 'hidden';
                            inputRemarks.name = 'remarks';
                            inputRemarks.value = result.value;
                            form.appendChild(inputRemarks);

                            form.submit();
                        }
                    });
                });
            });

            // --- Logic toggle lokasi yang sudah ada ---
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
