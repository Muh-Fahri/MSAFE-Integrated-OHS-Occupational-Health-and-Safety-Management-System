<x-app-layout>
    <div class="row">
        <div class="col-12">
           <div class="page-title-box d-flex align-items-center gap-2">
                <div class="page-title-left">
                    <a href="{{ route('users.index') }}" class="btn-back fs-4">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                </div>
                <h4 class="mb-0">User Detail</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">User Information</h4>
                        <div>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning rounded-pill btn-sm">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary rounded-pill btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 text-center mb-4 mb-md-0">
                            @if ($user->photo)
                                <img src="{{ route('storage.external', ['filename' => $user->photo]) }}"
                                    alt="{{ $user->name }}" class="img-thumbnail rounded-circle"
                                    style="width: 200px; height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 200px; height: 200px;">
                                    <i class="fas fa-user fa-5x text-muted"></i>
                                </div>
                            @endif
                            <div class="mt-3">
                                <span class="badge {{ $user->status == 'ACTIVE' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->status }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td width="250" class="fw-bold text-muted">Employee ID</td>
                                            <td width="20">:</td>
                                            <td class="fw-bold text-primary">{{ $user->employee_id ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Full Name</td>
                                            <td>:</td>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Username</td>
                                            <td>:</td>
                                            <td>{{ $user->username }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Email</td>
                                            <td>:</td>
                                            <td>{{ $user->email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Phone</td>
                                            <td>:</td>
                                            <td>{{ $user->phone ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Role</td>
                                            <td>:</td>
                                            <td><span class="badge bg-info">{{ $user->role->name ?? '-' }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Department</td>
                                            <td>:</td>
                                            <td>{{ $user->department->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Company</td>
                                            <td>:</td>
                                            <td>{{ $user->company->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Head of Department (HOD)</td>
                                            <td>:</td>
                                            <td>{{ $user->hod ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">HOD (Badge Request)</td>
                                            <td>:</td>
                                            <td>{{ $user->hod2 ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Created At</td>
                                            <td>:</td>
                                            <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end align-items-center">
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="me-2"
                            onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill">
                                <i class="fas fa-trash me-1"></i> Delete User
                            </button>
                        </form>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning rounded-pill">
                            <i class="fas fa-edit me-1"></i> Edit User Information
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
