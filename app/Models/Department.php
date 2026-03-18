<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class Department extends Model
{
    use AuditTable;
    
    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
        'old_ids',
    ];
}
