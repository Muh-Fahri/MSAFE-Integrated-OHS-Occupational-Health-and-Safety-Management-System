<?php

namespace App\Models;

use App\Models\MonthlyReportActivity;
use Illuminate\Database\Eloquent\Model;
use App\Models\MonthlyReportContractorIncident;
use App\Models\MonthlyReportContractorMaterial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyReportContractor extends Model
{
    use HasFactory;

    protected $table = 'monthly_report_contractors';

    protected $fillable = [
        // A. Informasi Umum
        'report_no',
        'report_date',
        'company_id',
        'company_name',
        'business_field',
        'operational_person_name',
        'issue_date',
        'expiry_date',

        // B1. Tenaga Kerja & Jam Kerja (Kontraktor)
        'operational_employee_total',
        'administration_operational_total',
        'supervision_employee_total',
        'operational_hours',
        'administration_hours',
        'supervision_hours',

        // B2. Tenaga Kerja & Jam Kerja (Sub-Kontraktor)
        'subcon_operational_employee_total',
        'subcon_operational_hours',
        'subcon_admin_total',
        'subcon_admin_hours',
        'subcon_supervision_total',
        'subcon_supervision_hours',

        // Detail Lokasi Tenaga Kerja & Alat
        'local_employee_total',
        'national_employee_total',
        'foreign_employee_total',
        'machine_working_hours',

        // approval
        'approval_level',
        'last_approval_level',
        'last_user_id',
        'last_user_name',
        'next_user_id',
        'next_user_name',
        'action',
        'last_action',
        'status',
        'requestor_id',
        'requestor_name',
        'remarks',
    ];

    protected $casts = [
        'report_date' => 'date',
        'issue_date'  => 'date',
        'expiry_date' => 'date',
        'operational_hours'          => 'double',
        'administration_hours'       => 'double',
        'supervision_hours'          => 'double',
        'subcon_operational_hours'   => 'double',
        'subcon_admin_hours'         => 'double',
        'subcon_supervision_hours'   => 'double',
        'machine_working_hours'      => 'double',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function activities()
    {
        // Gunakan hasMany karena satu laporan punya banyak aktivitas
        return $this->hasMany(MonthlyReportContractorActivity::class, 'report_id');
    }

    public function materials()
    {
        return $this->hasMany(MonthlyReportContractorMaterial::class, 'report_id');
    }

    public function incidents()
    {
        return $this->hasMany(MonthlyReportContractorIncident::class, 'report_id');
    }

        public function indicators()
        {
            return $this->hasMany(MonthlyReportContractorLeadingIndicator::class, 'report_id');
        }

    public function safetyMetrics()
    {
        return $this->hasMany(MonthlyReportContractorLaggingIndicator::class, 'report_id');
    }

    public function documentations()
    {
        return $this->hasMany(MonthlyReportContractorImage::class, 'report_id');
    }
}
