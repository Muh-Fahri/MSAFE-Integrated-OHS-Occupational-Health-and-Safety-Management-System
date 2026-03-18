<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class Incident extends Model
{
    use AuditTable;

    protected $casts = [
        'event_datetime' => 'datetime',
    ];

    protected $fillable = [
        'report_date',
        'no',
        'reporter_id',
        'reporter_name',
        'event_title',
        'event_datetime',
        'location_type',
        'location',
        'company_id',
        'company_name',
        'department_id',
        'department_name',
        'event_type',
        'severity_level_actual',
        'severity_level_actual_remarks',
        'severity_level_potential',
        'severity_level_potential_remarks',
        'incident_description',
        'immediate_actions',
        'work_related',
        'remarks',
        'last_action',
        'last_user_id',
        'last_user_name',
        'last_approval_level',
        'next_action',
        'next_user_id',
        'next_user_name',
        'status',
        'approval_level',
        'photo_1_type',
        'photo_1_path',
        'photo_2_type',
        'photo_2_path',
        'photo_3_type',
        'photo_3_path',
        'photo_4_type',
        'photo_4_path',
        'impact_injury_classification',
        'impact_injury_treatment_details',
        'impact_injury_body_injury',
        'impact_environmental_category',
        'impact_environmental_product_name',
        'impact_environmental_quantity_uom',
        'impact_property_damage_plant_type',
        'impact_property_damage_cost',
        'impact_property_damage_asset_involved',
        'impact_property_damage_info',
        'impact_property_damage_asset_number',
        'fact_finding_description_equipment',
        'fact_finding_description_procedure',
        'fact_finding_causal_factor',
        'impact_injury_description',
        'fact_finding_description_people',
        'fact_finding_description_environment',
        'fact_finding_root_cause',
        'fact_finding_photo_1_type',
        'fact_finding_photo_1_path',
        'fact_finding_photo_2_type',
        'fact_finding_photo_2_path',
        'fact_finding_photo_3_type',
        'fact_finding_photo_3_path',
        'fact_finding_photo_4_type',
        'fact_finding_photo_4_path',
    ];

    function reporter()
    {
        return $this->belongsTo(User::class);
    }

    function company()
    {
        return $this->belongsTo(Company::class);
    }

    function last_user()
    {
        return $this->belongsTo(User::class);
    }

    function department()
    {
        return $this->belongsTo(Department::class);
    }

    function next_user()
    {
        return $this->belongsTo(User::class);
    }

    public function teams()
    {
        return $this->hasMany(IncidentTeam::class, 'incident_id');
    }

    public function actions()
    {
        return $this->hasMany(IncidentAction::class, 'incident_id');
    }

    function logs()
    {
        return $this->hasMany(IncidentLog::class, 'incident_id');
    }

    function next_approvers()
    {
        return $this->hasMany(IncidentNextApprover::class, 'incident_id');
    }
}
