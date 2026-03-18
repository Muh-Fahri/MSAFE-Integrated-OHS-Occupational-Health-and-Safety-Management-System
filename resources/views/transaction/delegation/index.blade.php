<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Delegation</h4>
                <div class="page-title-right">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
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
                    <h4 class="card-title">Delegation Information</h4>
                    <p class="card-title-desc">Fill in the form below to create a delegation.</p>
                    <form action="{{ route('transaction-delegation.store') }}" method="POST" id="delegationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="delegation_start_date" class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                            <input style="border-color: #dee2e6; padding-left: 1.5rem;" type="date"
                                name="delegation_start_date" id="delegation_start_date" class="form-control rounded-pill" 
                                value="{{ old('delegation_start_date', $delegation_start_date) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="delegation_end_date" class="form-label fw-bold">End Date <span class="text-danger">*</span></label>
                            <input style="border-color: #dee2e6; padding-left: 1.5rem;" type="date"
                                name="delegation_end_date" id="delegation_end_date" class="form-control rounded-pill" 
                                value="{{ old('delegation_end_date', $delegation_end_date) }}" required> 
                        </div>
                        <div class="mb-3">
                            <label for="delegation_user_id" class="form-label fw-bold">User <span class="text-danger">*</span></label>
                            <select
                                class="form-select rounded-pill text-secondary border-2  @error('delegation_user_id') is-invalid @enderror"
                                id="delegation_user_id" name="delegation_user_id" 
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ old('delegation_user_id', $delegation_user_id) }}" required>
                                <option value="">Select User</option>
                                @foreach ($list_user as $user_id=>$user_name)
                                    <option value="{{ $user_id }}"
                                        {{ old('delegation_user_id', $delegation_user_id) == $user_id ? 'selected' : '' }}>{{ $user_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('delegation_user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        
						<button type="button" class="btn btn-danger btn-remove"> Un-Delegate </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Delegate
                        </button>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /*  */
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#delegation_user_id').select2({
                    placeholder: 'Select User',
                    allowClear: true,
                    width: '100%'
                });
                $('.btn-remove').on('click', function(){
                    $('#delegation_start_date').val('');
                    $('#delegation_end_date').val('');
                    $('#delegation_user_id').val('').trigger('change');
                    $('#delegationForm')[0].submit();
                });

                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        timer: 2000,
                        showConfirmButton: false
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#F76361'
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
