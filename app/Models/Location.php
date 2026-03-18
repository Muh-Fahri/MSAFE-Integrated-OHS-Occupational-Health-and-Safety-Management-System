<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;
class Location extends Model
{
    use AuditTable;
    
    protected $table = 'locations';

    protected $fillable = [
        'name',
    ];
}
