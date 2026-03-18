<x-app-layout>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center gap-2 mb-3">
                <a href="{{ route('transaction-asset.index') }}" class="btn btn-back fs-4 btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">Create Asset Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('transaction-asset.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Asset Code</label>
                                    <input type="text" name="code" class="form-control rounded-pill border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        placeholder="Enter asset code" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Register Date</label>
                                    <div class="input-group">
                                        <input type="date" name="register_date"
                                            class="form-control text-secondary rounded-pill border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            onclick="this.showPicker()" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select text-secondary rounded-pill border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                                        <option value="">Select Category</option>
                                        @foreach ($list_category as $category)
                                            <option value="{{ $category }}">{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Commissioning Date</label>
                                    <div class="input-group">
                                        <input type="date" name="commissioning_date"
                                            class="form-control date-input text-secondary rounded-pill border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            onclick="this.showPicker()" required>
                                    </div>
                                </div>

                                <div class="mb-3 select-wrapper">
                                    <label class="form-label">Department</label>
                                    <select name="department_id"
                                        class="form-select border-2 rounded-pill scrollable-select"
                                       style="border-color: #dee2e6;"  required>
                                        <option value="">Select Department</option>
                                        @foreach ($list_department as $dep_id => $dep_name)
                                            <option value="{{ $dep_id }}">{{ $dep_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Asset Name</label>
                                    <input type="text" name="name"
                                        class="form-control text-secondary rounded-pill border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        placeholder="Enter asset name"  required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Asset Type</label>
                                    <select name="type" class="form-select text-secondary rounded-pill border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                                        <option value="">--Select Type --</option>
                                        @foreach ($list_type as $type)
                                            <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ownership</label>
                                    <select name="ownership" class="form-select text-secondary rounded-pill border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" required> 
                                        <option value="">--Select Ownership --</option>
                                        @foreach ($list_ownership as $ownership)
                                            <option value="{{ $ownership }}">{{ $ownership }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Assembly Year</label>
                                    <div class="input-group">
                                        <input type="number" name="assembly_year"
                                            class="form-control text-secondary rounded-pill border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;" placeholder="YYYY"
                                            min="1900" max="2099" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Company</label>
                                    <select name="company_id" class="form-select text-secondary rounded-pill border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" required>
                                        <option value="">-- Select Company --</option>
                                        @foreach ($list_company as $company_id => $company_name)
                                            <option value="{{ $company_id }}">{{ $company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Specification</label>
                                <textarea name="specification" class="form-control rounded-5 border-2"
                                    style="border-color: #dee2e6; padding-left: 1.5rem;" rows="3" placeholder="Enter asset specifications..." required></textarea>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="form-label mb-0">Asset Attachments (Photos/Documents)</label>
                                    <button type="button" class="btn btn-add" id="add-attachment">
                                        <i class="fas fa-plus me-1"></i> Add More
                                    </button>
                                </div>

                                <div id="attachment-container">
                                    <div class="input-group mb-2 attachment-row">
                                        <input type="file" name="attachments[]"
                                            class="form-control text-secondary rounded-pill border-2"
                                            style="border-color: #dee2e6; padding-left: 1.5rem;"
                                            accept="image/*,.pdf">
                                        <button class="btn btn-outline-danger rounded-circle remove-attachment"
                                            type="button" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Allowed files: JPG, PNG, PDF (Max 2MB per file)</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('transaction-asset.create') }}" class="btn btn-cancel"
                                style="border: 1px solid #ced4da;">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-submit" style="padding: 0.5rem 2.5rem;">
                                 Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.getElementById('add-attachment').addEventListener('click', function() {
                const container = document.getElementById('attachment-container');
                const newRow = document.createElement('div');
                newRow.className = 'input-group mb-2 attachment-row';

                newRow.innerHTML = `
            <span class="input-group-text bg-light"><i class="fas fa-image text-muted"></i></span>
            <input type="file" name="attachments[]" class="form-control text-secondary rounded-pill border-2" style="border-color: #dee2e6; padding-left: 1.5rem;" accept="image/*,.pdf">
            <button class="btn btn-outline-danger rounded-circle remove-attachment" type="button">
                <i class="fas fa-trash"></i>
            </button>
        `;

                container.appendChild(newRow);
            });

            // Event delegation untuk menghapus baris
            document.getElementById('attachment-container').addEventListener('click', function(e) {
                if (e.target.closest('.remove-attachment')) {
                    e.target.closest('.attachment-row').remove();
                }
            });
        </script>
    @endpush
</x-app-layout>
