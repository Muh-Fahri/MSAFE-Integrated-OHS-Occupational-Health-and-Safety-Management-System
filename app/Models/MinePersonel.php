<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinePersonel extends Model
{
    protected $table = 'mine_personels';

    protected $fillable = [
        'tanggal_pengajuan',
        'no',
        'company',
        'company_name',
        'employee_id',
        'name',
        'title',
        'department_id',
        'department_name',
        'appointment',
        'attachments',
        'status',
        'next_action',
        'next_user_id',
        'next_user_name',
        'last_action',
        'last_user_id',
        'last_user_name',
        'approval_level',
        'list_approval_level',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'company'           => 'integer',
        'department_id'     => 'integer',
        'next_user_id'      => 'integer',
        'last_user_id'      => 'integer',
        'approval_level'    => 'integer',
        'list_approval_level' => 'integer',
    ];

    /**
     * Relasi ke Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company');
    }

    /**
     * Relasi ke Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relasi user berikutnya (approver)
     */
    public function nextUser()
    {
        return $this->belongsTo(User::class, 'next_user_id');
    }

    /**
     * Relasi user terakhir (aktor)
     */
    public function lastUser()
    {
        return $this->belongsTo(User::class, 'last_user_id');
    }

    /**
     * Scope: data yang masih proses
     */
    public function scopeOnProgress($query)
    {
        return $query->where('status', '!=', 'COMPLETED');
    }
}
