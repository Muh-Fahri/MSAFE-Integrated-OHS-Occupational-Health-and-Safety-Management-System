<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class PersonnelAssignmentDetail extends Model
{
    use AuditTable;
    
    protected $table = 'personnel_assignment_details';
    protected $fillable = [
        'assignment_id',
        'employee_id',
        'employee_name',
        'employee_title',
        'employee_department',
        'assignment_type',
        'assignment_field',
        'file_1_type',
        'file_1_path',
        'file_2_type',
        'file_2_path',
        'file_3_type',
        'file_3_path',
        'file_4_type',
        'file_4_path',
        'file_5_type',
        'file_5_path',
    ];
}
