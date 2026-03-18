<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\AuditTable;

class PersonnelAssignment extends Model
{
    use AuditTable;

    protected $table = 'personnel_assignments';
    protected $fillable = [
        'request_date',
        'request_no',
        'requestor_name',
        'requestor_id',
        'company_id',
        'company_name',
        'sub_company_id',
        'sub_company_name',
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
        'updated_by',
    ];

    function user_belongs(){
        return $this->belongsTo(User::class);
    }

    function company_belongs(){
        return $this->belongsTo(Company::class);
    }

    function personel_assign(){
        return $this->hasMany(PersonnelAssignmentDetail::class, 'assignment_id', 'id');
    }
}
