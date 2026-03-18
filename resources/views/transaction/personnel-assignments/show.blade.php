<x-app-layout>
    <style>
        .document-wrapper {
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border: 1px solid #e1e1e1;
            color: #333;
            width: 100%;
            /* Full Width */
        }

        .doc-header {
            border-bottom: 3px double #333;
            margin-bottom: 25px;
            padding-bottom: 15px;
        }

        .table-info-head {
            margin-bottom: 30px;
        }

        .table-info-head td {
            padding: 8px 12px !important;
        }

        .table-pdf-style {
            border: 1px solid #333 !important;
        }

        .table-pdf-style thead th {
            background-color: #f8f9fa !important;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: bold;
            border: 1px solid #333 !important;
            padding: 12px;
        }

        .table-pdf-style tbody td {
            border: 1px solid #333 !important;
            padding: 10px;
            vertical-align: middle;
        }

        .label-col {
            font-weight: bold;
            width: 200px;
            background-color: #f9f9f9;
            border: 1px solid #dee2e6 !important;
        }

        .value-col {
            border: 1px solid #dee2e6 !important;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .document-wrapper {
                border: none;
                padding: 0;
            }
        }
    </style>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
            style="border-left: 5px solid #198754;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fa-lg"></i>
                <div>
                    <strong>Berhasil!</strong>
                    <p class="mb-0 small">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="document-wrapper">
        <div class="doc-header text-center">
            <h4 class="text-uppercase fw-bold mb-0">Personnel Assignment Document</h4>
        </div>

        <div class="table-info-head">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered sm">
                        <tr>
                            <td class="label-col">Company</td>
                            <td class="value-col">
                                <strong>{{ $assign->company_name }}</strong>
                                @if ($assign->sub_company_name)
                                    <br><small class="text-muted">{{ $assign->sub_company_name }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label-col">Request No</td>
                            <td class="value-col">{{ $assign->request_no }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Requestor</td>
                            <td class="value-col">{{ $assign->requestor_name }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered sm">
                        <tr>
                            <td class="label-col">Request Date</td>
                            <td class="value-col">{{ date('d F Y', strtotime($assign->request_date)) }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Current Status</td>
                            <td class="value-col">
                                {{ $assign->status }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-col">Last Update</td>
                            <td class="value-col">
                                {{ $assign->updated_at ? $assign->updated_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-pdf-style">
                <thead>
                    <tr class="text-center">
                        <th width="50">No</th>
                        <th>Employee</th>
                        <th>Position & Dept</th>
                        <th>Assignment Type</th>
                        <th>Field</th>
                        <th>Attachments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detail as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->employee_name }}</strong><br>
                                <small>ID: {{ $item->employee_id }}</small>
                            </td>
                            <td>
                                {{ $item->employee_title }}<br>
                                <span class="text-muted small">{{ $item->employee_department }}</span>
                            </td>
                            <td class="text-center">{{ $item->assignment_type }}</td>
                            <td class="text-center">{{ $item->assignment_field ?? '-' }}</td>

                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @php $hasAttachment = false; @endphp

                                    @for ($i = 1; $i <= 5; $i++)
                                        @php $column = "file_{$i}_path"; @endphp

                                        @if (!empty($item->$column))
                                            @php $hasAttachment = true; @endphp
                                            <a href="{{ route('storage.external', ['folder' => 'personnel-assignments', 'filename' => $item->$column]) }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2"
                                                style="font-size: 11px;">
                                                <i class="fas fa-paperclip me-1"></i> File {{ $i }}
                                            </a>
                                        @endif
                                    @endfor

                                    @if (!$hasAttachment)
                                        <span class="text-muted small">-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No personnel data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h5 class="mb-3 text-uppercase fw-bold" style="font-size: 14px;">
                    Approval Logs & History
                </h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" style="border: 1px solid #333 !important;">
                        <thead>
                            <tr class="text-center bg-light">
                                <th style="border: 1px solid #333 !important; width: 50px;">No</th>
                                <th style="border: 1px solid #333 !important; width: 180px;">Date & Time</th>
                                <th style="border: 1px solid #333 !important; width: 200px;">User</th>
                                <th style="border: 1px solid #333 !important; width: 150px;">Event / Action</th>
                                <th style="border: 1px solid #333 !important; width: 120px;">Status</th>
                                <th style="border: 1px solid #333 !important;">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Asumsi variabel $logs sudah dikirim dari Controller --}}
                            @forelse ($logs as $index => $log)
                                <tr>
                                    <td class="text-center" style="border: 1px solid #333 !important;">
                                        {{ $index + 1 }}</td>
                                    <td class="text-center" style="border: 1px solid #333 !important;">
                                        {{ $log->created_at ? $log->created_at->format('d M Y H:i:s') : '-' }}
                                    </td>
                                    <td style="border: 1px solid #333 !important;">
                                        <strong>{{ $log->user_name }}</strong><br>
                                        <small class="text-muted">ID: {{ $log->user_id }}</small>
                                    </td>
                                    <td class="text-center" style="border: 1px solid #333 !important;">
                                        {{ $log->event }}
                                    </td>
                                    <td class="text-center" style="border: 1px solid #333 !important;">
                                        {{ $log->status }}
                                    </td>
                                    <td style="border: 1px solid #333 !important;">
                                        <small>{{ $log->remarks ?? '-' }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted"
                                        style="border: 1px solid #333 !important;">
                                        No history logs available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <label for="remarks" class="fw-bold text-muted mb-2">Remarks</label>
                <textarea name="remarks" form="formApprove" class="form-control"
                    style="border: 1px solid #333 !important; border-radius: 10px; {{ $assign->next_user_id !== Auth::user()->id ? 'background-color: #e9ecef;' : '' }}"
                    id="remarks" rows="4"
                    placeholder="{{ $assign->next_user_id !== Auth::user()->id ? 'Anda tidak memiliki akses untuk memberikan catatan.' : 'Masukkan catatan di sini...' }}"
                    {{ $assign->next_user_id !== Auth::user()->id ? 'disabled' : '' }}>{{ old('remarks') }}</textarea>
            </div>
        </div>

        <div class="row mt-4 no-print">
            <div class="col-12">
                <div class="d-flex align-items-center w-100">

                    <div class="me-auto">
                        <a href="{{ route('transaction-personnel-assignments.index') }}"
                            class="btn btn-secondary rounded-pill px-4">
                            <i class="fas fa-times me-1"></i> Back
                        </a>
                    </div>

                    <div class="d-flex gap-2 align-items-center">
                        @if ($assign->requestor_id === Auth::user()->id)
                            <a href="{{ route('transaction-personnel-assignments.edit', $assign->id) }}"
                                class="btn btn-warning rounded-pill px-4">
                                <i class="fas fa-edit me-1"></i> Edit Data
                            </a>
                            <form action="{{ route('transaction-personnel-assignments.destroy', $assign->id) }}"
                                method="POST" class="m-0"> @csrf @method('DELETE')
                                <button class="btn btn-danger rounded-pill px-4"
                                    onclick="return confirm('Hapus data?')">Delete</button>
                            </form>
                        @endif

                        @if ($assign->next_user_id === Auth::user()->id)
                            <form id="formApprove"
                                action="{{ route('transaction-personnel-assignments.approve', $assign->id) }}"
                                method="POST" class="m-0">
                                @method('PUT')
                                @csrf
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success rounded-pill px-4" type="submit" name="action"
                                        value="approve">
                                        <i class="fas fa-check me-1"></i> Approve
                                    </button>
                                    <button class="btn btn-danger rounded-pill px-4" type="submit" name="action"
                                        value="reject">
                                        <i class="fas fa-ban me-1"></i> Reject
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
