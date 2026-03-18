<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class Badge extends Model
{
    use AuditTable;

    protected $fillable = [
        'company_id',
        'sub_company_id',
        'employee_id',
        'citizen_id',
        'name',
        'title',
        'active_period',
        'active_from',
        'active_to',
        'status',
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

    function company()
    {
        return $this->belongsTo(Company::class);
    }

    function subCompany()
    {
        return $this->belongsTo(Company::class);
    }

    // Di Badges.php
    public function requestLines()
    {
        return $this->hasMany(BadgeRequestLine::class, 'employee_id', 'employee_id');
    }

    // Di BadgeRequestLines.php
    public function badge()
    {
        return $this->belongsTo(Badge::class, 'employee_id', 'employee_id');
    }

    // Di dalam class Badges
    public function requestHeader()
    {
        return $this->hasOneThrough(
            \App\Models\BadgeRequest::class,      // Target: Tabel yang mau diambil datanya
            \App\Models\BadgeRequestLine::class, // Lewat: Tabel perantara
            'employee_id', // Foreign key di tabel Lines yang nyambung ke Badges
            'id',          // Foreign key di tabel Request (Header)
            'employee_id', // Local key di tabel Badges
            'request_id'   // Local key di tabel Lines yang nyambung ke Request (Header)
        );
    }
}
