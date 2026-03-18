<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Menu;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RolePermissionController extends Controller
{
    /**
     * Display the role permissions page
     */
    public function index()
    {
        $roles = Role::all();
        return view('administrator.role-permissions.index', compact('roles'));
    }

    /**
     * Get permissions for a specific role
     */
    public function show($roleId)
    {
        // Get all menus with their children
        $menus = Menu::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        // Get current permissions for this role
        $permissions = RolePermission::where('role_id', $roleId)->get();

        // Format permissions ke array yang mudah diakses
        $details = [];
        foreach ($permissions as $perm) {
            $details[$perm->menu_id] = [
                'create'    => $perm->create,
                'edit'      => $perm->edit,
                'delete'    => $perm->delete,
                'show'      => $perm->show,
                'export'    => $perm->export,
                'import'    => $perm->import,
            ];
        }

        // Format menus dengan available actions
        $formattedMenus = $menus->map(function ($menu) {
            try {
                $menuData = [
                    'id'                => $menu->id,
                    'menu_name'         => $menu->menu_name,
                    'available_actions' => $menu->getAvailableActions(),
                ];

                if ($menu->children && $menu->children->isNotEmpty()) {
                    $menuData['children'] = $menu->children->map(function ($child) {
                        return [
                            'id'                => $child->id,
                            'menu_name'         => $child->menu_name,
                            'available_actions' => $child->getAvailableActions(),
                        ];
                    })->toArray();
                }

                return $menuData;
            } catch (\Exception $e) {
                \Log::error('Error formatting menu: ' . $menu->id . ' - ' . $e->getMessage());
                return null;
            }
        })->filter();

        return response()->json([
            'menus'         => $formattedMenus,
            'details'       => $details,
            'all_actions'   => Menu::getAllActions(),
        ]);
    }

    /**
     * Store/Update permissions for a role
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_id'       => 'required|exists:roles,id',
            'permissions'   => 'required|array',
        ]);

        $roleId         = $request->role_id;
        $permissions    = $request->permissions;

        // Delete existing permissions untuk role ini
        RolePermission::where('role_id', $roleId)->delete();

        // Insert new permissions
        foreach ($permissions as $menuId => $actions) {
            // Get menu to check available actions
            $menu = Menu::find($menuId);
            if (!$menu) continue;

            $availableActions = $menu->getAvailableActions();

            // Prepare data - only save permissions that are available
            $data = [
                'role_id' => $roleId,
                'menu_id' => $menuId,
            ];

            // Set each action based on availability
            foreach (Menu::getAllActions() as $action) {
                if (in_array($action, $availableActions)) {
                    $data[$action] = isset($actions[$action]) && $actions[$action] == 'on';
                } else {
                    $data[$action] = false;
                }
            }

            RolePermission::create($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permissions saved successfully'
        ]);
    }
}
