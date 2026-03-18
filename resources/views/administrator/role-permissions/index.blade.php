<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Role Permission Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Manage Role Permissions</h4>
                    <p class="card-title-desc">Set permissions for each role to control menu access.</p>

                    <!-- Role Selection -->
                    <div class="mb-4">
                        <label for="roleSelect" class="form-label">Select Role:</label>
                        <select id="roleSelect" class="form-select" style="max-width: 300px;">
                            <option value="">-- Choose Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Permission Matrix -->
                    <div id="permissionMatrix" style="display: none;">
                        <form id="permissionForm">
                            @csrf
                            <input type="hidden" name="role_id" id="roleIdInput">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light" id="tableHeader">
                                        <!-- Headers akan di-generate dinamis -->
                                    </thead>
                                    <tbody id="menuTableBody">
                                        <!-- Loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Permissions
                                </button>
                                <button type="button" class="btn btn-secondary" id="resetBtn">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="emptyState" class="text-center py-5">
                        <i class="fas fa-shield-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Please select a role to manage permissions</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="{{ asset('Minible/HTML/dist/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
        <style>
            .menu-parent {
                font-weight: 600;
            }
            .menu-child {
                padding-left: 30px;
            }
            .menu-child::before {
                content: "↳ ";
                margin-right: 5px;
                color: #6c757d;
            }
            .form-check-input {
                cursor: pointer;
                border: 1px solid #ced4da;
                width: 15px;
                height: 15px;
            }
            .form-check-input:not(:checked) {
                background-color: #fff;
                border: 1px solid #ced4da;
            }
            .form-check-input:focus {
                border-color: #86b7fe;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }
            .action-disabled {
                background-color: #f8f9fa;
                cursor: not-allowed;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('Minible/HTML/dist/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                let currentRoleId = null;
                let allActions = ['create', 'edit', 'delete', 'show', 'export', 'import'];
                let availableActionsMap = {}; // Store available actions per menu

                // Load permissions when role is selected
                $('#roleSelect').change(function() {
                    currentRoleId = $(this).val();

                    if (currentRoleId) {
                        loadPermissions(currentRoleId);
                        $('#emptyState').hide();
                        $('#permissionMatrix').show();
                        $('#roleIdInput').val(currentRoleId);
                    } else {
                        $('#emptyState').show();
                        $('#permissionMatrix').hide();
                    }
                });

                // Load permissions via AJAX
                function loadPermissions(roleId) {
                    $.ajax({
                        url: "{{ url('administrator/role-permissions') }}/" + roleId,
                        type: 'GET',
                        success: function(response) {
                            allActions = response.all_actions;

                            // Build available actions map
                            availableActionsMap = {};
                            response.menus.forEach(menu => {
                                availableActionsMap[menu.id] = menu.available_actions;
                                if (menu.children) {
                                    menu.children.forEach(child => {
                                        availableActionsMap[child.id] = child.available_actions;
                                    });
                                }
                            });

                            renderTableHeader(response.menus);
                            renderMenuTable(response.menus, response.details);
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to load permissions'
                            });
                        }
                    });
                }

                // Render table header berdasarkan actions yang ada
                function renderTableHeader(menus) {
                    // Collect all unique available actions from all menus
                    let usedActions = new Set();
                    menus.forEach(menu => {
                        menu.available_actions.forEach(action => usedActions.add(action));
                        if (menu.children) {
                            menu.children.forEach(child => {
                                child.available_actions.forEach(action => usedActions.add(action));
                            });
                        }
                    });

                    // Convert to array and sort by predefined order
                    let sortedActions = allActions.filter(action => usedActions.has(action));

                    let headerHtml = '<tr><th width="30%">Menu Name</th>';

                    sortedActions.forEach(action => {
                        headerHtml += `
                            <th class="text-center" width="${60/sortedActions.length}%">
                                <input type="checkbox" class="form-check-input header-checkbox" data-permission="${action}">
                                ${action.charAt(0).toUpperCase() + action.slice(1)}
                            </th>`;
                    });

                    headerHtml += '<th class="text-center" width="10%">Action</th></tr>';
                    $('#tableHeader').html(headerHtml);
                }

                // Render menu table with permissions
                function renderMenuTable(menus, details) {
                    let html = '';

                    menus.forEach(function(menu) {
                        const hasChildren = menu.children && menu.children.length > 0;
                        const menuDetails = details[menu.id];
                        const availableActions = menu.available_actions;

                        // Parent menu row
                        html += `<tr><td class="menu-parent">${menu.menu_name}</td>`;

                        allActions.forEach(function(perm) {
                            if (availableActions.includes(perm)) {
                                const checked = menuDetails && menuDetails[perm] ? 'checked' : '';
                                html += `<td class="text-center">
                                    <input type="checkbox" class="form-check-input permission-checkbox"
                                        name="permissions[${menu.id}][${perm}]"
                                        data-menu-id="${menu.id}"
                                        data-permission="${perm}"
                                        ${checked}>
                                </td>`;
                            }
                        });

                        // Select All button for parent with children
                        if (hasChildren) {
                            html += `<td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary select-all-children"
                                    data-parent-id="${menu.id}">
                                    <i class="fas fa-check-double"></i> All
                                </button>
                            </td>`;
                        } else {
                            html += `<td></td>`;
                        }

                        html += `</tr>`;

                        // Child menus
                        if (hasChildren) {
                            menu.children.forEach(function(child) {
                                const childDetails = details[child.id];
                                const childAvailableActions = child.available_actions;

                                html += `<tr><td class="menu-child">${child.menu_name}</td>`;

                                allActions.forEach(function(perm) {
                                    if (childAvailableActions.includes(perm)) {
                                        const checked = childDetails && childDetails[perm] ? 'checked' : '';
                                        html += `<td class="text-center">
                                            <input type="checkbox" class="form-check-input permission-checkbox child-checkbox"
                                                name="permissions[${child.id}][${perm}]"
                                                data-menu-id="${child.id}"
                                                data-parent-id="${menu.id}"
                                                data-permission="${perm}"
                                                ${checked}>
                                        </td>`;
                                    }
                                });

                                html += `<td></td></tr>`;
                            });
                        }
                    });

                    $('#menuTableBody').html(html);
                }

                // Header checkbox: Select all in column
                $(document).on('change', '.header-checkbox', function() {
                    const permission = $(this).data('permission');
                    const isChecked = $(this).prop('checked');

                    $(`.permission-checkbox[data-permission="${permission}"]`).prop('checked', isChecked);
                });

                // Select all children for a parent
                $(document).on('click', '.select-all-children', function() {
                    const parentId = $(this).data('parent-id');
                    const childCheckboxes = $(`.child-checkbox[data-parent-id="${parentId}"]`);
                    const allChecked = childCheckboxes.filter(':checked').length === childCheckboxes.length;

                    childCheckboxes.prop('checked', !allChecked);

                    // Update button icon
                    const icon = $(this).find('i');
                    if (!allChecked) {
                        icon.removeClass('fa-check-double').addClass('fa-times');
                    } else {
                        icon.removeClass('fa-times').addClass('fa-check-double');
                    }
                });

                // Submit form
                $('#permissionForm').submit(function(e) {
                    e.preventDefault();

                    const formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('role-permissions.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to save permissions'
                            });
                        }
                    });
                });

                // Reset button
                $('#resetBtn').click(function() {
                    if (currentRoleId) {
                        loadPermissions(currentRoleId);
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
