<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class WorkplaceControlTeam extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'control_id',
        'name',
        'role',
        'department'
    ];

    function workplace_id(){
        return $this->belongsTo(WorkplaceControl::class);
    }
}
