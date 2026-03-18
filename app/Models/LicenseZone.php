<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class LicenseZone extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'license_id',
        'code',
        'color_code',
        'remarks'
    ];

    function license(){
        return $this->belongsTo(License::class);
    }
}
