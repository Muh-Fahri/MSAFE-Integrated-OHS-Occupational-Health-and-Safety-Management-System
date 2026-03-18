<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Department') }}
        </h2>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white p-3">
                <h5 class="mb-0 fw-bold">Master Department</h5>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert"
                        style="border-left: 5px solid #dc3545 !important;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
                            <div>
                                <strong class="d-block">Opps!:</strong>
                                <ul class="mb-0 mt-1 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('master-department.update', $dept->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label fw-bold">Department Code</label>
                            <input type="text" name="code" id="code" class="form-control rounded-pill"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" placeholder="e.g. HRD, IT, ENG..."
                                value="{{ old('code', $dept->code) }}" required>
                            <div class="form-text text-muted">Unique code for the department.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-bold">Department Name</label>
                            <input type="text" name="name" id="name" class="form-control rounded-pill"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                placeholder="Enter department name..." value="{{ old('name', $dept->name) }}" required>
                        </div>
                        <input type="hidden" name="old_ids" value="{{ old('old_ids', $dept->old_ids) }}">
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('master-department.index') }}" class="btn btn-secondary px-4 rounded-pill">
                            Back
                        </a>
                        <button type="submit" class="btn btn-submit px-4">
                            <i class="fas fa-save me-1"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
