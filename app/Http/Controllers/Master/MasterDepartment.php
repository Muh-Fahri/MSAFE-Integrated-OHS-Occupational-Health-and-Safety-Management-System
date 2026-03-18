<?php

namespace App\Http\Controllers\Master;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class MasterDepartment extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $menuId = PermissionHelper::getCurrentMenuId();
        $query = Department::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }
        $depart = $query->latest()->paginate(10)->withQueryString();
        return view('master.department.index', compact('depart', 'menuId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.department.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:departments,code',
            'name' => 'required|string|max:255|unique:departments,name',
        ], [
            'code.required' => 'Department code is required.',
            'code.unique' => 'This department code is already taken. Please choose another one.',
            'name.required' => 'Department name is required.',
            'name.unique' => 'This department name is already taken. Please choose another one.',
        ]);
        Department::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);
        return redirect()->route('master-department.index')->with('success', 'Department created successfully!');
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
        $dept = Department::findOrFail($id);
        return view('master.department.edit', compact('dept'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dept = Department::findOrFail($id);
        $dept->update([
            'code' => $request->code,
            'name' => $request->name,
        ]);
        return redirect()->route('master-department.index')->with('success', 'Department updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dept = Department::findOrFail($id);
        $dept->delete();
        return redirect()->route('master-department.index')->with('success', 'Department deleted successfully!');
    }
}
