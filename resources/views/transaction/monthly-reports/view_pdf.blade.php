<!DOCTYPE html>
<html>

<head>
    <title>MSafe - Monthly Report</title>
    <style>
        .page-break {
            page-break-after: always;

        }

        .doc-grid {
            width: 100%;
            margin-top: 20px;
        }

        .doc-item {
            width: 31%;
            /* 3 kolom */
            margin: 1%;
            float: left;
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }

        .doc-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .doc-remarks {
            font-size: 10px;
            margin-top: 5px;
            color: #555;
        }

        /* Untuk membersihkan float setelah grid */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>
    <div class="page-break">
        <center>
            <h1>MONTHLY REPORT<br /> OCCUPATIONAL HEALTH & SAFETY <br />{{ $report->period_desc }}</h1>
            <img src="{{ asset('storage/' . $report->image) }}"
                style="width: 100%; height: 500px; object-fit: cover; display: block;" alt="Cover Image">


            <h3>{{ $report->cover_text }}</h3>
        </center>
        <p></p>
    </div>

    <div class="page-break">
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="margin-bottom: 5px;">TABLE OF CONTENTS</h2>
            <hr style="width: 200px; border: 1px solid #000;">
        </div>fdgs
        <table style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 13px;">
            <tbody>
                <tr style="height: 35px;">
                    <td style="width: 90%; font-weight: bold;">SECTION A: GENERAL INFORMATION</td>
                    <td style="width: 10%; text-align: right; font-weight: bold;">01</td>
                </tr>

                <tr style="height: 35px;">
                    <td style="font-weight: bold;">SECTION B: MANPOWER AND WORKING HOURS</td>
                    <td style="text-align: right; font-weight: bold;">02</td>
                </tr>
                <tr style="height: 25px;">
                    <td style="padding-left: 20px;">B.1 Contractor Statistics</td>
                    <td style="text-align: right;">...</td>
                </tr>
                <tr style="height: 25px;">
                    <td style="padding-left: 20px;">B.2 Sub-Contractor Statistics</td>
                    <td style="text-align: right;">...</td>
                </tr>
                <tr style="height: 25px;">
                    <td style="padding-left: 20px;">B.3 Workforce Origin & Machine Hours</td>
                    <td style="text-align: right;">...</td>
                </tr>

                <tr style="height: 35px;">
                    <td style="font-weight: bold;">SECTION C: SAFETY PERFORMANCE (LAGGING INDICATORS)</td>
                    <td style="text-align: right; font-weight: bold;">03</td>
                </tr>
                <tr style="height: 25px;">
                    <td style="padding-left: 20px;">- Frequency Rate (FR) & Severity Rate (SR)</td>
                    <td style="text-align: right;">...</td>
                </tr>

                <tr style="height: 35px;">
                    <td style="font-weight: bold;">SECTION D: LEADING INDICATORS & ACTIVITIES</td>
                    <td style="text-align: right; font-weight: bold;">04</td>
                </tr>
                <tr style="height: 25px;">
                    <td style="padding-left: 20px;">- Safety Activities (Relasi: activities)</td>
                    <td style="text-align: right;">...</td>
                </tr>

                <tr style="height: 35px;">
                    <td style="font-weight: bold;">SECTION E: INCIDENT & ACCIDENT RECORDS</td>
                    <td style="text-align: right; font-weight: bold;">05</td>
                </tr>

                <tr style="height: 35px;">
                    <td style="font-weight: bold;">SECTION F: MATERIAL & EQUIPMENT USAGE</td>
                    <td style="text-align: right; font-weight: bold;">06</td>
                </tr>

                <tr style="height: 35px;">
                    <td style="font-weight: bold;">SECTION G: DOCUMENTATION & SITE PHOTOS</td>
                    <td style="text-align: right; font-weight: bold;">07</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <h2>A. EXECUTIVE SUMMARY</h2>
        <p>During my notice period, I am committed to ensuring a seamless transition. I am more than willing to assist
            in the transfer of my responsibilities, provide training to my successor, and complete any pending projects.
            Please let me know how I can best contribute to this process.</p>
    </div>

    <div>
        <h2>B. HIGHLIGHT</h2>
        <p>During my notice period, I am committed to ensuring a seamless transition. I am more than willing to assist
            in the transfer of my responsibilities, provide training to my successor, and complete any pending projects.
            Please let me know how I can best contribute to this process.</p>
    </div>

    <div>
        <h2>C. WORK PROGRESS</h2>
        <p>During my notice period, I am committed to ensuring a seamless transition. I am more than willing to assist
            in the transfer of my responsibilities, provide training to my successor, and complete any pending projects.
            Please let me know how I can best contribute to this process.</p>
    </div>

    <div>
        <h2>D. OCCUPATIONAL HEALTH & SAFETY DASHBOARD</h2>
        <p>During my notice period, I am committed to ensuring a seamless transition. I am more than willing to assist
            in the transfer of my responsibilities, provide training to my successor, and complete any pending projects.
            Please let me know how I can best contribute to this process.</p>
    </div>

    <div>
        <h2>E. OPERATIONAL SAFETY</h2>
        <p>During my notice period, I am committed to ensuring a seamless transition. I am more than willing to assist
            in the transfer of my responsibilities, provide training to my successor, and complete any pending projects.
            Please let me know how I can best contribute to this process.</p>
    </div>

    <div>
        <h2>F. DOCUMENTATION</h2>
        <div class="doc-grid clearfix">
            @foreach ($report->documentations as $doc)
                <div class="doc-item">
                    @php
                        $path = public_path('storage/' . $doc->image);
                    @endphp

                    @if (file_exists($path))
                        <img src="{{ $path }}" class="doc-image">
                    @else
                        <div style="height: 150px; background: #eee; padding-top: 60px;">No Image</div>
                    @endif

                    <div class="doc-remarks">
                        {{ $doc->remarks ?? 'file' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>
