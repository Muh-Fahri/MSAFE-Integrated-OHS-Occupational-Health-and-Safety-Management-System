<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyReportDocumentation extends Model
{
    protected $fillable = [
        'report_id',
        'image',
        'remarks'
    ];
}
