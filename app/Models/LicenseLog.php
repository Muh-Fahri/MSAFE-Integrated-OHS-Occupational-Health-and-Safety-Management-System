<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Models\License;
use App\Traits\AuditTable;
class LicenseLog extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'license_id',
        'user_id',
        'user_name',
        'status',
        'remarks',
        'delegator_uid',
        'event'
    ];

    function license(){
        return $this->belongsTo(License::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }
}
