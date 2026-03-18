<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Menu;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Define available actions per menu
        $menuActions = [
            // Parent menus
            'Administrator' => ['show'],

            // Child menus - full CRUD
            'Users'             => ['create', 'edit', 'delete', 'show', 'export', 'import'],
            'Roles'             => ['create', 'edit', 'delete', 'show'],
            'Role Permissions' => ['show', 'edit'],

            // Other menus
            'Menus' => ['create', 'edit', 'delete', 'show'],
        ];

        // Update each menu
        foreach ($menuActions as $menuName => $actions) {
            Menu::where('menu_name', $menuName)->update([
                'available_actions' => json_encode($actions),
            ]);
        }

        // Set default for menus yang belum di-define (all permissions)
        Menu::whereNull('available_actions')->update([
            'available_actions' => json_encode(['create', 'edit', 'delete', 'show', 'export', 'import']),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Menu::query()->update(['available_actions' => null]);
    }
};
