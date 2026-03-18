    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Hazard Report - {{ $hazard->no }}</title>
        <style>
            body {
                font-family: 'Helvetica', 'Arial', sans-serif;
                font-size: 12px;
                color: #333;
                line-height: 1.6;
                margin: 0;
                padding: 0;
            }

            .header {
                text-align: center;
                padding: 20px;
                background-color: #f4f4f4;
                border-bottom: 3px solid #6c757d;
                margin-bottom: 30px;
            }

            .header h2 {
                margin: 0;
                text-transform: uppercase;
                color: #495057;
                letter-spacing: 2px;
            }

            .content {
                padding: 0 30px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            tr:nth-child(even) {
                background-color: #f8f9fa;
            }

            td {
                padding: 12px 10px;
                border-bottom: 1px solid #dee2e6;
                vertical-align: top;
            }

            .label {
                font-weight: bold;
                width: 30%;
                color: #495057;
                background-color: #e9ecef;
            }

            .status-badge {
                padding: 4px 8px;
                background-color: #6c757d;
                color: white;
                border-radius: 4px;
                font-size: 10px;
                text-transform: uppercase;
            }

            .attachment-section {
                margin-top: 20px;
            }

            .attachment-title {
                font-weight: bold;
                border-bottom: 1px solid #6c757d;
                padding-bottom: 5px;
                margin-bottom: 10px;
                color: #495057;
            }

            .image-grid {
                width: 100%;
            }

            .image-item {
                display: inline-block;
                width: 45%;
                margin-right: 2%;
                margin-bottom: 15px;
                text-align: center;
                vertical-align: top;
            }

            .hazard-img {
                width: 100%;
                max-height: 200px;
                object-fit: contain;
                border: 1px solid #dee2e6;
                border-radius: 4px;
            }

            .file-link {
                display: block;
                margin-top: 5px;
                color: #007bff;
                text-decoration: none;
                font-size: 10px;
            }

            .footer {
                position: fixed;
                bottom: 30px;
                width: 100%;
                text-align: center;
                font-size: 10px;
                color: #adb5bd;
                border-top: 1px solid #e9ecef;
                padding-top: 10px;
            }
        </style>
    </head>

    <body>
        <div class="header">
            <h2>Hazard Report Detail</h2>
            <p>Document No: <strong>{{ $hazard->no }}</strong></p>
        </div>

        <div class="content">
            <table>
                <tr>
                    <td class="label">Reporter Name</td>
                    <td>{{ $hazard->reporter->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Assign To</td>
                    <td>{{ $hazard->assignee_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Date & Time</td>
                    <td>{{ \Carbon\Carbon::parse($hazard->report_date_time)->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="label">Hazard Source</td>
                    <td>{{ $hazard->hazard_source ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Location</td>
                    <td>{{ $hazard->location ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Hazard Description</td>
                    <td>{{ $hazard->hazard_description }}</td>
                </tr>
                <tr>
                    <td class="label">Immediate Actions</td>
                    <td>{{ $hazard->immediate_actions }}</td>
                </tr>

                {{-- Tampilkan Corrective Action jika ada --}}
                @if ($hazard->corrective_action)
                    <tr>
                        <td class="label">Corrective Actions</td>
                        <td style="color: #2c3e50; font-weight: bold;">{{ $hazard->corrective_action }}</td>
                    </tr>
                @endif

                {{-- Tampilkan Due Date jika ada --}}
                @if ($hazard->due_date)
                    <tr>
                        <td class="label">Due Date</td>
                        <td>{{ \Carbon\Carbon::parse($hazard->due_date)->format('d M Y') }}</td>
                    </tr>
                @endif

                {{-- Tampilkan Completed Date jika ada --}}
                @if ($hazard->completed_date)
                    <tr>
                        <td class="label">Completed Date</td>
                        <td style="color: #28a745; font-weight: bold;">
                            {{ \Carbon\Carbon::parse($hazard->completed_date)->format('d M Y') }}</td>
                    </tr>
                @endif

                <tr>
                    <td class="label">Current Status</td>
                    <td>
                        @php
                            $statusColor = '#6c757d'; // default gray
                            if ($hazard->status == 'COMPLETED') {
                                $statusColor = '#28a745';
                            }
                            if ($hazard->status == 'ACTION_REQUIRED') {
                                $statusColor = '#dc3545';
                            }
                        @endphp
                        <span class="status-badge" style="background-color: {{ $statusColor }};">
                            {{ str_replace('_', ' ', $hazard->status) }}
                        </span>
                    </td>
                </tr>
            </table>

            {{-- Cek apakah ada setidaknya satu file sebelum menampilkan section --}}
            @if ($hazard->file_1_path || $hazard->file_2_path || $hazard->file_3_path)
                <div class="attachment-section">
                    <div class="attachment-title">Attachments / Documentation</div>
                    <div class="image-grid">
                        @for ($i = 1; $i <= 3; $i++)
                            @php
                                $pathField = "file_{$i}_path";
                                $filePath = $hazard->$pathField;
                            @endphp

                            @if ($filePath)
                                <div class="image-item">
                                    @php
                                        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                        $fullPath = public_path('storage/' . $filePath);
                                    @endphp

                                    @if ($isImage && file_exists($fullPath))
                                        <img src="data:image/{{ $extension }};base64,{{ base64_encode(file_get_contents($fullPath)) }}"
                                            class="hazard-img">
                                    @else
                                        <div
                                            style="padding: 20px; border: 1px dashed #ccc; background: #f9f9f9; min-height: 100px;">
                                            <span style="font-size: 10px;">[File: {{ strtoupper($extension) }}]</span>
                                        </div>
                                    @endif
                                    <span class="file-link">{{ basename($filePath) }}</span>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            @endif {{-- Tag penutup IF yang benar --}}
        </div>

        <div class="footer">
            Printed on: {{ date('d/m/Y H:i:s') }} | Hazard Management System
        </div>
    </body>

    </html>
