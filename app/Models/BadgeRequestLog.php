<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class BadgeRequestLog extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'request_id',
        'user_id',
        'user_name',
        'status',
        'remarks',
        'delegator_uid',
        'event',
    ];

    function request(){
        return $this->belongsTo(BadgeRequest::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }
}
