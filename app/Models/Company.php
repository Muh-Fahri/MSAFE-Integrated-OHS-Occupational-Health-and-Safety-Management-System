<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class Company extends Model
{
    use AuditTable;
    
    protected $table = 'companies';
    protected $fillable = [
        'name',
        'parent_id',
        'hse_manager_id',
        'pjo_id',
        'permit_no',
        'industry',
        'permit_start_date',
        'permit_end_date',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function hseManager()
    {
        return $this->belongsTo(User::class, 'hse_manager_id');
    }

    public function pjo()
    {
        return $this->belongsTo(User::class, 'pjo_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function pjoUser()
    {
        return $this->belongsTo(User::class, 'pjo_id');
    }
}
