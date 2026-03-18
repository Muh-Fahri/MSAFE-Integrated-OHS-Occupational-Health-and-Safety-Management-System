<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center gap-2">
                <div class="page-title-left">
                    <a href="{{ route('users.index') }}" class="btn-back fs-4">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                </div>
                <h4 class="mb-0">Add New User</h4>
        </div>
    </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3 row">
                            <label for="username" class="col-md-2 col-form-label">Username <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                    id="username" name="username" value="{{ old('username') }}"
                                    placeholder="Enter username" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="name" class="col-md-2 col-form-label">Name <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Enter full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="email" class="col-md-2 col-form-label">Email</label>
                            <div class="col-md-10">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Enter email address">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="phone" class="col-md-2 col-form-label">Phone<span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="number" class="form-control @error('phone') is-invalid @enderror"
                                    id="password" name="phone" value="{{ old('phone') }}"
                                    placeholder="Enter phone number" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="role_id" class="col-md-2 col-form-label">Role <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <select class="form-select @error('role_id') is-invalid @enderror" id="role_id"
                                    name="role_id" required>
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="email" class="col-md-2 col-form-label">Employee ID</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                                    id="employee_id" name="employee_id" value="{{ old('employee_id') }}"
                                    placeholder="Enter Employee ID">
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="role_id" class="col-md-2 col-form-label">Department <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <select class="form-select @error('role_id') is-invalid @enderror" id="department"
                                    name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $d)
                                        <option value="{{ $d->id }}"
                                            {{ old('department_id') == $d->id ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="role_id" class="col-md-2 col-form-label">Company <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <select class="form-select @error('company_id') is-invalid @enderror" id="company"
                                    name="company_id" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $d)
                                        <option value="{{ $d->id }}"
                                            {{ old('company_id') == $d->id ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="role_id" class="col-md-2 col-form-label">Head Of Department <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <select class="form-select @error('hod') is-invalid @enderror" id="hod"
                                    name="hod" required>
                                    <option value="">Select Head Of department</option>
                                    @foreach ($users as $d)
                                        <option value="{{ $d->id }}"
                                            {{ old('hod') == $d->id ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hod')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="role_id" class="col-md-2 col-form-label">Head Of Department (Badge Request)
                                <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <select class="form-select @error('hod2') is-invalid @enderror" id="hod2"
                                    name="hod2" required>
                                    <option value="">Select Head Of department (Badge Request)</option>
                                    @foreach ($users as $d)
                                        <option value="{{ $d->id }}"
                                            {{ old('hod2') == $d->id ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hod2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="password" class="col-md-2 col-form-label">Password <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Enter password (min 8 characters)"
                                    required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <label for="photo" class="col-md-2 col-form-label">Photo</label>
                            <div class="col-md-10">
                                <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                    id="photo" name="photo" accept="image/jpeg,image/png,image/jpg">
                                <small class="text-muted">Max 2MB. Format: JPG, JPEG, PNG</small>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <img id="photoPreview" src="" alt="Photo Preview"
                                        style="max-width: 150px; display: none;" class="img-thumbnail">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-10 offset-md-2 d-flex justify-content-end">
                                <a href="{{ route('users.index') }}" class="btn btn-cancel me-2">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </a>
                                <button type="submit" class="btn btn-submit">
                                    <i class="fas fa-save me-1"></i> Save User
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Photo preview
            document.getElementById('photo').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('photoPreview');
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        </script>
    @endpush
</x-app-layout>
