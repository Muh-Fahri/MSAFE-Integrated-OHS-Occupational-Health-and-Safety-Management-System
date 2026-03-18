<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class MonthlyContractorExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $reports;

    // Kita terima data reports lewat constructor agar dinamis (bisa difilter)
    public function __construct($reports)
    {
        $this->reports = $reports;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->reports;
    }

    public function headings(): array
    {
        return [
            'ID',
            'REPORT NO',
            'COMPANY',
            'BUSINESS FIELD',
            'REMARKS',
            'STATUS',
            'NEXT USER',
            'NEXT ACTION',
            'REPORT DATE',
            'LAST UPDATED'
        ];
    }

    public function map($report): array
    {
        return [
            $report->id,
            $report->report_no,
            $report->company_name,
            $report->business_field,
            $report->remarks,
            $report->status,
            $report->next_user_name,
            $report->action,
            $report->report_date ? $report->report_date->format('M Y') : '-',
            $report->updated_at ? $report->updated_at->format('d M Y, H:i') : '-',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '4F81BD']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],

            // Tambahkan border ke seluruh data
            'A1:J' . ($this->reports->count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
