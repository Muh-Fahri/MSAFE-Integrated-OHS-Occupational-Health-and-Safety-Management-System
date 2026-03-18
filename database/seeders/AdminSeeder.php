<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Role Admin
        $adminRole = Role::firstOrCreate(
            ['role' => 'ADMIN'],
            ['name' => 'Administrator']
        );

        // 2. Buat Menu-menu dengan available_actions
        $menus = [
            [
                'menu_name' => 'Administrator',
                'url' => null,
                'parent_id' => null,
                'icon' => 'fas fa-user-shield',
                'order' => 2,
                'available_actions' => ['show'], // Parent menu biasanya cuma show
            ],
            [
                'menu_name' => 'Menus',
                'url' => 'menus.index',
                'parent_id' => null,
                'icon' => 'fas fa-bars',
                'order' => 3,
                'available_actions' => ['create', 'edit', 'delete', 'show'], // Full CRUD tanpa export/import
            ],
        ];

        $createdMenus = [];
        foreach ($menus as $menuData) {
            // Casting di model sudah handle conversion ke JSON
            $menu = Menu::firstOrCreate(
                ['menu_name' => $menuData['menu_name'], 'parent_id' => $menuData['parent_id']],
                $menuData
            );
            $createdMenus[$menu->menu_name] = $menu;
        }

        // 3. Buat Submenu Administrator
        $administratorMenu = $createdMenus['Administrator'];

        $subMenus = [
            [
                'menu_name' => 'Users',
                'url' => 'users.index',
                'parent_id' => $administratorMenu->id,
                'icon' => 'fas fa-users',
                'order' => 1,
                'available_actions' => ['create', 'edit', 'delete', 'show', 'export', 'import'], // Full access
            ],
            [
                'menu_name' => 'Roles',
                'url' => 'roles.index',
                'parent_id' => $administratorMenu->id,
                'icon' => 'fas fa-user-tag',
                'order' => 2,
                'available_actions' => ['create', 'edit', 'delete', 'show'], // No export/import
            ],
            [
                'menu_name' => 'Role Permissions',
                'url' => 'role-permissions.index',
                'parent_id' => $administratorMenu->id,
                'icon' => 'fas fa-key',
                'order' => 3,
                'available_actions' => ['show', 'edit'], // Cuma lihat dan edit, tanpa create/delete
            ],
        ];

        foreach ($subMenus as $subMenuData) {
            // Casting di model sudah handle conversion ke JSON
            $subMenu = Menu::firstOrCreate(
                ['menu_name' => $subMenuData['menu_name'], 'parent_id' => $subMenuData['parent_id']],
                $subMenuData
            );
            $createdMenus[$subMenu->menu_name] = $subMenu;
        }

        // 4. Buat Role Permissions untuk semua menu
        $allMenus = Menu::all();
        foreach ($allMenus as $menu) {
            $availableActions = $menu->getAvailableActions();

            $permissionData = [
                'role_id' => $adminRole->id,
                'menu_id' => $menu->id,
            ];

            // Set permissions based on available actions
            foreach (Menu::getAllActions() as $action) {
                $permissionData[$action] = in_array($action, $availableActions);
            }

            RolePermission::updateOrCreate(
                [
                    'role_id' => $adminRole->id,
                    'menu_id' => $menu->id,
                ],
                $permissionData
            );
        }

        // 5. Buat User Admin
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'photo' => null,
            ]
        );

        $this->command->info('Admin seeder berhasil dijalankan!');
        $this->command->info('Username: admin');
        $this->command->info('Password: admin123');
    }
}
