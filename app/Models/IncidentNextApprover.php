<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class IncidentNextApprover extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'incident_id',
        'user_id',
        'user_name',
    ];

    function Incident(){
        return $this->belongsTo(Incident::class);
    }

    function User(){
        return $this->belongsTo(User::class);
    }

}
