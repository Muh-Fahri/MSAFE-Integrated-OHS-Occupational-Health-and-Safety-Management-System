<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class CorrectiveActionEvidence extends Model
{
    use AuditTable;
    
    protected $table = 'corrective_action_evidences';

    protected $fillable = [
        'action_id',
        'remark',
        'file_type',
        'file_path',
    ];

    /**
     * Relasi ke Corrective Action
     */
    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class, 'action_id');
    }
}
