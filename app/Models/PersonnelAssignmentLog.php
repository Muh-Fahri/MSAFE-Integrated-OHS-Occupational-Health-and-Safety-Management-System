<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class PersonnelAssignmentLog extends Model
{
    use AuditTable;
    
    protected $table = 'personnel_assignment_logs';
    protected $fillable = [
        'assignment_id',
        'user_id',
        'user_name',
        'status',
        'remarks',
        'delegator_uid',
        'event'
    ];
}
