<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MonthlyReportContractorImage extends Model
{
    use HasFactory;

    protected $table = 'monthly_report_contractor_images';

    protected $fillable = [
        'report_id',
        'image',
        'remarks',
    ];

    /**
     * Relasi balik ke laporan utama.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(MonthlyReportContractor::class, 'report_id');
    }

    /**
     * Accessor untuk mendapatkan URL penuh gambar (opsional).
     * Memudahkan saat ingin menampilkan foto di frontend.
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
