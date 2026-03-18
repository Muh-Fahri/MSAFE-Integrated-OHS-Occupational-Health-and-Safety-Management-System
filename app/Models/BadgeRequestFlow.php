<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class BadgeRequestFlow extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'level',
        'type',
        'action',
        'value',
    ];
}
