<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTable;

class Role extends Model
{

    use AuditTable;

    protected $fillable = [
        'role',
        'name',
    ];

    // Relationships: menghubungkan ke permissions
    public function permissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    // Helper: Cek apakah role ini punya permission
    public function hasPermission(): bool
    {
        return $this->permissions()->exists();
    }

    // Relationships: menghubungkan ke menus melalui role_permissions
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_permissions')
                    ->withPivot(['create', 'edit', 'delete', 'show', 'export', 'import'])
                    ->withTimestamps();
    }
}
