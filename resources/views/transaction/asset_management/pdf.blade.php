<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Asset Management Report - {{ $data->code }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
            color: #2c3e50;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
            vertical-align: top;
        }

        .table th {
            background-color: #f8f9fa;
            width: 22%;
            font-weight: bold;
            color: #495057;
        }

        .section-title {
            background: #2c3e50;
            color: #ffffff;
            padding: 6px 10px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 0;
            border-radius: 3px 3px 0 0;
        }

        .spec-box {
            padding: 12px;
            border: 1px solid #dee2e6;
            background: #fff;
            min-height: 50px;
            margin-bottom: 20px;
        }

        .attachment-container {
            margin-top: 10px;
            width: 100%;
        }

        .attachment-item {
            display: inline-block;
            width: 30%;
            margin-right: 2%;
            margin-bottom: 15px;
            text-align: center;
            vertical-align: top;
        }

        .attachment-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .file-icon {
            height: 120px;
            background: #f1f1f1;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed #ccc;
            font-size: 10px;
            color: #666;
            padding-top: 50px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: right;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Asset Management Report</h2>
        <div style="margin-top: 5px;">Printed Date: {{ date('d F Y H:i') }}</div>
    </div>

    <div class="section-title">Primary Information</div>
    <table class="table">
        <tr>
            <th>Asset Code</th>
            <td>{{ $data->code }}</td>
            <th>Asset Name</th>
            <td>{{ $data->name }}</td>
        </tr>
        <tr>
            <th>Category</th>
            <td>{{ $data->category }}</td>
            <th>Asset Type</th>
            <td>{{ $data->type }}</td>
        </tr>
        <tr>
            <th>Register Date</th>
            <td>{{ \Carbon\Carbon::parse($data->register_date)->format('d-m-Y') }}</td>
            <th>Commissioning Date</th>
            <td>{{ $data->commisioning_date ? \Carbon\Carbon::parse($data->commisioning_date)->format('d-m-Y') : '-' }}
            </td>
        </tr>
    </table>

    <div class="section-title">Organizational & Ownership</div>
    <table class="table">
        <tr>
            <th>Department</th>
            <td>{{ $data->department->name ?? '-' }}</td>
            <th>Company</th>
            <td>{{ $data->company->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Ownership</th>
            <td>{{ $data->ownership }}</td>
            <th>Assembly Year</th>
            <td>{{ $data->assembly_year }}</td>
        </tr>
    </table>

    <div class="section-title">Specification Details</div>
    <div class="spec-box">
        {!! nl2br(e($data->specification)) ?: '<span style="color: #999;">No specification provided.</span>' !!}
    </div>

    <div class="section-title">Asset Attachments (Photos/Documents)</div>
    <div class="attachment-container">
        @forelse($data->attachments as $file)
            <div class="attachment-item">
                @php
                    $isImage = in_array(strtolower($file->file_type), ['jpg', 'jpeg', 'png', 'gif']);
                    $path = public_path('storage/' . $file->file_path);
                @endphp

                @if ($isImage && file_exists($path))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($path)) }}">
                @else
                    <div class="file-icon">
                        [ {{ strtoupper($file->file_type) }} DOCUMENT ]
                    </div>
                @endif
                <div style="margin-top: 5px; font-size: 9px; overflow: hidden; height: 12px;">
                    {{ $file->file_name }}
                </div>
            </div>
        @empty
            <div style="padding: 20px; color: #999; text-align: center;">No attachments available.</div>
        @endforelse
    </div>

    <div class="footer">
        Generated by System | Asset ID: {{ $data->id }}
    </div>
</body>

</html>
