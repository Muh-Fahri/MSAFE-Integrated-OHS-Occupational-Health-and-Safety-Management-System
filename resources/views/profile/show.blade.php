<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">My Profile</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Profile Information</h4>
                    <p class="card-title-desc">Update your profile information below.</p>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3 row">
                            <label for="username" class="col-md-2 col-form-label">Username <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                    id="username" name="username" value="{{ old('username', $user->username) }}"
                                    placeholder="Enter username" required readonly>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="name" class="col-md-2 col-form-label">Full Name <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}"
                                    placeholder="Enter full name" required  readonly>
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
                                    placeholder="Enter email address"  readonly>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="photo" class="col-md-2 col-form-label">Photo</label>
                            <div class="col-md-10">
                                @if ($user->photo)
                                    <div class="mb-2" id="currentPhotoContainer">
                                        <img src="{{ asset('uploads/users/' . $user->photo) }}" alt="Current Photo"
                                            class="img-thumbnail" style="max-width: 150px;" id="currentPhoto">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="markPhotoForDeletion()">
                                                <i class="fas fa-trash"></i> Delete Photo
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                onclick="cancelPhotoDelete()" id="cancelDeleteBtn"
                                                style="display: none;">
                                                <i class="fas fa-undo"></i> Cancel
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                <!-- Hidden input untuk mark foto akan dihapus -->
                                <input type="hidden" name="remove_photo" id="remove_photo" value="0">

                                <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                    id="photo" name="photo" accept="image/jpeg,image/png,image/jpg">
                                <small class="text-muted">Max 2MB. Format: JPG, JPEG, PNG.</small>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <img id="photoPreview" src="" alt="Photo Preview"
                                        style="max-width: 150px; display: none;" class="img-thumbnail">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Change Password</h5>
                        <p class="text-muted small">Leave empty if you don't want to change password.</p>

                        <div class="mb-3 row">
                            <label for="current_password" class="col-md-2 col-form-label">Current Password</label>
                            <div class="col-md-10">
                                <input type="password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password"
                                    placeholder="Enter current password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="password" class="col-md-2 col-form-label">New Password</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Enter new password">
                                <small class="text-muted">Minimum 8 characters.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="password_confirmation" class="col-md-2 col-form-label">Confirm
                                Password</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-10 offset-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Profile
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                                </a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <!-- Sweet Alert -->
        <link href="{{ asset('Minible/HTML/dist/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
            type="text/css" />
    @endpush

    @push('scripts')
        <!-- Sweet Alerts js -->
        <script src="{{ asset('Minible/HTML/dist/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

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

                        // Hide current photo when preview shown
                        const currentPhotoContainer = document.getElementById('currentPhotoContainer');
                        if (currentPhotoContainer) {
                            currentPhotoContainer.style.opacity = '0.5';
                        }

                        // Reset remove_photo flag karena user upload foto baru
                        document.getElementById('remove_photo').value = '0';

                        // Hide cancel button & reset photo style
                        const cancelBtn = document.getElementById('cancelDeleteBtn');
                        const currentPhoto = document.getElementById('currentPhoto');
                        if (cancelBtn) {
                            cancelBtn.style.display = 'none';
                        }
                        if (currentPhoto) {
                            currentPhoto.style.opacity = '1';
                            currentPhoto.style.filter = 'none';
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Mark photo for deletion (soft delete)
            function markPhotoForDeletion() {
                Swal.fire({
                    title: 'Delete Photo?',
                    text: "Photo will be deleted when you click 'Update Profile'",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const currentPhoto = document.getElementById('currentPhoto');
                        const cancelBtn = document.getElementById('cancelDeleteBtn');
                        const removePhotoInput = document.getElementById('remove_photo');

                        if (currentPhoto) {
                            // Tambah efek visual (opacity + grayscale)
                            currentPhoto.style.opacity = '0.3';
                            currentPhoto.style.filter = 'grayscale(100%)';
                        }

                        // Show cancel button
                        if (cancelBtn) {
                            cancelBtn.style.display = 'inline-block';
                        }

                        // Set flag untuk hapus foto
                        removePhotoInput.value = '1';
                    }
                });
            }

            // Cancel photo deletion
            function cancelPhotoDelete() {
                const currentPhoto = document.getElementById('currentPhoto');
                const cancelBtn = document.getElementById('cancelDeleteBtn');
                const removePhotoInput = document.getElementById('remove_photo');

                if (currentPhoto) {
                    // Reset efek visual
                    currentPhoto.style.opacity = '1';
                    currentPhoto.style.filter = 'none';
                }

                // Hide cancel button
                if (cancelBtn) {
                    cancelBtn.style.display = 'none';
                }

                // Reset flag
                removePhotoInput.value = '0';

                Swal.fire({
                    icon: 'info',
                    title: 'Cancelled',
                    text: 'Photo was not deleted',
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            // Success message dari session
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif
        </script>
    @endpush
</x-app-layout>
