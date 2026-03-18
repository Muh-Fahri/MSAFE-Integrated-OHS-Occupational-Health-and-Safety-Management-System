<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyReportContractorLog extends Model
{
    use HasFactory;

    protected $table = 'monthly_report_contractor_logs';

    protected $fillable = [
        'report_id',
        'user_id',
        'user_name',
        'event',
        'status',
    ];

    /**
     * Relasi ke laporan utama.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(MonthlyReportContractor::class, 'report_id');
    }

    /**
     * Relasi ke user yang melakukan aksi (log).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
