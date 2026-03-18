<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title d-flex align-items-center p-3">
                <a href="{{ route('dashboard') }}" class="btn fs-4 btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <h4 class="mb-0 fw-bold">Incident Latest Report</h1>
            </div>
            <div class="d-flex flex-wrap p-4">
                <button type="button" class="btn btn-search" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>

            {{-- modal search --}}
            <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="border-radius: 15px;">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="searchModalLabel fw-bold"><i
                                    class="fas fa-filter me-2"></i>Filter Data
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('report.incident_report') }}" method="GET">
                            <div class="modal-body">
                                <input type="hidden" name="type" value="{{ request('type') }}">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Request Date</label>
                                        <div class="input-group"">
                                            <input type="text" name="date_range" id="date_range"
                                                class="form-control rounded-pill" value="{{ $date_range }}" />

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Request No</label>
                                        <input type="text" name="no" class="form-control rounded-pill"
                                            placeholder="IN02-..." value="{{ request('no') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Status</label>
                                        <select name="status" class="form-select rounded-pill">
                                            <option value="">-- All Status --</option>
                                            @foreach ($list_status as $ls)
                                                <option value="{{ $ls }}">{{ $ls }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Requestor Name</label>
                                        <input type="text" name="reporter_name" class="form-control rounded-pill"
                                            placeholder="Name..." value="{{ request('reporter_name') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold">Company Name</label>
                                        <input type="text" name="company_name" class="form-control rounded-pill"
                                            placeholder="Company..." value="{{ request('company_name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <a href="{{ route('report.incident_report') }}"
                                    class="btn btn-light rounded-pill px-4">Reset</a>
                                <button type="submit" class="btn btn-search px-4">Apply Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 text-nowrap">
                            <thead>
                                <tr class="text-nowrap bg-light">
                                    {{-- <th>Action</th> --}}
                                    <th>ID</th>
                                    <th>No</th>
                                    <th>Report Date</th>
                                    <th>Event Title</th>
                                    <th>Date/Time</th>
                                    <th>Event Type</th>
                                    <th>Location</th>
                                    <th class="text-center">Status</th>
                                    <th>Reporter</th>
                                    <th>Remarks</th>
                                    <th>Next Action</th>
                                    <th>Next User</th>
                                    <th>Last Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr class="text-nowrap"">
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->no }}</td>
                                        <td>{{ $item->report_date }}</td>
                                        <td>{{ $item->event_title }}</td>
                                        <td>
                                            @php
                                                $rawDate = $item->event_dateTime ?? $item->event_datetime;
                                            @endphp
                                            @if (!empty($rawDate) && $rawDate !== '0000-00-00 00:00:00' && $rawDate !== '0000-00-00')
                                                {{ \Carbon\Carbon::parse($rawDate)->format('d-m-Y H:i') }}
                                            @else
                                                <span class="text-muted text-italic" style="font-size: 11px;">(No
                                                    Data)</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->event_type }}</td>
                                        <td>{{ $item->location }}</td>
                                        <td class="text-center">{{ $item->status }}</td>
                                        <td>{{ $item->reporter_name }}</td>
                                        <td>{{ $item->remarks }}</td>
                                        <td>{{ $item->next_action }}</td>
                                        <td>{{ $item->next_user_name }}</td>
                                        <td>{{ $item->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing <strong>{{ $data->firstItem() ?? 0 }}</strong> to
                            <strong>{{ $data->lastItem() ?? 0 }}</strong> of
                            <strong>{{ $data->total() }}</strong> entries
                        </div>

                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Tombol Previous --}}
                                @if ($data->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link shadow-none">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link shadow-none" href="{{ $data->previousPageUrl() }}"
                                            rel="prev">Previous</a>
                                    </li>
                                @endif

                                {{-- Tombol Angka --}}
                                @foreach ($data->getUrlRange(max(1, $data->currentPage() - 2), min($data->lastPage(), $data->currentPage() + 2)) as $page => $url)
                                    <li class="page-item {{ $page == $data->currentPage() ? 'active' : '' }}">
                                        <a class="page-link shadow-none"
                                            href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                {{-- Tombol Next --}}
                                @if ($data->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link shadow-none" href="{{ $data->nextPageUrl() }}"
                                            rel="next">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link shadow-none">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @push('styles')
            <link rel="stylesheet" type="text/css"
                href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
            <style>
                .card {
                    border-radius: 0.75rem;
                    overflow: hidden;
                }

                .table thead th {
                    font-size: 0.75rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }

                .table tbody td {
                    vertical-align: middle;
                    font-size: 0.85rem;
                    padding-top: 10px;
                    padding-bottom: 10px;
                }

                .badge {
                    font-weight: 500;
                }

                .daterangepicker td.available,
                .daterangepicker th.available {
                    color: #333 !important;
                }

                .daterangepicker td.off,
                .daterangepicker td.off.in-range,
                .daterangepicker td.off.start-date,
                .daterangepicker td.off.end-date {
                    color: #999 !important;
                }

                .daterangepicker {
                    z-index: 9999 !important;
                    border: 1px solid #ddd;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                }
            </style>
        @endpush
        @push('scripts')
            <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
            <script>
                $(document).ready(function() { // Pastikan DOM sudah siap
                    $('#date_range').daterangepicker({
                        autoUpdateInput: false,
                        alwaysShowCalendars: true,
                        opens: 'left',
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                                'month').endOf('month')]
                        },
                        locale: {
                            format: 'YYYY-MM-DD',
                            separator: " to ",
                            applyLabel: "Pilih",
                            cancelLabel: "Hapus"
                        }
                    });

                    // PERBAIKAN: Tambahkan tanda kutip pada selector #date_range
                    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                            'YYYY-MM-DD'));
                    });

                    // Opsional: Membersihkan input jika klik cancel
                    $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                    });
                });
            </script>
        @endpush
</x-app-layout>
