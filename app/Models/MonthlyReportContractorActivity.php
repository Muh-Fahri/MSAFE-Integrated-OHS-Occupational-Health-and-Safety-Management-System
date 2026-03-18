<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyReportContractorActivity extends Model
{
    use HasFactory;

    /**
     * Nama tabel (opsional, Laravel sudah otomatis mendeteksi bentuk jamak).
     */
    protected $table = 'monthly_report_contractor_activities';

    /**
     * Kolom yang dapat diisi mass-assignment.
     */
    protected $fillable = [
        'report_id',
        'activity',
        'type',
    ];

    /**
     * Relasi balik ke laporan utama (MonthlyReportContractor).
     * Activity ini dimiliki oleh satu laporan bulanan.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(MonthlyReportContractor::class, 'report_id');
    }
}
