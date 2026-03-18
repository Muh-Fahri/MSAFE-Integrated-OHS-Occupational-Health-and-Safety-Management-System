<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\AuditTable;

class Delegation extends Model
{
    use AuditTable;
    
    protected $table = 'delegations';

    protected $fillable = [
        'type',
        'delegator',
        'delegatee',
        'begin_date',
        'end_date',
    ];

    /**
     * User pemberi delegasi
     */
    public function delegatorUser()
    {
        return $this->belongsTo(User::class, 'delegator');
    }

    /**
     * User penerima delegasi
     */
    public function delegateeUser()
    {
        return $this->belongsTo(User::class, 'delegatee');
    }

    /**
     * Scope: delegasi aktif
     */
    public function scopeActive($query)
    {
        return $query->whereDate('begin_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }
}
