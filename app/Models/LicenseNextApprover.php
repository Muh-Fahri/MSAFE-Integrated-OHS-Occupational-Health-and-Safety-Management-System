<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class LicenseNextApprover extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'license_id',
        'user_id',
        'user_name'
    ];

    function license(){
        return $this->belongsTo(License::class);
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}
