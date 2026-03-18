<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class PersonnelAssignmentFlow extends Model
{
    use AuditTable;
    
    protected $table = 'personnel_assignment_flows';
    protected $fillable = [
        'level',
        'type',
        'action',
        'value',
    ];
}
