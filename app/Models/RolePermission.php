<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class RolePermission extends Model
{
    use AuditTable;

    protected $fillable = [
        'role_id',
        'menu_id',
        'create',
        'edit',
        'delete',
        'show',
        'export',
        'import',
        'approve'
    ];

    protected $casts = [
        'create'    => 'boolean',
        'edit'      => 'boolean',
        'delete'    => 'boolean',
        'show'      => 'boolean',
        'export'    => 'boolean',
        'import'    => 'boolean',
        'approve'   => 'boolean',
    ];

    // Relationships: model role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relationships: model menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
