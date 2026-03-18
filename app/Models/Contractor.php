<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class Contractor extends Model
{
    use AuditTable;
    
    protected $table = 'contractors';

    protected $fillable = [
        'employee_id',
        'citizen_id',
        'tag_id',
        'full_name',
        'job_position',
        'department',
        'company',
        'gender',
    ];
}
