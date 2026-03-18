<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Menu;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\PermissionHelper;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Menu $menuId)
    {
        if ($request->ajax()) {
            $menuId = PermissionHelper::getCurrentMenuId();
            $roles = Role::all();

            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('action', function ($role) use ($menuId) {
                    $buttons = '';

                    if (PermissionHelper::hasPermission($menuId, 'edit')) {
                        $buttons .= '<a href="'.route('roles.edit', $role->id).'" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-pen"></i>
                                    </a>';
                    }

                    if (PermissionHelper::hasPermission($menuId, 'delete')) {
                        $buttons .= '<button onclick="deleteRole('.$role->id.')" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('administrator.roles.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrator.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role'      => 'required|string|max:50|unique:roles,role',
            'name'      => 'required|string|max:100|unique:roles,name',
        ]);

        // Auto uppercase untuk field role
        $validated['role'] = strtoupper($validated['role']);

        Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);

        return view('administrator.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'role'      => 'required|string|max:50|unique:roles,role,'.$role->id,
            'name'      => 'required|string|max:100|unique:roles,name,'.$role->id,
        ]);

        // Auto uppercase role code
        $validated['role'] = strtoupper($validated['role']);

        $role->update($validated);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {

        // Validation: block delete if role has permissions (nanti pas role_permissions udah ada)
        // if ($role->hasPermissions()) {
        //     return response()->json(['success' => false, 'message' => 'Cannot delete role that is being used']);
        // }

        $role->delete();

        return response()->json(['success' => true, 'message' => 'Role deleted successfully']);

    }
}
