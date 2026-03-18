<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class GeneralTable extends Model
{
    use AuditTable;
    
    protected $table = 'general_tables';

    protected $fillable = [
        'type',
        'code',
        'name',
        'value1',
        'value2',
        'value3',
    ];
}
