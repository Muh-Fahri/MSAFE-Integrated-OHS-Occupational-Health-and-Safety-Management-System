<x-app-layout>
    <div class="container-fluid">
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h4 class="mb-0">Dashboard</h4>
                <p class="text-muted fw-medium">{{ $full_date }}</p>
            </div>
            <div class="col-md-6">
                <form action="" method="GET" class="d-flex justify-content-md-end">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" name="date_range" id="date_range"
                            class="form-control form-control-sm border-secondary" value="{{ $date_range }}" />
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">Monthly Incident & Hazard Trends</h5>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height:350px;">
                            <canvas id="barChartTrends"></canvas>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <div class="me-4"><span class="badge" style="background-color: #FACA05;">&nbsp;</span>
                                Incident</div>
                            <div><span class="badge" style="background-color: #F2310B;">&nbsp;</span> Hazard</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="row g-2 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center" style="background: #FACA05;">
                            <div class="card-body py-3 px-1"> <small class="text-white d-block opacity-75 text-nowrap"
                                    style="font-size: 11px;">Incident Notif</small>
                                <h3 class="text-white mb-0 fw-bold">{{ $incident_notif_count }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center" style="background: #E89307;">
                            <div class="card-body py-3 px-1">
                                <small class="text-white d-block opacity-75 text-nowrap"
                                    style="font-size: 11px;">Investigation</small>
                                <h3 class="text-white mb-0 fw-bold">{{ $incident_inv_count }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center" style="background: #F2310B;">
                            <div class="card-body py-3 px-1">
                                <small class="text-white d-block opacity-75 text-nowrap" style="font-size: 11px;">Hazard
                                    Report</small>
                                <h3 class="text-white mb-0 fw-bold">{{ $hazard_count }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center" style="background: #5D9205;">
                            <div class="card-body py-3 px-1">
                                <small class="text-white d-block opacity-75 text-nowrap"
                                    style="font-size: 11px;">Corrective Action</small>
                                <h3 class="text-white mb-0 fw-bold">{{ $corrective_action_count }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success d-flex align-items-center justify-content-between">
                        <h6 class="card-title text-white mb-0">Corrective Action Summary</h6>
                        <span class="badge bg-white text-success">Real-time</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Source</th>
                                        <th class="text-center">Open</th>
                                        <th class="text-center">Complete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_summary_CAR as $v)
                                        <tr>
                                            <td class="fw-medium">{{ $v->source_name }}</td>
                                            <td class="text-center text-danger fw-bold">{{ $v->open }}</td>
                                            <td class="text-center text-success fw-bold">{{ $v->complete }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header border-bottom d-flex align-items-center justify-content-between"
                        style="background: #bf9000;">
                        <h6 class="card-title text-white mb-0">10 Latest Incidents</h6>
                        <a href="{{ route('report.incident_report') }}"
                            class="btn btn-sm btn-light">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Date</th>
                                        <th>No</th>
                                        <th>Description</th>
                                        <th>Dept</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_incident as $v)
                                        <tr>
                                            <td class="ps-3 text-nowrap">{{ $v->report_date }}</td>
                                            <td class="text-nowrap fw-bold text-danger">{{ $v->no }}</td>
                                            <td>{{ Str::limit($v->event_title, 30) }}</td>
                                            <td>{{ $v->department_name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header border-bottom d-flex align-items-center justify-content-between"
                        style="background: #c55a11;">
                        <h6 class="card-title text-white mb-0">10 Latest Hazards</h6>
                        <a href="{{ route('report.hazard_report') }}"
                            class="btn btn-sm btn-light">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Date</th>
                                        <th>No</th>
                                        <th>Description</th>
                                        <th>Dept</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_hazard as $v)
                                        <tr>
                                            <td class="ps-3 text-nowrap small">{{ $v->report_datetime }}</td>
                                            <td class="text-nowrap fw-bold text-warning">{{ $v->no }}</td>
                                            <td>{{ Str::limit($v->hazard_description, 30) }}</td>
                                            <td>{{ $v->reporter_department_name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
        <script src="{{ asset('Minible/HTML/dist/assets/libs/chart.js/chart.umd.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Data dari Controller
                var incidentRaw = {!! $list_incident_grouping !!};
                var hazardRaw = {!! $list_hazard_grouping !!};
                var allLabels = Array.from(new Set([...incidentRaw.label, ...hazardRaw.label]));
                var incidentDataFormatted = allLabels.map(label => {
                    var index = incidentRaw.label.indexOf(label);
                    return index !== -1 ? incidentRaw.data[index] : 0;
                });

                var hazardDataFormatted = allLabels.map(label => {
                    var index = hazardRaw.label.indexOf(label);
                    return index !== -1 ? hazardRaw.data[index] : 0;
                });

                const ctx = document.getElementById("barChartTrends");
                new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: allLabels,
                        datasets: [{
                                label: "Incidents",
                                backgroundColor: "#FACA05",
                                borderRadius: 5,
                                data: incidentDataFormatted,
                                barPercentage: 0.7,
                                categoryPercentage: 0.6
                            },
                            {
                                label: "Hazards",
                                backgroundColor: "#F2310B",
                                borderRadius: 5,
                                data: hazardDataFormatted,
                                barPercentage: 0.7,
                                categoryPercentage: 0.6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            } // Kita pakai legend manual di HTML agar lebih rapi
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            });
            $('#date_range').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                        'month')]
                },
                alwaysShowCalendars: true,
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: " to "
                }
            });
        </script>
    @endpush
</x-app-layout>
