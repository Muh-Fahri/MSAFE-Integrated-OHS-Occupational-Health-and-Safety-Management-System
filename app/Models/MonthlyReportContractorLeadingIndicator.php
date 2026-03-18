<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyReportContractorLeadingIndicator extends Model
{
    use HasFactory;

    /**
     * Nama tabel sesuai dengan migration.
     */
    protected $table = 'monthly_report_contractor_leading_indicators';

    /**
     * Atribut yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'report_id',
        'activity',
        'jumlah_pelaksana',
        'remarks',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'jumlah_pelaksana' => 'integer',
    ];

    /**
     * Relasi balik ke laporan utama.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(MonthlyReportContractor::class, 'report_id');
    }
}
