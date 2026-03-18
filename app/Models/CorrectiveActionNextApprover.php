<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CorrectiveAction;
use App\Traits\AuditTable;

class CorrectiveActionNextApprover extends Model
{
    use AuditTable;
    
    protected $table = 'corrective_action_next_approvers';

    protected $fillable = [
        'action_id',
        'user_id',
        'user_name',
    ];

    /**
     * Relasi ke Corrective Action
     */
    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class, 'action_id');
    }

    /**
     * Relasi ke User (Approver)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
