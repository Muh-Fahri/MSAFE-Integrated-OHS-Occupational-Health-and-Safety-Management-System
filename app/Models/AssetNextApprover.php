<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class AssetNextApprover extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'asset_id',
        'user_id',
        'user_name',
        'status',
        'remarks',
        'delegator_uid',
        'event',
    ];

    function asset(){
        return $this->belongsTo(Asset::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }

    function delegator(){
        return $this->belongsTo(User::class);
    }
}
