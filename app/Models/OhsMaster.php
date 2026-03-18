<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class OhsMaster extends Model
{
    use AuditTable;
    
    protected $table = 'ohs_masters';

    protected $fillable = [
        'type',
        'code',
        'name',
        'value1',
        'value2',
        'value3',
    ];

    /**
     * Scope berdasarkan type
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
