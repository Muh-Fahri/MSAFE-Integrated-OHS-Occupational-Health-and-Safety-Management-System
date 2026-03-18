<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class Flow extends Model
{
    use AuditTable;
    
    protected $table = 'flows';

    protected $fillable = [
        'level',
        'type',
        'process',
        'action',
        'value',
    ];
}
