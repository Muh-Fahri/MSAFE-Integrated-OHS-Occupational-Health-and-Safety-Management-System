<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class AssetAttachment extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'asset_id',
        'file_name',
        'file_type',
        'file_path'
    ];

    function asset(){
        return $this->belongsTo(Asset::class);
    }
}
