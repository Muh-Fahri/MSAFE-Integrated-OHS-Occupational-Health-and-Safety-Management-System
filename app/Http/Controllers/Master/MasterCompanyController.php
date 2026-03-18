<?php

namespace App\Http\Controllers\Master;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class MasterCompanyController extends Controller
{
    public function index(Request $request)
    {
        $menuId = PermissionHelper::getCurrentMenuId();
        $query = Company::with('parent', 'hseManager', 'pjo');
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }
        $comp = $query->latest()->paginate(10)->withQueryString();
        $allCompanies = Company::all();

        return view('master.company.index', compact('comp', 'allCompanies', 'menuId'));
    }

    public function create()
    {
        $comp = Company::all();
        $user = User::all();
        return view('master.company.create', compact('comp', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
            'parent_id' => 'nullable|exists:companies,id',
            'permit_no' => 'nullable|string',
            'permit_start_date' => 'nullable|date',
            'permit_end_date' => 'nullable|date|after_or_equal:permit_start_date',
        ], [
            'name.required' => 'Nama perusahaan wajib diisi bos!',
            'name.unique' => 'Nama Perusahaan ini sudah terdaftar di sistem. Pakai nama lain ya!',
            'permit_end_date.after_or_equal' => 'Tanggal berakhir permit tidak boleh lebih kecil dari tanggal mulai.',
        ]);
        Company::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'hse_manager_id' => $request->hse_manager_id,
            'pjo_id' => $request->pjo_id,
            'permit_no' => $request->permit_no,
            'industry' => $request->industry,
            'permit_start_date' => $request->permit_start_date,
            'permit_end_date' => $request->permit_end_date,
        ]);

        return redirect()->route('master-company.index')->with('success', 'Company created successfully.');
    }

    public function edit($id)
    {
        $data = Company::find($id);
        $comp = Company::all();
        $user = User::all();
        return view('master.company.edit', compact('comp', 'user', 'data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
            'parent_id' => 'nullable|exists:companies,id',
            'permit_no' => 'nullable|string',
            'permit_start_date' => 'nullable|date',
            'permit_end_date' => 'nullable|date|after_or_equal:permit_start_date',
        ], [
            'name.required' => 'Nama perusahaan wajib diisi bos!',
            'name.unique' => 'Nama Perusahaan ini sudah terdaftar di sistem. Pakai nama lain ya!',
            'permit_end_date.after_or_equal' => 'Tanggal berakhir permit tidak boleh lebih kecil dari tanggal mulai.',
        ]);

        $comp = Company::find($id);
        $comp->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'hse_manager_id' => $request->hse_manager_id,
            'pjo_id' => $request->pjo_id,
            'permit_no' => $request->permit_no,
            'industry' => $request->industry,
            'permit_start_date' => $request->permit_start_date,
            'permit_end_date' => $request->permit_end_date,
        ]);

        return redirect()->route('master-company.index')->with('success', 'Company updated successfully.');
    }

    public function destroy($id) {
        $comp = Company::find($id);
        if ($comp) {
            $comp->delete();
            return redirect()->route('master-company.index')->with('success', 'Company deleted successfully.');
        }
        return redirect()->route('master-company.index')->with('error', 'Company not found.');
    }
}
