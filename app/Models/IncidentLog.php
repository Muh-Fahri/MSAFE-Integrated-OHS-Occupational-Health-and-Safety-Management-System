<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class IncidentLog extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'incident_id',
        'user_id',
        'user_name',
        'status',
        'remarks',
        'delegator_uid',
        'event',
    ];

    function incident(){
        return $this->belongsTo(Incident::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }

    function delegator(){
        return $this->belongsTo(User::class);
    }
}
