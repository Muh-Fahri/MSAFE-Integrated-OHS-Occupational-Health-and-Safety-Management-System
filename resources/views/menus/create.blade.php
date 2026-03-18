<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Add New Menu</h4>
                <div class="page-title-right">
                    <a href="{{ route('menus.index') }}" class="btn btn-secondary">
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
                    <h4 class="card-title">Menu Information</h4>
                    <p class="card-title-desc">Fill in the form below to create a new menu.</p>

                    <form action="{{ route('menus.store') }}" method="POST" id="menuForm">
                        @csrf

                        <div class="row mb-3">
                            <label for="menu_name" class="col-md-2 col-form-label">Menu Name <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('menu_name') is-invalid @enderror"
                                    id="menu_name" name="menu_name" value="{{ old('menu_name') }}"
                                    placeholder="Enter menu name" required>
                                @error('menu_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="url" class="col-md-2 col-form-label">URL</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('url') is-invalid @enderror"
                                    id="url" name="url" value="{{ old('url') }}"
                                    placeholder="e.g., products.index or # for parent">
                                <small class="text-muted">Enter route name (e.g., products.index). System will
                                    auto-detect available actions.</small>
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="parent_id" class="col-md-2 col-form-label">Parent Menu</label>
                            <div class="col-md-10">
                                <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id"
                                    name="parent_id">
                                    <option value="">-- Select Parent (Leave empty for parent menu) --</option>
                                    @foreach ($parents as $parent)
                                        <option value="{{ $parent->id }}"
                                            {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->menu_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Leave empty if this is a parent menu</small>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3" id="iconField">
                            <label for="icon" class="col-md-2 col-form-label">Icon <span class="text-danger"
                                    id="iconRequired">*</span></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                    id="icon" name="icon" value="{{ old('icon') }}"
                                    placeholder="e.g., fas fa-home">
                                <small class="text-muted">Use Font Awesome classes. Example: fas fa-home, fas
                                    fa-users</small>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="order" class="col-md-2 col-form-label">Order <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                    id="order" name="order" value="{{ old('order', 0) }}" min="0"
                                    required>
                                <small class="text-muted">Lower number appears first</small>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Available Actions Section -->
                        <div class="row mb-3" id="actionsSection">
                            <label class="col-md-2 col-form-label">Available Actions</label>
                            <div class="col-md-10">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_manual_override"
                                                    name="is_manual_override" value="1">
                                                <label class="form-check-label" for="is_manual_override">
                                                    <strong>Manual Override</strong>
                                                    <small class="text-muted d-block">Enable to manually select actions
                                                        instead of auto-detection</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div id="autoDetectInfo" class="alert alert-info mb-3" style="display: none;">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Auto-detected:</strong> <span id="detectedActionsText">-</span>
                                        </div>

                                        <div id="actionsCheckboxes">
                                            @foreach ($allActions as $action)
                                                <div class="form-check form-check-inline action-checkbox-wrapper"
                                                    data-action="{{ $action }}">
                                                    <input class="form-check-input action-checkbox" type="checkbox"
                                                        id="action_{{ $action }}" name="available_actions[]"
                                                        value="{{ $action }}">
                                                    <label class="form-check-label" for="action_{{ $action }}">
                                                        <span class="action-icon"
                                                            data-action="{{ $action }}"></span>
                                                        {{ ucfirst($action) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-check-circle text-success"></i> Auto-detected from
                                                routes
                                                <i class="fas fa-exclamation-triangle text-warning ms-2"></i> Manual
                                                override
                                                <i class="fas fa-times-circle text-secondary ms-2"></i> Not available
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-10 offset-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Menu
                                </button>
                                <a href="{{ route('menus.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .action-icon::before {
                font-family: "Font Awesome 5 Free";
                font-weight: 900;
                margin-right: 3px;
            }

            .action-icon.detected::before {
                content: "\f058";
                /* fa-check-circle */
                color: #28a745;
            }

            .action-icon.manual::before {
                content: "\f071";
                /* fa-exclamation-triangle */
                color: #ffc107;
            }

            .action-icon.disabled::before {
                content: "\f057";
                /* fa-times-circle */
                color: #6c757d;
            }

            .action-checkbox:disabled+label {
                opacity: 0.6;
                cursor: not-allowed;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                let detectedActions = [];
                let isManualOverride = false;

                // Function to toggle icon field
                function toggleIconField() {
                    var parentId = $('#parent_id').val();
                    var iconField = $('#icon');
                    var iconRequired = $('#iconRequired');

                    if (parentId) {
                        iconField.prop('disabled', true);
                        iconField.val('');
                        iconField.prop('required', false);
                        iconRequired.hide();
                        $('#iconField').addClass('opacity-50');
                    } else {
                        iconField.prop('disabled', false);
                        iconField.prop('required', true);
                        iconRequired.show();
                        $('#iconField').removeClass('opacity-50');
                    }
                }

                // Function to detect actions from URL
                function detectActions() {
                    var url = $('#url').val();

                    if (!url || url === '#') {
                        detectedActions = [];
                        updateActionsUI();
                        return;
                    }

                    $.ajax({
                        url: "{{ route('menus.detect-actions') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            route_name: url
                        },
                        success: function(response) {
                            detectedActions = response.detected_actions || [];
                            updateActionsUI();
                        }
                    });
                }

                // Function to update actions UI
                function updateActionsUI() {
                    if (isManualOverride) {
                        // Manual mode: enable all checkboxes
                        $('.action-checkbox').prop('disabled', false);
                        $('.action-icon').removeClass('detected disabled').addClass('manual');
                        $('#autoDetectInfo').hide();
                    } else {
                        // Auto-detect mode
                        if (detectedActions.length > 0) {
                            $('#autoDetectInfo').show();
                            $('#detectedActionsText').text(detectedActions.map(a => a.charAt(0).toUpperCase() + a.slice(
                                1)).join(', '));
                        } else {
                            $('#autoDetectInfo').hide();
                        }

                        $('.action-checkbox').each(function() {
                            var action = $(this).val();
                            var icon = $(this).siblings('label').find('.action-icon');

                            if (detectedActions.includes(action)) {
                                $(this).prop('checked', true).prop('disabled', true);
                                icon.removeClass('manual disabled').addClass('detected');
                            } else {
                                $(this).prop('checked', false).prop('disabled', true);
                                icon.removeClass('detected manual').addClass('disabled');
                            }
                        });
                    }
                }

                // Toggle manual override
                $('#is_manual_override').change(function() {
                    isManualOverride = $(this).is(':checked');
                    updateActionsUI();
                });

                // Detect actions when URL changes (debounced)
                let detectTimeout;
                $('#url').on('input', function() {
                    clearTimeout(detectTimeout);
                    detectTimeout = setTimeout(detectActions, 500);
                });

                // Run on page load
                toggleIconField();
                detectActions();

                // Run when parent dropdown changes
                $('#parent_id').on('change', function() {
                    toggleIconField();
                });

                // Validation before submit
                $('#menuForm').on('submit', function(e) {
                    var parentId = $('#parent_id').val();
                    var icon = $('#icon').val();

                    if (!parentId && !icon) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Icon Required',
                            text: 'Parent menu must have an icon!'
                        });
                        return false;
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
