<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class WorkplaceControlAttachment extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'control_id',
        'file_name',
        'file_type',
        'file_path',
    ];

    function worlplace_id(){
        return $this->belongsTo(WorkplaceControl::class);
    }
}
