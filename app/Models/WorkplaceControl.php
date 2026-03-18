<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class WorkplaceControl extends Model
{
    use AuditTable;
    
    protected $table = 'workplace_controls';

    protected $fillable = [
        'date',
        'no',
        'operator_id',
        'operator_name',
        'department_id',
        'department_name',
        'location',
        'vehicle_code',
        'vehicle_type',
        'building_type',
        'site',
        'type',
        'activity',
        'activity_company',
        'activity_person',
        'employee_count',
        'area_supervisor',
        'procedure',
        'observation_reason',
        'requestor_id',
        'requestor_name',
        'requestor_department_id',
        'requestor_department_name',
        'remarks'

    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id', 'id');
    }

    public function teams()
    {
        return $this->hasMany(WorkplaceControlTeam::class, 'control_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(WorkplaceControlItem::class, 'control_id', 'id');
    }

    public function actions()
    {
        return $this->hasMany(WorkplaceControlAction::class, 'control_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(WorkplaceControlAttachment::class, 'control_id', 'id');
    }

    public function findings()
    {
        // Sesuaikan 'WorkplaceControlFindings' dengan nama model tabel temuan Anda
        return $this->hasMany(WorkplaceControlItem::class, 'control_id', 'id');
    }
}
