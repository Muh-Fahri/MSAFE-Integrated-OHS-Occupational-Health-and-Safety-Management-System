<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class BadgeRequest extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'request_date',
        'request_no',
        'requestor_id',
        'requestor_name',
        'company_id',
        'company_name',
        'sub_company_id',
        'sub_company_name',
        'location',
        'taken_by',
        'remarks',
        'last_action',
        'last_user_id',
        'last_user_name',
        'next_action',
        'next_user_id',
        'next_user_name',
        'status',
        'approval_level'
    ];
    function lines()
    {
        // Parameter kedua harus 'request_id'
        return $this->hasMany(BadgeRequestLine::class, 'request_id');
    }

    function requestor()
    {
        return $this->belongsTo(User::class);
    }

    function company()
    {
        return $this->belongsTo(Company::class);
    }

    function lastUser()
    {
        return $this->belongsTo(User::class);
    }

    function subCompany()
    {
        return $this->belongsTo(Company::class);
    }
}
