<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Master Data') }}
        </h2>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white p-3">
                <h5 class="mb-0 fw-bold">Company Master</h5>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Opps!:</strong>
                        </div>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('master-company.store') }}" method="POST">
                    @csrf
                </form>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('master-company.create') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-bold">Company Name</label>
                            <input type="text" name="name" id="name"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" class="form-control rounded-pill"
                                placeholder="Enter company name..." required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="parent_id" class="form-label fw-bold">Parent Company</label>
                            <select name="parent_id" id="parent_id" style="border-color: #dee2e6; padding-left: 1.5rem;"
                                class="form-select select2-custom">
                                <option value="">-- Select Parent Company --</option>
                                @foreach ($comp as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        {{-- penyesuaian jika diperlukan --}}
                        <div class="col-md-6 mb-3">
                            <label for="hse_manager_id" class="form-label fw-bold">HSE Manager</label>
                            <select name="hse_manager_id"  style="border-color: #dee2e6; padding-left: 1.5rem;" id="hse_manager_id" class="form-select select2-custom">
                                <option value="">-- Select Manager --</option>
                                @foreach ($user as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="pjo_id" class="form-label fw-bold">PJO</label>
                            <select name="pjo_id" id="pjo_id"  style="border-color: #dee2e6; padding-left: 1.5rem;" class="form-select select2-custom">
                                <option value="">-- Select PJO --</option>
                                @foreach ($user as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="permit_no" class="form-label fw-bold">Permit No</label>
                            <input type="text"  style="border-color: #dee2e6; padding-left: 1.5rem;" name="permit_no" id="permit_no" class="form-control rounded-pill"
                                placeholder="Enter permit number...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="industry" class="form-label fw-bold">Industry</label>
                            <input type="text"  style="border-color: #dee2e6; padding-left: 1.5rem;" name="industry" id="industry" class="form-control rounded-pill"
                                placeholder="e.g. Mining, Oil & Gas...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="permit_start_date" class="form-label fw-bold">Permit Start Date</label>
                            <input type="date"  style="border-color: #dee2e6; padding-left: 1.5rem;" name="permit_start_date" id="permit_start_date"
                                class="form-control rounded-pill">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="permit_end_date" class="form-label fw-bold">Permit End Date</label>
                            <input type="date"  style="border-color: #dee2e6; padding-left: 1.5rem;" name="permit_end_date" id="permit_end_date"
                                class="form-control rounded-pill">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('master-company.index') }}" class="btn btn-cancel px-4">Back</a>
                        <button type="submit" class="btn btn-submit px-4">
                            <i class="fas fa-save me-1"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2-custom').select2({
                    width: '100%'
                });
            });
        </script>
        <style>
            .select2-container--default .select2-selection--single {
                border-radius: 50rem !important;
                height: 38px !important;
                border: 1px solid #dee2e6 !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 36px !important;
                padding-left: 15px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px !important;
            }
        </style>
    @endpush
</x-app-layout>
