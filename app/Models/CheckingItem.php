<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class CheckingItem extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'group',
        'subgroup',
        'name',
    ];
}
