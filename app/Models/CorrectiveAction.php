<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class CorrectiveAction extends Model
{
    use AuditTable;

    protected $table = 'corrective_actions';

    protected $fillable = [
        'source',
        'source_id',
        'source_no',
        'source_action_id',

        'risk_issuer_id',
        'risk_issuer_name',
        'risk_issue_date',
        'risk_description',

        'location',
        'department_id',
        'department_name',

        'responsible_person_id',
        'responsible_person_name',

        'corrective_action',
        'action_taken',
        'due_date',

        'status',
        'last_action',
        'last_user_id',
        'last_user_name',

        'next_action',
        'next_user_id',
        'next_user_name',

        'approval_level',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public function evidences()
    {
        return $this->hasMany(CorrectiveActionEvidence::class, 'action_id');
    }

    /**
     * Optional helper: cek apakah sudah selesai
     */
    public function isCompleted(): bool
    {
        return $this->status === 'COMPLETED';
    }
}
