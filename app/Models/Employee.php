<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class Employee extends Model
{
    use AuditTable;
    
    protected $table = 'employees';

    protected $fillable = [
        'employee_id',
        'tag_id',
        'full_name',
        'barcode',
        'division',
        'company',
        'organization',
        'job_position',
        'job_level',
        'join_date',
        'resign_date',
        'employee_status',
        'end_date',
        'email',
        'birth_date',
        'birth_place',
        'citizen_id_address',
        'residential_address',
        'npwp',
        'ptkp_status',
        'employee_tax_status',
        'tax_config',
        'bank_name',
        'bank_account',
        'bank_account_holder',
        'bpjs_ketenagakerjaan',
        'bpjs_kesehatan',
        'citizen_id',
        'mobile_phone',
        'phone',
        'branch_name',
        'religion',
        'gender',
        'marital_status',
        'nationality_code',
        'currency',
        'length_of_service',
        'payment_schedule',
        'approval_line_id',
        'approval_line',
        'grade',
        'class',
        'point_of_hire',
        'point_of_hire_status',
        'point_of_travel',
        'roster',
        'company_email',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_number',
        'blood_type',
    ];

    /**
     * Casting tanggal
     */
    protected $casts = [
        'join_date'   => 'date',
        'resign_date' => 'date',
        'end_date'    => 'date',
        'birth_date'  => 'date',
    ];
}
