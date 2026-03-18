<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center gap-2">
                <div class="page-title-left">
                    <a href="{{ route('users.index') }}" class="btn-back fs-4">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                </div>
                <h4 class="mb-0">Edit User</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">User Information</h4>
                    <p class="card-title-desc">Update the details below to edit user information.</p>

                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 row">
                            <label for="username" class="col-md-2 col-form-label">Username <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                    id="username" name="username" value="{{ old('username', $user->username) }}"
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
                                    id="name" name="name" value="{{ old('name', $user->name) }}"
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
                                    id="email" name="email" value="{{ old('email', $user->email) }}"
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
                                    id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
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
                                <select class="form-select @error('role_id') is-invalid @enderror" name="role_id"
                                    required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
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
                                    id="employee_id" name="employee_id"
                                    value="{{ old('employee_id', $user->employee_id) }}"
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
                                <select class="form-select @error('department_id') is-invalid @enderror"
                                    name="department_id" required>
                                    @foreach ($departments as $d)
                                        <option value="{{ $d->id }}"
                                            {{ old('department_id', $user->department_id) == $d->id ? 'selected' : '' }}>
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
                                <select class="form-select @error('company_id') is-invalid @enderror" name="company_id"
                                    required>
                                    @foreach ($companies as $c)
                                        <option value="{{ $c->id }}"
                                            {{ old('company_id', $user->company_id) == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
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
                                <select class="form-select @error('hod') is-invalid @enderror" name="hod"
                                    required>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}"
                                            {{ old('hod', $user->hod) == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
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
                                <select class="form-select @error('hod2') is-invalid @enderror" name="hod2"
                                    required>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}"
                                            {{ old('hod2', $user->hod2) == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
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
                                <div class="col-md-10">
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror" id="password"
                                        name="password"
                                        placeholder="Leave blank if you don't want to change password">
                                    <small class="text-muted">Min 8 characters. Fill only if you want to change current
                                        password.</small>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <label for="photo" class="col-md-2 col-form-label">Photo</label>
                            <div class="col-md-10">
                                <div class="mt-2">
                                    @if ($user->photo)
                                        <img id="oldPhoto"
                                            src="{{ route('storage.external', ['filename' => $user->photo]) }}"
                                            alt="Current Photo" style="max-width: 150px;"
                                            class="img-thumbnail d-block mb-2">
                                    @endif
                                    <img id="photoPreview" src="" alt="New Preview"
                                        style="max-width: 150px; display: none;" class="img-thumbnail">
                                </div>
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
