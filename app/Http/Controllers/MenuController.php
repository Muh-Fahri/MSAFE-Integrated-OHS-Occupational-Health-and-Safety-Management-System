<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Services\RouteActionDetector;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\PermissionHelper;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Menu $menuId)
    {
        if ($request->ajax()) {
            $menuId = PermissionHelper::getCurrentMenuId();
            $menus  = Menu::all();

            return DataTables::of($menus)
                ->addIndexColumn()
                ->addColumn('parent_name', function ($row) {
                    return $row->parent ? $row->parent->menu_name : '-';
                })
                ->addColumn('type', function ($row) {
                    return $row->isParent() ? '<span class="badge bg-primary">Parent</span>' : '<span class="badge bg-secondary">Child</span>';
                })
                ->addColumn('action', function ($menu) use ($menuId) {
                    $buttons = '';

                    if (PermissionHelper::hasPermission($menuId, 'edit')) {
                        $buttons .= '<a href="'.route('menus.edit', $menu->id).'" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-pen"></i>
                                    </a>';
                    }

                    if (PermissionHelper::hasPermission($menuId, 'delete')) {
                        $buttons .= '<button onclick="deleteMenu('.$menu->id.')" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['type', 'action'])
                ->make(true);
        }

        return view('menus.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents    = Menu::whereNull('parent_id')->orderBy('order')->get();
        $allActions = Menu::getAllActions();

        return view('menus.create', compact('parents', 'allActions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_name'             => 'required|string|max:255',
            'url'                   => 'nullable|string|max:255',
            'parent_id'             => 'nullable|exists:menus,id',
            'icon'                  => 'nullable|string|max:255',
            'order'                 => 'required|integer|min:0',
            'is_manual_override'    => 'boolean',
            'available_actions'     => 'nullable|array',
        ]);

        // jika child, set icon jadi null
        if ($request->parent_id) {
            $validated['icon'] = null;
        }

        // Handle available actions
        $isManual   = $request->has('is_manual_override') && $request->is_manual_override;
        $menu       = new Menu($validated);

        if ($isManual) {
            $menu->syncAvailableActions(true, $request->available_actions ?? []);
        } else {
            $menu->syncAvailableActions(false);
        }

        $menu->save();

        return redirect()->route('menus.index')->with('success', 'Menu created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $parents = Menu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->orderBy('order')
            ->get();

        $allActions = Menu::getAllActions();

        return view('menus.edit', compact('menu', 'parents', 'allActions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'menu_name'             => 'required|string|max:255',
            'url'                   => 'nullable|string|max:255',
            'parent_id'             => 'nullable|exists:menus,id',
            'icon'                  => 'nullable|string|max:255',
            'order'                 => 'required|integer|min:0',
            'is_manual_override'    => 'boolean',
            'available_actions'     => 'nullable|array',
        ]);

        // jika child, set icon jadi null
        if ($request->parent_id) {
            $validated['icon'] = null;
        }

        // Update basic fields
        $menu->fill($validated);

        // Handle available actions
        $isManual = $request->has('is_manual_override') && $request->is_manual_override;

        if ($isManual) {
            $menu->syncAvailableActions(true, $request->available_actions ?? []);
        } else {
            $menu->syncAvailableActions(false);
        }

        $menu->save();

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully');
    }

    /**
     * AJAX: Detect available actions from route name
     */
    public function detectActions(Request $request)
    {
        $routeName = $request->input('route_name');

        if (empty($routeName)) {
            return response()->json([
                'detected_actions'  => [],
                'details'           => null,
            ]);
        }

        $details = RouteActionDetector::getRouteDetails($routeName);

        return response()->json([
            'detected_actions' => $details['detected_actions'],
            'details'          => $details,
        ]);
    }
}
