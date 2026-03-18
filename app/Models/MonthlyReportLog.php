<?php

namespace App\Models;

use App\Models\User;
use App\Models\MonthlyReport;
use Illuminate\Database\Eloquent\Model;

class MonthlyReportLog extends Model
{
    protected $fillable = [
        'report_id',
        'user_id',
        'user_name',
        'status',
        'remarks',
        'delegator_uid',
        'event'
    ];

    function user(){
        return $this->belongsTo(User::class);
    }

    function report() {
        return $this->belongTo(MonthlyReport::class);
    }
}
