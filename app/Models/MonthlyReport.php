<?php

namespace App\Models;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    protected $fillable = [
        'report_date',
        'report_no',
        'requestor_id',
        'requestor_name',
        'company_id',
        'company_name',
        'cover_image',
        'cover_text',
        'incident_total',
        'step_unachieved_dept',
        'next_month_strategy',
        'remarks',
        'last_action',
        'last_user_id',
        'last_user_name',
        'next_action',
        'next_user_id',
        'next_user_name',
        'status',
        'approval_level',
        'created_by',
        'updated_by'
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Tambahkan ini di App\Models\MonthlyReport.php

    public function activities()
    {
        // Ini menghubungkan Report ke tabel MonthlyActivity
        // Pastikan nama modelnya benar (misal: MonthlyActivity)
        return $this->hasMany(MonthlyReportActivity::class, 'report_id');
    }

    public function documentations()
    {
        // Ini menghubungkan Report ke tabel dokumentasi gambar
        return $this->hasMany(MonthlyReportDocumentation::class, 'report_id');
    }
}
