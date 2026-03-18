<?php

namespace App\Models;

use App\Http\Controllers\Transactions\IncidentNotification;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class IncidentAction extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'incident_id',
        'name',
        'assignee_id',
        'assignee_name',
        'due_date',
    ];

    function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    function assignee()
    {
        return $this->belongsTo(User::class);
    }
}
