<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'resource',
        'status',
        'details',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'details' => 'array',
    ];
}
