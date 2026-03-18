<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class License extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'no',
        'date',
        'employee_id',
        'name',
        'position',
        'department_id',
        'department_name',
        'company_id',
        'company_name',
        'requestor_id',
        'requestor_name',
        'requestor_department_id',
        'requestor_department_name',
        'type',
        'reason',
        'theory_tester_id',
        'theory_tester_name',
        'practice_tester_id',
        'practice_tester_name',
        'first_aid_trainer_id',
        'first_aid_trainer_name',
        'ddc_trainer_id',
        'ddc_trainer_name',
        'remarks',
        'driving_license_expiry_date',
        'last_action',
        'last_user_id',
        'last_user_name',
        'last_approval_level',
        'next_user_id',
        'next_action',
        'next_user_name',
        'status',
        'approval_level',
        'license_status',
        'expire_date'
    ];

    function department()
    {
        return $this->belongsTo(Department::class);
    }

    function first_aid()
    {
        return $this->belongsTo(User::class);
    }

    function company()
    {
        return $this->belongsTo(Company::class);
    }

    function requestor()
    {
        return $this->belongsTo(User::class);
    }

    function theoryTester()
    {
        return $this->belongsTo(User::class);
    }

    function practiceTester()
    {
        return $this->belongsTo(User::class);
    }

    function ddcTrainer()
    {
        return $this->belongsTo(User::class);
    }

    function lastUser()
    {
        return $this->belongsTo(User::class);
    }

    function next_user()
    {
        return $this->belongsTo(User::class);
    }

    public function licenseItems()
    {
        // Tambahkan 'license_id' agar Laravel tidak mencari 'licenses_id'
        return $this->hasMany(LicenseItem::class, 'license_id');
    }

    public function licenseZones()
    {
        return $this->hasMany(LicenseZone::class, 'license_id');
    }

    public function attachments()
    {
        return $this->hasMany(LicenseAttachment::class, 'license_id');
    }

    public function logs()
    {
        return $this->hasMany(LicenseLog::class, 'license_id');
    }
}
