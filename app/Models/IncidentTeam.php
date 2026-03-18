<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class IncidentTeam extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'incident_id',
        'name',
        'role',
    ];

    function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}
