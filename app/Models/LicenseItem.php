<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;
use App\Models\License;

class LicenseItem extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'license_id',
        'code',
        'name'
    ];

    function license(){
        return $this->belongsTo(License::class);
    }
}
