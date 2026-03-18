<?php

namespace App\Models;

use App\Models\License;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class LicenseAttachment extends Model
{
    use AuditTable;
    
    protected $table = 'license_attachments';
    protected $fillable = [
        'license_id',
        'file_name',
        'file_type',
        'file_path',
    ];

    function license()
    {
        return $this->belongsTo(License::class);
    }
}
