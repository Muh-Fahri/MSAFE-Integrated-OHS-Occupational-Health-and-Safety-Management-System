<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center gap-2">
                <div class="page-title-left">
                    <a href="{{ route('dashboard') }}" class="btn-back fs-4">
                        <i class="fas fa-arrow-left me-1"></i>
                    </a>
                </div>
                <h4 class="mb-0">User Management</h4>
            </div>
            @php
                $menuId = \App\Helpers\PermissionHelper::getCurrentMenuId();
            @endphp
            <div class="grid gap-2 d-flex mb-4">
                @if ($menuId && \App\Helpers\PermissionHelper::hasPermission($menuId, 'create'))
                    <div class="mx-1">
                        <a href="{{ route('users.create') }}" class="btn btn-add btn-sm">
                            <i class="fas fa-plus me-1"></i> Add New User
                        </a>
                    </div>
                @endif
                <button type="button" class="btn btn-sm btn-search" data-bs-toggle="modal"
                    data-bs-target="#searchModal">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>
        </div>
    </div>

    {{-- modal search --}}
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="searchModalLabel fw-bold"><i class="fas fa-filter me-2"></i>Filter Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.index') }}" method="GET">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="{{ request('action') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Username</label>
                                <input type="text" name="username" id="username" class="form-control rounded-pill"
                                    value="{{ request('username') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Name</label>
                                <input type="text" name="name" id="name" class="form-control rounded-pill"
                                    value="{{ request('name') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email</label>
                                <input type="text" name="email" id="email" class="form-control rounded-pill"
                                    value="{{ request('email') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control rounded-pill"
                                    value="{{ request('phone') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Role</label>
                                <input type="text" name="role" id="role" class="form-control rounded-pill"
                                    value="{{ request('role') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Employee ID</label>
                                <input type="text" name="employee_id" id="employee_id"
                                    class="form-control rounded-pill" value="{{ request('employee_id') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select rounded-pill">
                                    <option value="">-- All Status --</option>
                                    <option value="ACTIVE" {{ request('status') == 'ACTIVE' ? 'selected' : '' }}>ACTIVE
                                    </option>
                                    <option value="INACTIVE" {{ request('status') == 'INACTIVE' ? 'selected' : '' }}>
                                        INACTIVE</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <a href="{{ route('users.index') }}" class="btn btn-light rounded-pill px-4">Reset</a>
                        <button type="submit" class="btn btn-search px-4">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th>Company</th>
                                <th>Head Of Department</th>
                                <th>Head Of Department (Badge)</th>
                                <th>Employee ID</th>
                                <th>Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <!-- DataTables -->
        <link href="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
            rel="stylesheet" type="text/css" />
        <link
            href="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
            rel="stylesheet" type="text/css" />
        <link
            href="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
            rel="stylesheet" type="text/css" />

        <!-- Sweet Alert -->
        <link href="{{ asset('Minible/HTML/dist/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
            type="text/css" />

        <style>
            #datatable {
                font-size: 11px !important;
                /* Ukuran font lebih kecil */
                width: 100% !important;
            }

            #datatable th,
            #datatable td {
                padding: 6px 4px !important;
                vertical-align: middle !important;
                white-space: normal !important;
            }

            #datatable td:last-child {
                white-space: nowrap !important;
            }

            .table-bordered td,
            .table-bordered th {
                border-left: none !important;
                border-right: none !important;
            }

            .btn-xs {
                padding: 1px 5px !important;
                font-size: 10px !important;
                line-height: 1.5;
                border-radius: 3px;
            }

            .fa-xs {
                font-size: 0.70rem !important;
            }

            #datatable td:last-child {
                text-align: center;
                vertical-align: middle;
                white-space: nowrap !important;
                width: 1%;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- Required datatable js -->
        <script src="{{ asset('Minible/HTML/dist/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Responsive examples -->
        <script src="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
        </script>
        <script
            src="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
        </script>

        <!-- Sweet Alerts js -->
        <script src="{{ asset('Minible/HTML/dist/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                // Initialize DataTable with server-side processing
                var table = $('#datatable').DataTable({
                    processing: true,
                    responsive: false,
                    serverSide: true,
                    lengthChange: false,
                    searching: false,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('users.index') }}",
                        data: function(d) {
                            // mengambil data sesuai dengan id pada modal
                            d.username = $('#username').val();
                            d.name = $('#name').val();
                            d.email = $('#email').val();
                            d.phone = $('#phone').val();
                            d.role = $('#role').val();
                            d.employee_id = $('#employee_id').val();
                            d.status = $('select[name="status"]').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'username',
                            name: 'username'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'role_name',
                            name: 'role_name'
                        }, {
                            data: 'department_name',
                            name: 'department_name'
                        }, {
                            data: 'company_name',
                            name: 'company_name'
                        },
                        {
                            data: 'hod_name',
                            name: 'hod_name'
                        }, {
                            data: 'hod2_name',
                            name: 'hod2_name'
                        },
                        {
                            data: 'employee_id',
                            name: 'employee_id'
                        },
                        {
                            data: 'status',
                            name: 'status',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    language: {
                        processing: '<i class="fas fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                    }
                });

                // Success message
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 2000
                    });
                @endif
            });

            // Delete function
            function deleteUser(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('administrator/users') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: 'User has been deleted.',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $('#datatable').DataTable().ajax.reload();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong!'
                                });
                            }
                        });
                    }
                });
            }
            // Eksekusi tombol "apply filter" pada modal search
            $('#searchModal form').on('submit', function(e) {
                e.preventDefault();
                $('#datatable').DataTable().draw();
                $('#searchModal').modal('hide');
            });
        </script>
    @endpush
</x-app-layout>
