<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Menu Management</h4>
            </div>
            @php
                $menuId = \App\Helpers\PermissionHelper::getCurrentMenuId();
            @endphp
            @if ($menuId && \App\Helpers\PermissionHelper::hasPermission($menuId, 'create'))
                <div class="grid gap-2 d-flex mb-4">
                    <div class="mx-1">
                        <a href="{{ route('menus.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Add New Menu
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Menu List</h4>
                    <p class="card-title-desc">Manage your app menu and sidebar structure.</p>

                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Menu Name</th>
                                <th>URL</th>
                                <th>Parent</th>
                                <th>Icon</th>
                                <th>Order</th>
                                <th>Type</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Server-side data -->
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <!-- DataTables -->
    <link href="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Sweet Alert -->
    <link href="{{ asset('Minible/HTML/dist/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush

    @push('scripts')
    <!-- Required datatable js -->
    <script src="{{ asset('Minible/HTML/dist/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('Minible/HTML/dist/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Sweet Alerts js -->
    <script src="{{ asset('Minible/HTML/dist/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with server-side processing
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('menus.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'menu_name', name: 'menu_name'},
                    {data: 'url', name: 'url'},
                    {data: 'parent_name', name: 'parent.menu_name'},
                    {data: 'icon', name: 'icon'},
                    {data: 'order', name: 'order'},
                    {data: 'type', name: 'type'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive: true,
                language: {
                    processing: '<i class="fas fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });

            // Success message
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session("success") }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif
        });

        // Delete function
        function deleteMenu(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! All child menus will also be deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('menus') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.success,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#datatable').DataTable().ajax.reload();
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
    </script>
    @endpush
</x-app-layout>
