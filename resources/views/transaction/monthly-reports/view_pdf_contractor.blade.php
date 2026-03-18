<!DOCTYPE html>
<html>

<head>
    <title>MSafe - Monthly Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: -1px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            word-wrap: break-word;
            vertical-align: middle;
        }

        /* Warna Header Oranye */
        .header-section {
            background-color: #D8964D;
            font-weight: bold;
            text-align: left;
        }

        .bg-gray {
            background-color: #f2f2f2;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        /* Ukuran Font Judul */
        .title-large {
            font-size: 14px;
            font-weight: bold;
        }

        .title-form {
            font-size: 11px;
            font-weight: bold;
        }

        /* Tinggi box foto */
        .photo-container {
            height: 180px;
            vertical-align: top;
            text-align: center;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td width="20%" class="center">
                <div style="margin-bottom: 5px;">
                    <img src="{{ public_path('logo/MDA.png') }}" style="height: 50px; width: auto;">
                </div>
                <strong style="font-size: 11pt;">PT MASMINDO</strong><br>
                <span style="font-size: 10pt;">DWI AREA</span>
            </td>
            <td width="60%" class="center">
                <div class="title-form">FORMULIR</div>
                <div class="title-large">LAPORAN KINERJA K3 BULANAN<br>KONTRAKTOR</div>
            </td>
            <td width="20%" style="vertical-align: top; font-size: 9pt;">
                Doc. No: {{ $report->report_no ?? '-' }}<br>
                Date: {{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    <table>
        <tr class="header-section">
            <td colspan="6">A. INFORMASI UMUM</td>
        </tr>
        <tr>
            <td width="18%" class="bg-gray">PERUSAHAAN</td>
            <td width="2%" class="center">:</td>
            <td width="30%">{{ $report->company_name }}</td>
            <td width="18%" class="bg-gray">NAMA PJO</td>
            <td width="2%" class="center">:</td>
            <td width="30%">{{ $report->operational_person_name }}</td>
        </tr>
        <tr>
            <td class="bg-gray">BIDANG USAHA</td>
            <td class="center">:</td>
            <td>{{ $report->business_field }}</td>
            <td class="bg-gray">BULAN / THN</td>
            <td class="center">:</td>
            <td>{{ $report->period_desc }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="18%" class="bg-gray">No Ijin</td>
            <td width="2%" class="center">:</td>
            <td width="25%">{{ $report->report_no }}</td>
            <td width="12%" class="bg-gray center">Tgl Terbit</td>
            <td width="13%">{{ \Carbon\Carbon::parse($report->issue_date)->format('d/m/Y') }}</td>
            <td width="15%" class="bg-gray center">Tgl Berakhir</td>
            <td width="15%">{{ \Carbon\Carbon::parse($report->expiry_date)->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table>
        <tr class="header-section">
            <td colspan="6">B. JUMLAH & JAM KERJA KARYAWAN</td>
        </tr>
        <tr class="bg-gray center">
            <td colspan="3">B1. Kontraktor</td>
            <td colspan="3">B2. Sub-Kontraktor</td>
        </tr>
        <tr class="bg-gray center" style="font-size: 9pt;">
            <td width="20%">Kategori</td>
            <td width="15%">Orang</td>
            <td width="15%">Jam</td>
            <td width="20%">Kategori</td>
            <td width="15%">Orang</td>
            <td width="15%">Jam</td>
        </tr>

        @php
            // Fungsi diperbarui:
            // Jika 0: sangat kecil (7pt)
            // Jika > 0: tampil normal apa adanya (mengikuti font induk)
            $formatZero = function ($value) {
                $val = $value ?? 0;
                if ($val == 0) {
                    return '<span style="font-size: 7pt; color: #888;">0</span>';
                }
                return $val;
            };
        @endphp

        <tr>
            <td>Operasional</td>
            <td class="center">{!! $formatZero($report->operational_employee_total) !!}</td>
            <td class="center">{!! $formatZero($report->operational_hours) !!}</td>
            <td>Operasional</td>
            <td class="center">{!! $formatZero($report->subcon_operational_employee_total) !!}</td>
            <td class="center">{!! $formatZero($report->subcon_operational_hours) !!}</td>
        </tr>
        <tr>
            <td>Administrasi</td>
            <td class="center">{!! $formatZero($report->administration_operational_total) !!}</td>
            <td class="center">{!! $formatZero($report->administration_hours) !!}</td>
            <td>Administrasi</td>
            <td class="center">{!! $formatZero($report->subcon_admin_total) !!}</td>
            <td class="center">{!! $formatZero($report->subcon_admin_hours) !!}</td>
        </tr>
        <tr>
            <td>Pengawas</td>
            <td class="center">{!! $formatZero($report->supervision_employee_total) !!}</td>
            <td class="center">{!! $formatZero($report->supervision_hours) !!}</td>
            <td>Pengawas</td>
            <td class="center">{!! $formatZero($report->subcon_supervision_total) !!}</td>
            <td class="center">{!! $formatZero($report->subcon_supervision_hours) !!}</td>
        </tr>
        <tr class="bg-gray center">
            <td>Total</td>
            @php
                $totalOrangB1 =
                    ($report->operational_employee_total ?? 0) +
                    ($report->administration_operational_total ?? 0) +
                    ($report->supervision_employee_total ?? 0);
                $totalJamB1 =
                    ($report->operational_hours ?? 0) +
                    ($report->administration_hours ?? 0) +
                    ($report->supervision_hours ?? 0);

                $totalOrangB2 =
                    ($report->subcon_operational_employee_total ?? 0) +
                    ($report->subcon_admin_total ?? 0) +
                    ($report->subcon_supervision_total ?? 0);
                $totalJamB2 =
                    ($report->subcon_operational_hours ?? 0) +
                    ($report->subcon_admin_hours ?? 0) +
                    ($report->subcon_supervision_hours ?? 0);
            @endphp
            <td>{!! $formatZero($totalOrangB1) !!}</td>
            <td>{!! $formatZero($totalJamB1) !!}</td>
            <td>Total</td>
            <td>{!! $formatZero($totalOrangB2) !!}</td>
            <td>{!! $formatZero($totalJamB2) !!}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="40%" class="bg-gray" style="text-align: right; padding-right: 10px;">Total Karyawan: </td>
            <td width="15%" class="center">
                @php $grandTotalOrang = $totalOrangB1 + $totalOrangB2; @endphp
                {!! $formatZero($grandTotalOrang) !!}
            </td>
            <td class="bg-gray" style="padding-left: 5px;">Orang</td>
        </tr>
        <tr>
            <td class="bg-gray" style="text-align: right; padding-right: 10px;">Total Jam Kerja: </td>
            <td class="center">
                @php $grandTotalJam = $totalJamB1 + $totalJamB2; @endphp
                {!! $formatZero($grandTotalJam) !!}
            </td>
            <td class="bg-gray" style="padding-left: 5px;">Jam</td>
        </tr>
    </table>

    <table>
        <tr class="bg-gray center">
            <td width="25%">Jumlah Tenaga Kerja</td>
            <td width="25%">Lokal</td>
            <td width="25%">Nasional</td>
            <td width="25%">Asing</td>
        </tr>
        <tr>
            <td class="center">{!! $formatZero($totalOrangB1 + $totalOrangB2) !!}</td>
            <td class="center">{!! $formatZero($report->local_employee_total) !!}</td>
            <td class="center">{!! $formatZero($report->national_employee_total) !!}</td>
            <td class="center">{!! $formatZero($report->foreign_employee_total) !!}</td>
        </tr>
        <tr>
            <td class="bg-gray">Working Hour Machine</td>
            <td class="center" colspan="3">{{ $report->machine_working_hours ?? 0 }} Hours</td>
        </tr>
    </table>

    <table>
        <tr class="header-section">
            <td colspan="4">C. KEGIATAN BERISIKO TINGGI</td>
        </tr>
        <tr class="bg-gray center">
            <td colspan="2">Bulan Lalu</td>
            <td colspan="2">Bulan Depan</td>
        </tr>
        <tr class="bg-gray center">
            <td width="5%">No</td>
            <td width="45%">Aktifitas</td>
            <td width="5%">No</td>
            <td width="45%">Aktifitas</td>
        </tr>

        @php
            $prevActivities = $report->activities->where('type', 'PREV')->values();
            $nextActivities = $report->activities->where('type', 'NEXT')->values();
            $maxRows = max($prevActivities->count(), $nextActivities->count());
            $maxRows = $maxRows > 0 ? $maxRows : 1;
        @endphp

        @for ($i = 0; $i < $maxRows; $i++)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>{{ $prevActivities[$i]->activity ?? '' }}</td>

                <td class="center">{{ $i + 1 }}</td>
                <td>{{ $nextActivities[$i]->activity ?? '' }}</td>
            </tr>
        @endfor
    </table>

    <table>
        <tr class="header-section">
            <td colspan="6">D. PENGELOLAAN BAHAN BERBAHAYA BERACUN (B3)</td>
        </tr>
        <tr class="bg-gray center">
            <td width="25%">Nama Bahan</td>
            <td width="15%">Sisa Bulan Lalu</td>
            <td width="15%">Penerimaan</td>
            <td width="15%">Pemakaian</td>
            <td width="15%">Sisa Akhir</td>
            <td width="15%">Satuan</td>
        </tr>

        @if ($report->materials->count() > 0)
            @foreach ($report->materials as $material)
                <tr>
                    <td>{{ $material->name }}</td>
                    <td class="center">{!! $formatZero($material->materials_qty) !!}</td>
                    <td class="center">{!! $formatZero($material->received_qty) !!}</td>
                    <td class="center">{!! $formatZero($material->used_qty) !!}</td>
                    <td class="center" style="font-weight: bold;">{!! $formatZero($material->remaining_qty) !!}</td>
                    <td class="center">{!! $formatZero($material->uom) !!}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="center">0</td>
                <td class="center">Kg/Lt</td>
            </tr>
        @endif
    </table>

    <table>
        <tr class="header-section">
            <td colspan="2">E. RINGKASAN KEGIATAN / INSIDEN BULAN INI</td>
        </tr>
        <tr class="bg-gray center">
            <td width="80%">Ringkasan Insiden</td>
            <td width="20%">Status</td>
        </tr>

        @if ($report->incidents->count() > 0)
            @foreach ($report->incidents as $item)
                <tr>
                    <td style="padding: 8px;">{{ $item->incident }}</td>
                    <td class="center">
                        <span style="font-weight: bold;">{{ strtoupper($item->status) }}</span>
                    </td>
                </tr>
            @endforeach
        @else
            <tr style="height: 40px;">
                <td class="center text-muted">Tidak ada insiden yang dilaporkan</td>
                <td class="center">-</td>
            </tr>
        @endif
    </table>

    <table>
        <tr class="header-section">
            <td colspan="4">F. INDIKATOR PROSES (LEADING INDICATOR)</td>
        </tr>
        <tr class="bg-gray center">
            <td width="5%">No</td>
            <td width="50%">Aktifitas</td>
            <td width="15%">Jumlah Pelaksanaan</td>
            <td width="30%">Keterangan</td>
        </tr>

        @php
            // Array ini harus SAMA PERSIS dengan yang ada di form create (Leading_activities)
            $leading_categories = [
                'Audit (SMKP, ISO Management system, etc.)',
                'Risk management (IBPR, JSA, JHA, etc.)',
                'Safety Talks / Toolbox Meeting',
                'Hazard Identification Report',
                'TAKE 5',
                'Planned Task Observation (PTO)',
                'Procedures Review (SOP, IK)',
                'OHS Initiatives',
                'OHS Promotion (Banner, Poster, Brochure, Competition, etc.)',
                'Reporting & Investigation of Near Miss',
                'Fatigue Check',
                'Alcohol Test',
                'Drug Test',
            ];
        @endphp

        @foreach ($leading_categories as $index => $catName)
            @php
                $indicator = $report->indicators
                    ->filter(function ($item) use ($catName) {
                        // Gunakan strtolower agar pencocokan tidak gagal karena huruf besar/kecil
                        return strtolower(trim($item->activity_name)) === strtolower(trim($catName));
                    })
                    ->first();
            @endphp
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $catName }}</td>
                <td class="center">
                    {{ $indicator ? $indicator->jumlah_pelaksana : 0 }}
                </td>
                <td style="font-size: 9pt;">
                    {{ $indicator ? $indicator->remarks : '-' }}
                </td>
            </tr>
        @endforeach
    </table>

    <table>
        <tr class="header-section">
            <td colspan="7">G. INDIKATOR HASIL (LAGGING INDICATOR)</td>
        </tr>
        <tr class="bg-gray center">
            <td width="5%">No</td>
            <td width="35%">Kategori</td>
            <td width="10%">Target</td>
            <td width="10%">Aktual</td>
            <td width="10%">FR</td>
            <td width="10%">Hari Hilang</td>
            <td width="10%">SR</td>
        </tr>

        @php
            $lagging_categories = [
                'NR (Non Recordable)',
                'NM (Near Miss)',
                'PD (Property Damage)',
                'OI (Occupational Ilness)',
                'FA (First Aid)',
                'MTC (Medical Treatment Case)',
                'RWC (Restricted Work Case)',
                'LTI (Lost Time Injury Case)',
                'Fatality',
                'Lost Work Days RWC',
                'Lost Work Days LTI',
            ];
            $total_hours =
                ($report->operational_hours ?? 0) +
                ($report->administration_hours ?? 0) +
                ($report->supervision_hours ?? 0) +
                ($report->subcon_operational_hours ?? 0) +
                ($report->subcon_admin_hours ?? 0) +
                ($report->subcon_supervision_hours ?? 0);
        @endphp

        @foreach ($lagging_categories as $index => $catName)
            @php
                $metric = $report->safetyMetrics->where('category', $catName)->first();
                $actual = $metric->actual ?? 0;
                $lost_days = $metric->lost_days ?? 0;

                $fr = $total_hours > 0 ? ($actual * 1000000) / $total_hours : 0;
                $sr = $total_hours > 0 ? ($lost_days * 1000000) / $total_hours : 0;
            @endphp
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td style="padding-left: 5px;">{{ $catName }}</td>
                <td class="center">{{ $metric->target ?? 0 }}</td>
                <td class="center">{{ $actual }}</td>
                <td class="center">
                    {{ str_contains($catName, 'Lost Work') ? '-' : number_format($fr, 2) }}
                </td>
                <td class="center">{{ $lost_days }}</td>
                <td class="center">
                    {{ str_contains($catName, 'Lost Work') ? '-' : number_format($sr, 2) }}
                </td>
            </tr>
        @endforeach
    </table>
    <table style="page-break-before: auto; width: 100%;">
        <tr class="header-section">
            <td>H. DOKUMENTASI</td>
        </tr>
        <tr>
            <td style="padding: 0; border: none;">
                <table style="width: 100%; border: none; border-collapse: collapse;">
                    @if ($report->documentations->count() > 0)
                        {{-- chunk(3) untuk membuat grid 3 kolom --}}
                        @foreach ($report->documentations->chunk(3) as $chunk)
                            <tr>
                                @foreach ($chunk as $doc)
                                    <td width="33.33%"
                                        style="padding: 10px; border: 1px solid #dee2e6; vertical-align: top;">
                                        <div style="text-align: center; margin-bottom: 5px;">
                                            @if ($doc->image)
                                                @php
                                                    $basePath = rtrim(env('FILE_PATH'), '/');
                                                    $imagePath = $basePath . '/' . $doc->image;
                                                    $base64 = '';
                                                    if (file_exists($imagePath)) {
                                                        $type = pathinfo($imagePath, PATHINFO_EXTENSION);
                                                        $data = file_get_contents($imagePath);
                                                        $base64 =
                                                            'data:image/' . $type . ';base64,' . base64_encode($data);
                                                    }
                                                @endphp
                                                @if ($base64)
                                                    <img src="{{ $base64 }}"
                                                        style="width: 100%; height: 160px; object-fit: cover; border-radius: 4px;">
                                                @else
                                                    <div style="height: 160px; background: #eee; line-height: 160px;">
                                                        File Not Found</div>
                                                @endif
                                            @else
                                                <div style="height: 160px; background: #eee; line-height: 160px;">No
                                                    Image</div>
                                            @endif
                                        </div>
                                        <div
                                            style="font-size: 8pt; background-color: #f8f9fa; padding: 6px; border: 1px solid #eee; min-height: 35px;">
                                            <strong>Keterangan:</strong><br>
                                            {{-- Sesuaikan dengan nama kolom 'remarks' di controller --}}
                                            {{ $doc->remarks ?? '-' }}
                                        </div>
                                    </td>
                                @endforeach

                                {{-- Penyeimbang jika baris terakhir kurang dari 3 foto --}}
                                @for ($i = $chunk->count(); $i < 3; $i++)
                                    <td width="33.33%" style="border: none;"></td>
                                @endfor
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="center" style="padding: 30px; color: #666;">
                                <i>Tidak ada lampiran foto kegiatan untuk bulan ini.</i>
                            </td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>


</body>

</html>
