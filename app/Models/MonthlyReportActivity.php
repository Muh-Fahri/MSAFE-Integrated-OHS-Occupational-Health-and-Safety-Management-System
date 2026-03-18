<?php

namespace App\Models;

use App\Models\MonthlyReport;
use Illuminate\Database\Eloquent\Model;

class MonthlyReportActivity extends Model
{
    protected $fillable = [
        'report_id',
        'activities',
        'pic',
        'status',
        'type'
    ];

    function report(){
        return $this->belongsTo(MonthlyReport::class);
    }
}
