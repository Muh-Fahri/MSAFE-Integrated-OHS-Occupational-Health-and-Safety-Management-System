<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center">
                <a href="{{ route('transaction-incidentNotification.index') }}" class="btn fs-4 btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0">View Incident Notification</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title mb-0 fw-bold">Event Details</h5>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Event Title</label>
                        <div class="col-md-10">
                            <input type="text" name="event_title" readonly
                                class="form-control bg-light rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;" value="{{ $item->event_title }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Date/Time</label>
                        <div class="col-md-10">
                            <input type="datetime-local" name="event_dateTime" readonly
                                class="form-control bg-light rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ date('Y-m-d\TH:i', strtotime($item->event_datetime)) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Reported Date</label>
                        <div class="col-md-10">
                            <input type="date" name="report_date"
                                class="form-control bg-light rounded-pill text-secondary border-2"
                                style="border-color: #dee2e6; padding-left: 1.5rem;"
                                value="{{ date('Y-m-d', strtotime($item->report_date)) }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Company</label>
                        <div class="col-md-10">
                            <select class="form-control bg-light rounded-pill text-secondary border-2" disabled
                                style="border-color: #dee2e6; padding-left: 1.5rem;" name="company_id">
                                @foreach ($company as $c)
                                    <option value="{{ $c->id }}"
                                        {{ $item->company_id == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Department</label>
                        <div class="col-md-10">
                            <select class="form-control bg-light rounded-pill text-secondary border-2" disabled
                                style="border-color: #dee2e6; padding-left: 1.5rem;" name="department_id">
                                @foreach ($department as $d)
                                    <option value="{{ $d->id }}"
                                        {{ $item->department_id == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Location</label>
                        <div class="col-md-10">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <select class="form-control bg-light rounded-pill text-secondary border-2" disabled
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" name="location_type"
                                        id="location_type" onchange="toggleLocationInput()">
                                        @foreach ($loc_type as $l)
                                            <option value="{{ $l }}"
                                                {{ $item->location_type == $l ? 'selected' : '' }}>
                                                {{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6" id="location_container">
                                    <select
                                        class="form-control bg-light rounded-pill text-secondary border-2 {{ $item->location_type == 'Off Site' ? 'd-none' : '' }}"
                                        disabled name="location" id="location_select"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        {{ $item->location_type == 'Off Site' ? 'disabled' : '' }}>
                                        @foreach ($location as $lo)
                                            <option value="{{ $lo->name }}"
                                                {{ $item->location == $lo->name ? 'selected' : '' }}>
                                                {{ $lo->name }}</option>
                                        @endforeach
                                    </select>

                                    <input type="text" name="location" id="location_input" readonly
                                        class="form-control bg-light rounded-pill text-secondary borer-2 {{ $item->location_type == 'Off Site' ? '' : 'd-none' }}"
                                        value="{{ $item->location }}"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        {{ $item->location_type == 'Off Site' ? '' : 'disabled' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Event Type</label>
                        <div class="col-md-10">
                            <select class="form-control bg-light rounded-pill text-secondary border-2" disabled
                                style="border-color: #dee2e6; padding-left: 1.5rem;" name="event_type">
                                @foreach ($ev_type as $et)
                                    <option value="{{ $et }}"
                                        {{ $item->event_type == $et ? 'selected' : '' }}>{{ $et }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Severity Level</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small">Actual Severity</label>
                                    <select class="form-control bg-light rounded-pill text-secondary border-2 mb-3"
                                        disabled style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        name="severity_level_actual">
                                        @foreach ($act_sev as $as)
                                            <option value="{{ $as }}"
                                                {{ $item->severity_level_actual == $as ? 'selected' : '' }}>
                                                {{ $as }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text"
                                        class="form-control bg-light rounded-pill text-secondary border-2"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" readonly
                                        name="severity_level_actual_remarks"
                                        value="{{ $item->severity_level_actual_remarks }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Potential Severity</label>
                                    <select class="form-control bg-light rounded-pill text-secondary border-2 mb-3"
                                        style="border-color: #dee2e6; padding-left: 1.5rem;" disabled
                                        name="severity_level_potential">
                                        @foreach ($pot_sev as $pv)
                                            <option value="{{ $pv }}"
                                                {{ $item->severity_level_potential == $pv ? 'selected' : '' }}>
                                                {{ $pv }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="severity_level_potential_remarks"
                                        class="form-control bg-light rounded-pill text-secondary border-2" readonly
                                        style="border-color: #dee2e6; padding-left: 1.5rem;"
                                        value="{{ $item->severity_level_potential_remarks }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Work Related</label>
                        <div class="col-md-10">
                            <select class="form-control bg-light rounded-pill text-secondary border-2" disabled
                                style="border-color: #dee2e6; padding-left: 1.5rem;" name="work_related">
                                <option value="Yes" {{ $item->work_related == 'Yes' ? 'selected' : '' }}>Yes
                                </option>
                                <option value="No" {{ $item->work_related == 'No' ? 'selected' : '' }}>No
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title mb-0 fw-bold">Incident Summary</h5>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Description</label>
                        <div class="col-md-10">
                            <textarea name="incident_description" class="form-control bg-light rounded-3 text-secondary border-2" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4">{{ $item->incident_description }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Immediate Actions</label>
                        <div class="col-md-10">
                            <textarea name="immediate_actions" class="form-control bg-light rounded-3 text-secondary border-2" readonly
                                style="border-color: #dee2e6; padding-left: 1.5rem;" rows="4">{{ $item->immediate_actions }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2 col-form-label">Evidence Photos</label>
                        <div class="col-md-10">
                            <div class="row">
                                @for ($i = 1; $i <= 4; $i++)
                                    @php
                                        $photoField = "photo_{$i}_path";
                                        $fileName = $item->$photoField;
                                    @endphp

                                    @if ($fileName && trim($fileName) !== '')
                                        <div class="col-md-6 col-lg-3 mb-4">
                                            <div class="mb-2">
                                                <a href="{{ route('storage.external', ['folder' => 'incident', 'filename' => $fileName]) }}"
                                                    target="_blank">
                                                    <img src="{{ route('storage.external', ['folder' => 'incident', 'filename' => $fileName]) }}"
                                                        alt="Evidence {{ $i }}"
                                                        class="img-thumbnail shadow-sm"
                                                        style="max-height: 200px; width: 100%; object-fit: cover; border: 2px solid #dee2e6;">
                                                </a>
                                                <div class="mt-1 small text-muted text-center">Photo{{ $i }}</div>
                                            </div>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        {{-- Button Approve --}}
                        @if ($item->next_action != 'INVESTIGATION')
                            @if ($item->next_user_id === Auth::user()->id)
                                <div class="d-flex gap-2 justify-content-center"> {{-- Pembungkus agar tombol sejajar --}}

                                    {{-- Form Setujui --}}
                                    <form action="{{ route('transaction-incidentNotification.approve', $item->id) }}"
                                        method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="action" value="APPROVE">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-success btn-approve-swal px-3 rounded-pill">
                                            <i class="fas fa-check me-1"></i> Setujui
                                        </button>
                                    </form>

                                    {{-- Form Tolak --}}
                                    <form action="{{ route('transaction-incidentNotification.approve', $item->id) }}"
                                        method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="action" value="REJECT">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-reject-swal px-3 rounded-pill">
                                            <i class="fas fa-times me-1"></i> Tolak
                                        </button>
                                    </form>

                                </div>
                            @endif
                        @endif

                        {{-- Button Edit --}}
                        @if ($item->reporter_id === Auth::user()->id)
                            <a href="{{ route('transaction-incidentNotification.edit', $item->id) }}"
                                class="btn btn-sm btn-outline-warning px-3 rounded-pill" title="Edit Data">
                                <i class="fas fa-pen me-1"></i> Edit
                            </a>

                            {{-- Button Delete --}}
                            <form action="{{ route('transaction-incidentNotification.delete', $item->id) }}"
                                method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="btn btn-sm btn-danger btn-confirm-delete px-3 rounded-pill shadow-sm">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // 1. Fungsi toggle lokasi
        function toggleLocationInput() {
            const locationType = document.getElementById('location_type').value;
            const selectElement = document.getElementById('location_select');
            const inputElement = document.getElementById('location_input');

            if (locationType === 'Off Site') {
                selectElement.classList.add('d-none');
                selectElement.disabled = true;
                inputElement.classList.remove('d-none');
                inputElement.disabled = false;
            } else {
                selectElement.classList.remove('d-none');
                selectElement.disabled = false;
                inputElement.classList.add('d-none');
                inputElement.disabled = true;
            }
        }

        // 2. Event Listeners untuk SweetAlert
        document.addEventListener('DOMContentLoaded', function() {

            // --- KONFIRMASI APPROVE DENGAN REMARKS ---
            const approveButtons = document.querySelectorAll('.btn-approve-swal');
            approveButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Setujui Laporan?',
                        text: "Tambahkan catatan jika diperlukan:",
                        icon: 'question',
                        input: 'textarea',
                        inputPlaceholder: 'Tuliskan catatan persetujuan di sini...',
                        inputAttributes: {
                            'aria-label': 'Tuliskan catatan persetujuan di sini'
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Setujui!',
                        customClass: {
                            popup: 'rounded-4'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Buat input hidden secara dinamis untuk mengirim remarks
                            const inputRemarks = document.createElement('input');
                            inputRemarks.type = 'hidden';
                            inputRemarks.name = 'remarks';
                            inputRemarks.value = result.value; // Nilai dari textarea SWAL
                            form.appendChild(inputRemarks);

                            form.submit();
                        }
                    });
                });
            });

            // --- KONFIRMASI REJECT DENGAN REMARKS (WAJIB ISI) ---
            const rejectButtons = document.querySelectorAll('.btn-reject-swal');
            rejectButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Tolak Laporan?',
                        text: "Berikan alasan penolakan (Wajib):",
                        icon: 'error',
                        input: 'textarea',
                        inputPlaceholder: 'Alasan penolakan...',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Anda harus menuliskan alasan penolakan!'
                            }
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Tolak!',
                        customClass: {
                            popup: 'rounded-4'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const inputRemarks = document.createElement('input');
                            inputRemarks.type = 'hidden';
                            inputRemarks.name = 'remarks';
                            inputRemarks.value = result.value;
                            form.appendChild(inputRemarks);

                            form.submit();
                        }
                    });
                });
            });

            // --- DELETE TETAP SEPERTI BIASA ---
            const deleteButtons = document.querySelectorAll('.btn-confirm-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Hapus Data?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        customClass: {
                            popup: 'rounded-4'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
</x-app-layout>
