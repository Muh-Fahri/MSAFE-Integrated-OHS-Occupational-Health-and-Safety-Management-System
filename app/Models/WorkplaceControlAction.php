<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class WorkplaceControlAction extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'control_id',
        'name',
        'category',
        'status',
        'assignee_id',
        'assignee_name',
        'due_date',
    ];

    function asignee(){
        return $this->belongsTo(User::class);
    }

    function workplace_id(){
        return $this->belongsTo(WorkplaceControl::class);
    }
}
