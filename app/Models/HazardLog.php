<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class HazardLog extends Model
{
    use AuditTable;
    
    protected $table = 'hazard_logs';

    protected $fillable = [
        'hazard_id',
        'user_id',
        'user_name',
        'status',
        'remarks',
        'delegator_uid',
        'event',
        'created_by',
    ];

    protected $casts = [
        'hazard_id'     => 'integer',
        'user_id'       => 'integer',
        'delegator_uid' => 'integer',
    ];

    /**
     * Relasi ke Hazard
     */
    public function hazard()
    {
        return $this->belongsTo(Hazard::class);
    }

    /**
     * Relasi ke User (aktor)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke User sebagai delegator
     */
    public function delegator()
    {
        return $this->belongsTo(User::class, 'delegator_uid');
    }
}
