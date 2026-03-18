<?php

namespace App\Models;

use App\Models\User;
use App\Models\MonthlyReport;
use Illuminate\Database\Eloquent\Model;

class MonthlyReportNextApprover extends Model
{
    protected $fillable = [
        'report_id',
        'user_id',
        'user_name',
    ];

    function report()
    {
        return $this->belongsTo(MonthlyReport::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }
}
