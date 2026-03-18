<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Add New Role</h4>
                <div class="page-title-right">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Role Information</h4>
                    <p class="card-title-desc">Fill in the form below to create a new role.</p>

                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <label for="role" class="col-md-2 col-form-label">Role Code <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('role') is-invalid @enderror"
                                       id="role" name="role" value="{{ old('role') }}"
                                       placeholder="e.g., ADM, USR, S-ADM" required>
                                <small class="text-muted">Role code will be automatically converted to uppercase</small>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-2 col-form-label">Role Name <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="e.g., Admin, User, Super Admin" required>
                                <small class="text-muted">Human-readable name for display purposes</small>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-10 offset-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Role
                                </button>
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
