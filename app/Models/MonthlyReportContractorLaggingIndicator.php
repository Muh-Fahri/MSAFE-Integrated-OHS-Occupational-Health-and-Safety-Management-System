<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyReportContractorLaggingIndicator extends Model
{
    use HasFactory;

    protected $table = 'monthly_report_contractor_lagging_indicators';

    protected $fillable = [
        'report_id',
        'category',
        'target',
        'actual',
        'fr',
        'loss_day',
        'sr',
    ];

    /**
     * Relasi balik ke laporan utama.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(MonthlyReportContractor::class, 'report_id');
    }
}
