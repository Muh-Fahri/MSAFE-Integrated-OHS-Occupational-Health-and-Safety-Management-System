<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center mb-3">
                <a href="{{ route('transaction-incidentInvestigation.index') }}" class="btn btn-back fs-4">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Finding Incident Investigation</h4>
            </div>
        </div>
    </div>

    <form action="{{ route('transaction-incidentInvestigation.create', $data->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        {{-- CARD 1: IMPACT --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="card-title mb-0 text-secondary"><b>Impact</b></h5>
                </div>
                {{-- Injury Section --}}
                <div class="mb-4 border-bottom pb-2">
                    <h5 class="text-uppercase">Injury</h5>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Classification</b></label>
                    <div class="col-sm-9">
                        <input type="text"
                            name="impact_injury_classification"class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_injury_classification') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Description</b></label>
                    <div class="col-sm-9">
                        <input type="text" name="impact_injury_description"
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_injury_description') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Treatment Details</b></label>
                    <div class="col-sm-9">
                        <input type="text" name="impact_injury_treatment_details"
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_injury_treatment_details') }}">
                    </div>
                </div>
                <div class="mb-5 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Body Injury</b></label>
                    <div class="col-sm-9">
                        <input type="text" name="impact_injury_body_injury"
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_injury_body_injury') }}">
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
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_environmental_category') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Product Name</b></label>
                    <div class="col-sm-9">
                        <input type="text" name="impact_environmental_product_name"
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_environmental_product_name') }}">
                    </div>
                </div>
                <div class="mb-5 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Quantity-UOM</b></label>
                    <div class="col-sm-9">
                        <input type="text" name="impact_environmental_quantity_uom"
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_environmental_quantity_uom') }}">
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
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_property_damage_plant_type') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Damage Cost</b></label>
                    <div class="col-sm-9">
                        <input type="number" name="impact_property_damage_cost"
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_property_damage_cost') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Asset Involved</b></label>
                    <div class="col-sm-9">
                        <input type="text" name="impact_property_damage_asset_involved"
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_property_damage_asset_involved') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Damage Info</b></label>
                    <div class="col-sm-9">
                        <input type="text" name="impact_property_damage_info"
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_property_damage_info') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Asset Number</b></label>
                    <div class="col-sm-9">
                        <input type="text" name="impact_property_damage_asset_number"
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('impact_property_damage_asset_number') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: FACT FINDINGS --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="card-title mb-0"><b>Fact Findings</b></h5>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>People</b></label>
                    <div class="col-sm-9">
                        <input type="text" name="fact_finding_description_people"
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('fact_finding_description_people') }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Environment</b></label>
                    <div class="col-sm-9">
                        <input type="text" name="fact_finding_description_environment"
                            class="form-control rounded-pill text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                            value="{{ old('fact_finding_description_environment') }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Equipment</b></label>
                    <div class="col-sm-9">
                        <textarea name="fact_finding_description_equipment" class="form-control rounded-5 text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4">{{ old('fact_finding_description_equipment') }}</textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Procedures</b></label>
                    <div class="col-sm-9">
                        <textarea name="fact_finding_description_procedure" class="form-control rounded-5 text-secondary border-2"
                            style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4">{{ old('fact_finding_description_procedure') }}</textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Investigation Photos</b></label>
                    <div class="col-sm-9">
                        <div class="row">
                            @for ($i = 1; $i <= 4; $i++)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small text-muted">Photo {{ $i }}</label>
                                    <input type="file" name="fact_finding_photo_{{ $i }}_path"
                                        class="form-control">
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body p-0">
                <div class="mb-4">
                    <h5 class="card-title mb-0"><b>Root Cause Analysis</b></h5>
                </div>
                <table class="table table-bordered w-100 mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light text-secondary align-middle" style="width: 30%;">Causal Factor</th>
                            <td class="p-0">
                                <textarea name="fact_finding_causal_factor" class="form-control text-secondary border-2"rows="3"
                                    placeholder="Enter causal factor explanation..." required style="resize: none;">{{ old('fact_finding_causal_factor') }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light text-secondary align-middle">Root Cause</th>
                            <td class="p-0">
                                <textarea name="fact_finding_root_cause" class="form-control text-secondary border-2"rows="3"
                                    placeholder="Enter root cause explanation..." required style="resize: none;">{{ old('fact_finding_root_cause') }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 2. ACTIONS REQUIRED (DYNAMIC TABLE) --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="p-3">
                    <h5 class="card-title mb-0"><b>Actions Required</b></h5>
                </div>
                <table class="table table-bordered w-100 mb-0" id="table-actions">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center align-middle" style="width: 5%">No</th>
                            <th class="align-middle" style="width: 40%">Action Required</th>
                            <th class="align-middle" style="width: 25%">Assign To</th>
                            <th class="align-middle" style="width: 20%">Date of Completion</th>
                            <th class="text-center align-middle" style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center align-middle row-number">1</td>
                            <td class="align-middle">
                                <textarea name="action_name[]" class="form-control" rows="1"></textarea>
                            </td>
                            <td class="align-middle">
                                <select name="assignee_id[]" class="form-select">
                                    <option value="">Select Staff</option>
                                    @foreach ($usr as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="align-middle">
                                <input type="date" name="due_date[]" class="form-control">
                            </td>
                            <td class="text-center align-middle">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top-0">
                <button type="button" class="btn btn-sm btn-success" onclick="addRow()">
                    <i class="fas fa-plus"></i> Add New Action
                </button>
            </div>
        </div>
        {{-- ACTION BUTTONS --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-4 row">
                    <label class="col-sm-3 col-form-label text-secondary"><b>Remarks / Notes</b></label>
                    <div class="col-sm-9">
                        <textarea name="remarks" style="border-color: #dee2e6; padding-left: 1.5rem"
                            class="form-control @error('remarks') is-invalid @enderror" rows="3">{{ old('remarks', $data->remarks) }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <a href="{{ route('transaction-incidentInvestigation.create', $data->id) }}"
                            class="btn btn-cancel me-2">Cancel</a>
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
            function addRow() {
                const table = document.getElementById('table-actions').getElementsByTagName('tbody')[0];
                const rowCount = table.rows.length;
                const row = table.insertRow(rowCount);

                row.innerHTML = `
            <td class="text-center align-middle row-number">${rowCount + 1}</td>
            <td><textarea name="action_name[]" class="form-control" rows="1" required></textarea></td>
            <td>
                <select name="assignee_id[]" class="form-control" required>
                    <option value="">Select Staff</option>
                    @foreach ($usr as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="date" name="due_date[]" class="form-control" required></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
            }

            function removeRow(btn) {
                const table = document.getElementById('table-actions').getElementsByTagName('tbody')[0];
                if (table.rows.length > 1) {
                    const row = btn.parentNode.parentNode;
                    row.parentNode.removeChild(row);
                    updateRowNumbers();
                } else {
                    alert("At least one action is required.");
                }
            }

            function updateRowNumbers() {
                const rows = document.querySelectorAll('.row-number');
                rows.forEach((td, index) => {
                    td.innerText = index + 1;
                });
            }
        </script>
    @endpush
</x-app-layout>
