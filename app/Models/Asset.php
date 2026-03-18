<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class Asset extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'company_id',
        'company_name',
        'department_id',
        'department_name',
        'register_date',
        'code',
        'name',
        'type',
        'category',
        'specification',
        'assembly_year',
        'maintenance_period',
        'ownership',
        'commissioning_date',
        'status',
        'remarks',
        'requestor_id',
        'requestor_name',
        'requestor_department_id',
        'requestor_department_name',
        'last_action',
        'last_user_id',
        'last_user_name',
        'last_approval_level',
        'next_action',
        'next_user_id',
        'next_user_name',
        'approval_status',
        'approval_level',
    ];

    function company()
    {
        return $this->belongsTo(Company::class);
    }

    function department()
    {
        return $this->belongsTo(Department::class);
    }

    function requestor_department()
    {
        return $this->belongsTo(Department::class);
    }

    function requestor()
    {
        return $this->belongsTo(User::class);
    }

    function lastUser()
    {
        return $this->belongsTo(User::class);
    }

    function nextuser()
    {
        return $this->belongsTo(User::class);
    }

    function attachments()
    {
        return $this->hasMany(AssetAttachment::class, 'asset_id');
    }

    function logs()
    {
        return $this->hasMany(AssetLog::class, 'asset_id')
            ->orderBy('created_at', 'desc');
    }
}
