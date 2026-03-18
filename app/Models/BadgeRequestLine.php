<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class BadgeRequestLine extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'request_id',
        'seq',
        'employee_id',
        'citizen_id',
        'name',
        'title',
        'active_period',
        'status',
        'contract_start_date',
        'contract_end_date',
        'contract_period',
        'time_unit',
        'file_type_photo',
        'file_path_photo',
        'file_type_ftw',
        'file_path_ftw',
        'file_type_mcu',
        'file_path_mcu',
        'file_type_covid',
        'file_path_covid',
        'file_type_ktp',
        'file_path_ktp',
        'file_type_domicile',
        'file_path_domicile',
        'file_type_skck',
        'file_path_skck',
        'file_type_induksi',
        'file_path_induksi',
    ];

    function request()
    {
        return $this->belongsTo(BadgeRequest::class);
    }
    public function badgeRequest()
    {
        // Gunakan 'request_id' sesuai dengan yang ada di fillable dan DB
        return $this->belongsTo(BadgeRequest::class, 'request_id');
    }
}
