<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CorrectiveAction;
use App\Traits\AuditTable;

class CorrectiveActionLog extends Model
{
    use AuditTable;
    
    protected $table = 'corrective_action_logs';

    protected $fillable = [
        'action_id',
        'user_id',
        'user_name',
        'status',
        'remarks',
        'delegator_uid',
        'event',
    ];

    /**
     * Relasi ke Corrective Action
     */
    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class, 'action_id');
    }

    /**
     * User yang melakukan action
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * User delegator (jika ada)
     */
    public function delegator()
    {
        return $this->belongsTo(User::class, 'delegator_uid');
    }
}
