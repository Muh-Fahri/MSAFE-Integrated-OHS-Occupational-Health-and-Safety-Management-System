<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class PersonnelAssignmentNextApprover extends Model
{
    use AuditTable;
    
    protected $table = 'personnel_assignment_next_approvers';
    protected $fillable = [
        'assignment_id',
        'user_id',
        'user_name'
    ];
}
