<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class Hazard extends Model
{
    use AuditTable;
    
    protected $table = 'hazards';

    protected $fillable = [
        'no',

        'requestor_id',
        'requestor_name',
        'requestor_department_id',
        'requestor_department_name',

        'reporter_id',
        'reporter_name',
        'reporter_department_id',
        'reporter_department_name',

        'recipient_id',
        'recipient_name',
        'recipient_department_id',
        'recipient_department_name',

        'report_datetime',
        'location',
        'hazard_source',
        'hazard_type',
        'hazard_description',
        'immediate_actions',
        'corrective_action',
        'action_taken',

        'due_date',
        'completed_date',

        'assignee_id',
        'assignee_name',
        'assignee_department_id',
        'assignee_department_name',

        'remarks',

        'last_action',
        'last_user_id',
        'last_user_name',
        'next_action',
        'status',
        'approval_level',

        'file_1_type',
        'file_1_path',
        'file_2_type',
        'file_2_path',
        'file_3_type',
        'file_3_path',
        'file_4_type',
        'file_4_path',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'report_datetime' => 'datetime',
        'due_date'        => 'date',
        'completed_date'  => 'date',
        'approval_level'  => 'integer',
    ];

    // Relasi ke tabel Master Hazard Source
    public function hazardSourceRelation()
    {
        // Mengasumsikan nama Model Masternya adalah HazardSource
        // 'hazard_source' adalah nama kolom di tabel hazards
        return $this->belongsTo(Hazard::class, 'hazard_source');
    }

    // Relasi ke tabel Master Location
    public function locationRelation()
    {
        // Mengasumsikan nama Model Masternya adalah Location
        // 'location' adalah nama kolom di tabel hazards
        return $this->belongsTo(Location::class, 'location');
    }

    // Relasi ke tabel User untuk Reporter
    public function reporter()
    {
        // 'reporter_id' adalah nama kolom di tabel hazards
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
