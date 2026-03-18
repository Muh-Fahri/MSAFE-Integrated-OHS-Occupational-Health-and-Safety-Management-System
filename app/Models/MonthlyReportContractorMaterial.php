<?php

namespace App\Models;

use App\Models\MonthlyReportContractor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyReportContractorMaterial extends Model
{
    use HasFactory;

    protected $table = 'monthly_report_contractor_materials';

    protected $fillable = [
        'report_id',
        'materials_qty',
        'remaining_qty',
        'received_qty',
        'used_qty',
        'uom',
        'name',
    ];

    /**
     * Casting ke double/float agar perhitungan presisi saat digunakan di PHP.
     */
    protected $casts = [
        'materials_qty' => 'double',
        'remaining_qty' => 'double',
        'received_qty' => 'double',
        'used_qty' => 'double',
    ];

    /**
     * Relasi ke laporan utama.
     */
    public function report(): BelongsTo
    {
        // Sesuaikan nama tabel target jika di migration ada typo 'month' vs 'monthly'
        return $this->belongsTo(MonthlyReportContractor::class, 'report_id');
    }
}
