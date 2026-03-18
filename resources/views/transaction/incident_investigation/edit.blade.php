<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <div class="page-title-right">
                    <a href="{{ route('transaction-incidentInvestigation.index') }}" class="btn btn-back">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                </div>
                <h4 class="mb-0">Edit Incident Investigation</h4>
            </div>
        </div>
    </div>

    <form action="{{ route('transaction-incidentInvestigation.update', $incident->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Penting untuk proses Update --}}

        {{-- INVESTIGATION TEAM --}}
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color: #2D3B41">
                <h5 class="card-title mb-0 text-white"><b>Investigation Team</b></h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered w-100 mb-0" id="table-teams">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 40%">Name</th>
                            <th style="width: 45%">Role</th>
                            <th style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($incident->teams) > 0)
                            @foreach ($incident->teams as $index => $team)
                                <tr>
                                    <td class="text-center align-middle row-number">{{ $index + 1 }}</td>
                                    <td>
                                        <input type="text" name="team_name[]" class="form-control" value="{{ $team->name }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="team_role[]" class="form-control" value="{{ $team->role }}" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="removeRowTeam(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center align-middle row-number">1</td>
                                <td>
                                    <input type="text" name="team_name[]" class="form-control" value="" required>
                                </td>
                                <td>
                                    <input type="text" name="team_role[]" class="form-control" value="" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRowTeam(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top-0">
                <button type="button" class="btn btn-sm btn-success" onclick="addRowTeam()">
                    <i class="fas fa-plus"></i> Add New Member
                </button>
            </div>
        </div>

        {{-- CARD 1: IMPACT --}}
        <div class="card mb-4">
            <div class="card-header" style="background-color: #2D3B41">
                <h5 class="card-title mb-0 text-white"><b>Impact</b></h5>
            </div>
            <div class="card-body">
                {{-- Injury Section --}}
                <div class="mb-4 border-bottom pb-2">
                    <h5 class="text-uppercase">Injury</h5>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Classification</b></label>
                    <div class="col-sm-9">
                        <select name="impact_injury_classification" class="form-select" required>
                            <option value="">-</option>
                            @foreach ($lists['IMPACT_INJURY_CLASSIFICATION'] as $k=>$v)
                                <option value="{{ $k }}"
                                    {{ old('impact_injury_classification', $incident->impact_injury_classification) == $k ? 'selected' : '' }}>
                                    {{ $v }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Description</b></label>
                    <div class="col-sm-9">
                        <textarea name="impact_injury_description" class="form-control text-secondary " style="" rows="4">{{ old('impact_injury_description', $incident->impact_injury_description) }}</textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Treatment Details</b></label>
                    <div class="col-sm-9">
                        <textarea name="impact_injury_treatment_details" class="form-control text-secondary " style="" rows="4">{{ old('impact_injury_treatment_details', $incident->impact_injury_treatment_details) }}</textarea>
                    </div>
                </div>
                <div class="mb-5 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Body Injury</b></label>
                    <div class="col-sm-9">
                        <select name="impact_injury_body_injury" class="form-select" required>
                            <option value="">-</option>
                            @foreach ($lists['IMPACT_INJURY_BODY'] as $k=>$v)
                                <option value="{{ $k }}"
                                    {{ old('impact_injury_body_injury', $incident->impact_injury_body_injury) == $k ? 'selected' : '' }}>
                                    {{ $v }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Environmental Section --}}
                <div class="mb-4 border-bottom pb-2">
                    <h5 class="text-uppercase">Environmental</h5>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Category</b></label>
                    <div class="col-sm-9">
                        <select name="impact_environmental_category" class="form-select" required>
                            <option value="">-</option>
                            @foreach ($lists['IMPACT_ENVIRONMENTAL_CATEGORY'] as $k=>$v)
                                <option value="{{ $k }}"
                                    {{ old('impact_environmental_category', $incident->impact_environmental_category) == $k ? 'selected' : '' }}>
                                    {{ $v }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Product Name</b></label>
                    <div class="col-sm-9">
                        <select name="impact_environmental_product_name" class="form-select" required>
                            <option value="">-</option>
                            @foreach ($lists['IMPACT_ENVIRONMENTAL_PRODUCT'] as $k=>$v)
                                <option value="{{ $k }}"
                                    {{ old('impact_environmental_product_name', $incident->impact_environmental_product_name) == $k ? 'selected' : '' }}>
                                    {{ $v }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-5 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Quantity-UOM</b></label>
                    <div class="col-sm-9">
                        <input type="text" name="impact_environmental_quantity_uom"
                            class="form-control text-secondary"
                            style=""
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
                        <textarea name="impact_property_damage_plant_type" 
                            class="form-control text-secondary"
                            style="" rows="4">{{ old('impact_property_damage_plant_type', $incident->impact_property_damage_plant_type) }}</textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Damage Cost</b></label>
                    <div class="col-sm-9">
                        <input type="number" name="impact_property_damage_cost"
                            class="form-control text-secondary"
                            style=""
                            value="{{ old('impact_property_damage_cost', $incident->impact_property_damage_cost) }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Asset Involved</b></label>
                    <div class="col-sm-9">
                        <select name="impact_property_damage_asset_involved" class="form-select" required>
                            <option value="">-</option>
                            @foreach ($lists['IMPACT_PROPERTY_DAMAGE_ASSET'] as $k=>$v)
                                <option value="{{ $k }}"
                                    {{ old('impact_property_damage_asset_involved', $incident->impact_property_damage_asset_involved) == $k ? 'selected' : '' }}>
                                    {{ $v }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Damage Info</b></label>
                    <div class="col-sm-9">
                        <textarea name="impact_property_damage_info" class="form-control text-secondary " style="" rows="4">{{ old('impact_property_damage_info', $incident->impact_property_damage_info) }}</textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Asset Number</b></label>
                    <div class="col-sm-9">
                        <textarea name="impact_property_damage_asset_number" class="form-control text-secondary " style="" rows="4">{{ old('impact_property_damage_asset_number', $incident->impact_property_damage_asset_number) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: FACT FINDINGS --}}
        <div class="card mb-4">
            <div class="card-header" style="background-color: #2D3B41">
                <h5 class="card-title mb-0 text-white"><b>Fact Findings</b></h5>
            </div>
            <div class="card-body">
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>People</b></label>
                    <div class="col-sm-9">
                        <textarea name="fact_finding_description_people" class="form-control text-secondary" style="" rows="4">{{ old('fact_finding_description_people', $incident->fact_finding_description_people) }}</textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Environment</b></label>
                    <div class="col-sm-9">
                        <textarea name="fact_finding_description_environment" class="form-control text-secondary" style="" rows="4">{{ old('fact_finding_description_environment', $incident->fact_finding_description_environment) }}</textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Equipment</b></label>
                    <div class="col-sm-9">
                        <textarea name="fact_finding_description_equipment" class="form-control text-secondary" style="" rows="4">{{ old('fact_finding_description_equipment', $incident->fact_finding_description_equipment) }}</textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Procedures</b></label>
                    <div class="col-sm-9">
                        <textarea name="fact_finding_description_procedure" class="form-control text-secondary" style="" rows="4">{{ old('fact_finding_description_procedure', $incident->fact_finding_description_procedure) }}</textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Investigation Photos</b></label>
                    <div class="col-sm-9">
                        @for ($i = 1; $i <= 4; $i++)
                            @php
                                $photoField = 'fact_finding_photo_' . $i . '_path';
                                $hasPhoto = !empty($incident->$photoField);
                            @endphp

                            {{-- Setiap slot foto dibungkus div agar bersusun ke bawah --}}
                            <div class="mb-4 p-2 border-bottom">
                                <label class="form-label small text-muted font-weight-bold">Photo
                                    {{ $i }}</label>

                                {{-- Tampilkan gambar hanya jika ada datanya --}}
                                @if ($hasPhoto)
                                    <div class="mb-2">
                                        <a href="{{ route('storage.external', ['folder' => 'incident', 'filename' => $incident->$photoField]) }}" target="_blank">
                                            <img src="{{ route('storage.external', ['folder' => 'incident', 'filename' => $incident->$photoField]) }}"
                                                class="img-thumbnail"
                                                style="max-height: 200px; width: auto; object-fit: contain;">
                                        </a>
                                        <div class="small text-primary mt-1">
                                            <i class="fas fa-file-image"></i> {{ basename($incident->$photoField) }}
                                        </div>
                                    </div>
                                @endif

                                {{-- Input file tetap di bawah preview --}}
                                <div style="max-width: 400px;"> {{-- Membatasi lebar input agar tidak terlalu panjang ke kanan --}}
                                    <input type="file" name="fact_finding_photo_{{ $i }}_path"
                                        class="form-control form-control-sm @error("fact_finding_photo_{{ $i }}_path") is-invalid @enderror"
                                        accept="image/*">

                                    @error("fact_finding_photo_{{ $i }}_path")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header text-white" style="background-color: #2D3B41">
                <h5 class="card-title mb-0 text-white"><b>Root Cause Analysis</b></h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered w-100 mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light text-secondary align-middle" style="width: 30%;">Causal Factor</th>
                            <td class="p-0">
                                <textarea name="fact_finding_causal_factor" class="form-control border-0" rows="3" required
                                    style="resize: none;">{{ old('fact_finding_causal_factor', $incident->fact_finding_causal_factor) }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light text-secondary align-middle">Root Cause</th>
                            <td class="p-0">
                                <textarea name="fact_finding_root_cause" class="form-control border-0" rows="3" required
                                    style="resize: none;">{{ old('fact_finding_root_cause', $incident->fact_finding_root_cause) }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- DYNAMIC TABLE FOR ACTIONS REQUIRED --}}
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color: #2D3B41">
                <h5 class="card-title mb-0 text-white"><b>Actions Required</b></h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered w-100 mb-0" id="table-actions">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 40%">Action Required</th>
                            <th style="width: 25%">Assign To</th>
                            <th style="width: 20%">Date of Completion</th>
                            <th style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($incident->actions) > 0)
                            @foreach ($incident->actions as $index => $action)
                                <tr>
                                    <td class="text-center align-middle row-number">{{ $index + 1 }}</td>
                                    <td>
                                        <textarea name="action_name[]" class="form-control" rows="1" required>{{ $action->name }}</textarea>
                                    </td>
                                    <td>
                                        <select name="assignee_id[]" class="form-select" required>
                                            <option value="">Select Staff</option>
                                            @foreach ($list_user as $u)
                                                <option value="{{ $u->id }}"
                                                    {{ $action->assignee_id == $u->id ? 'selected' : '' }}>
                                                    {{ $u->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" name="due_date[]" class="form-control"
                                            value="{{ date('Y-m-d', strtotime($action->due_date)) }}" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="removeRowAction(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center align-middle row-number">1</td>
                                <td>
                                    <textarea name="action_name[]" class="form-control" rows="1" required></textarea>
                                </td>
                                <td>
                                    <select name="assignee_id[]" class="form-select js-assignee-id" required>
                                        <option value="">Select Staff</option>
                                        @foreach ($list_user as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="date" name="due_date[]" class="form-control" required></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRowAction(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top-0">
                <button type="button" class="btn btn-sm btn-success" onclick="addRowAction()">
                    <i class="fas fa-plus"></i> Add New Action
                </button>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="card mb-4">
            <div class="card-body">
                {{-- Area Remarks --}}
                <div class="mb-4 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Remarks / Notes</b></label>
                    <div class="col-sm-9">
                        <textarea name="remarks" style="border-color: #dee2e6; padding-left: 1.5rem;"
                            class="form-control @error('remarks') is-invalid @enderror" rows="3">{{ old('remarks', $incident->remarks) }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                {{-- Tombol Action di Ujung Kanan --}}
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end align-items-center gap-2"> {{-- Tambahkan gap-2 di sini --}}
                        <a href="{{ route('transaction-incidentInvestigation.show', $incident->id) }}"
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

    @push('scripts')
        <script>
            $('.js-assignee-id').select2({
                width: '100%'
            });
            function addRowTeam() {
                const table = document.getElementById('table-teams').getElementsByTagName('tbody')[0];
                const rowCount = table.rows.length;
                const row = table.insertRow(rowCount);

                row.innerHTML = `
                    <td class="text-center align-middle row-number">${rowCount + 1}</td>
                    <td><input type="text" name="team_name[]" class="form-control" required></td>
                    <td><input type="text" name="team_role[]" class="form-control" required></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRowTeam(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
            }

            function removeRowTeam(btn) {
                const table = document.getElementById('table-teams').getElementsByTagName('tbody')[0];
                if (table.rows.length > 1) {
                    const row = btn.parentNode.parentNode;
                    row.parentNode.removeChild(row);
                    updateRowNumbersTeam();
                } else {
                    alert("At least one team is required.");
                }
            }

            function updateRowNumbersTeam() {
                const rows = document.querySelectorAll('#table-teams .row-number');
                rows.forEach((td, index) => {
                    td.innerText = index + 1;
                });
            }

            function addRowAction() {
                const table = document.getElementById('table-actions').getElementsByTagName('tbody')[0];
                const rowCount = table.rows.length;
                const row = table.insertRow(rowCount);

                row.innerHTML = `
                    <td class="text-center align-middle row-number">${rowCount + 1}</td>
                    <td><textarea name="action_name[]" class="form-control" rows="1" required></textarea></td>
                    <td>
                        <select name="assignee_id[]" class="form-control js-assignee-id" required>
                            <option value="">Select Staff</option>
                            @foreach ($list_user as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="date" name="due_date[]" class="form-control" required></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRowAction(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                $('.js-assignee-id').select2({
                    width: '100%'
                });
            }

            function removeRowAction(btn) {
                const table = document.getElementById('table-actions').getElementsByTagName('tbody')[0];
                if (table.rows.length > 1) {
                    const row = btn.parentNode.parentNode;
                    row.parentNode.removeChild(row);
                    updateRowNumbersAction();
                } else {
                    alert("At least one action is required.");
                }
            }

            function updateRowNumbersAction() {
                const rows = document.querySelectorAll('.row-number');
                rows.forEach((td, index) => {
                    td.innerText = index + 1;
                });
            }
        </script>
    @endpush
</x-app-layout>
