<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class WorkplaceControlItem extends Model
{
    use AuditTable;
    
    protected $fillable = [
        'control_id',
        'checking_item_id',
        'checking_item_name',
        'result',
        'remarks',
    ];

    function workplace_control(){
       return $this->belongsTo(WorkplaceControl::class);
    }
}
