<?php

namespace App\Models;

use App\Models\MonthlyReportContractor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyReportContractorIncident extends Model
{
    use HasFactory;

    /**
     * Nama tabel.
     */
    protected $table = 'monthly_report_contractor_incidents';

    /**
     * Atribut yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'report_id',
        'incident',
        'status',
    ];

    /**
     * Relasi balik ke laporan utama.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(MonthlyReportContractor::class, 'report_id');
    }
}
