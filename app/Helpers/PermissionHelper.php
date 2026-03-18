<?php

namespace App\Helpers;

use App\Models\RolePermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionHelper
{
    /**
     * Check if current user has permission for specific menu and action
     *
     * @param  int  $menuId
     * @param  string  $action  (create|edit|delete|show|export|import)
     * @return bool
     */
    public static function hasPermission($menuId, $action)
    {
        // Not authenticated = no permission
        if (! Auth::check()) {
            return false;
        }

        $user = Auth::user();

        // Invalid menu ID = no permission
        if ($menuId === null) {
            return false;
        }

        // Check database permission
        $permission = RolePermission::where('role_id', $user->role_id)
            ->where('menu_id', $menuId)
            ->first();

        // No permission record = no access
        if (! $permission) {
            return false;
        }

        // Check specific action column
        return (bool) $permission->$action;
    }

    /**
     * Check if current user can access menu (at least 1 permission active)
     *
     * @param  int  $menuId
     * @return bool
     */
    public static function canAccessMenu($menuId)
    {
        // Not authenticated = no access
        if (! Auth::check()) {
            return false;
        }

        $user = Auth::user();

        // Menu ID null = no access
        if ($menuId === null) {
            return false;
        }

        // Check if ANY permission is true for this menu
        $permission = RolePermission::where('role_id', $user->role_id)
            ->where('menu_id', $menuId)
            ->first();

        if (! $permission) {
            return false;
        }

        // Menu accessible if at least 1 action is true
        return $permission->create
            || $permission->edit
            || $permission->delete
            || $permission->show
            || $permission->export
            || $permission->import
            || $permission->approve;
    }

    /**
     * Get menu ID by menu name (helper untuk blade)
     *
     * @param  string  $menuName
     * @return int|null
     */
    public static function getMenuIdByName($menuName)
    {
        $menu = \App\Models\Menu::where('menu_name', $menuName)->first();

        return $menu ? $menu->id : null;
    }

    /**
     * Get menu ID from current route name (auto-detect)
     */
    public static function getCurrentMenuId(): ?int
    {
        $routeName = request()->route()?->getName();

        if (! $routeName) {
            return null;
        }

        // Extract base route (e.g., 'users.index' -> 'users')
        $baseRoute = explode('.', $routeName)[0];

        // Map common route patterns to menu URLs
        $routeToMenuUrl = [
            'users'                             => 'users.index',
            'roles'                             => 'roles.index',
            'role-permissions'                  => 'role-permissions.index',
            'menus'                             => 'menus.index',
            'transaction-hazards'               => 'transaction-hazards.index',
            'transaction-incidentNotification'  => 'transaction-incidentNotification.index',
            'transaction-incidentInvestigation' => 'transaction-incidentInvestigation.index',
            'transaction-workPlace'             => 'transaction-workPlace.index',
            'transaction-correctiveAction'      => 'transaction-correctiveAction.index',
            'transaction-license'               => 'transaction-licens.index',
            'transaction-personnel-assignments' => 'transaction-personnel-assignments.index',
            'transaction-asset'                 => 'transaction-asset.index',
            'transaction-badge'                 => 'transaction-badge.index',
            // master
            'master-company'                    => 'master-company.index',
            'master-department'                 => 'master-department.index',
        ];

        $menuUrl = $routeToMenuUrl[$baseRoute] ?? null;

        if (! $menuUrl) {
            return null;
        }

        return \DB::table('menus')
            ->where('url', $menuUrl)
            ->value('id');
    }
}
