<?php

namespace App\Http\Controllers\Administrator;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\returnCallback;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $menuId = PermissionHelper::getCurrentMenuId();
            $users = User::with('role', 'department', 'company', 'hod', 'hod2');

            if ($request->filled('username')) {
                $users->where('username', 'like', '%' . $request->username . '%');
            }
            if ($request->filled('name')) {
                $users->where('name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('email')) {
                $users->where('email', 'like', '%' . $request->email . '%');
            }
            if ($request->filled('phone')) {
                $users->where('phone', 'like', '%' . $request->phone . '%');
            }
            if ($request->filled('role')) {
                $users->where('role_id', $request->role);
            }
            if ($request->filled('employee_id')) {
                $users->where('employee_id', 'like', '%' . $request->employee_id . '%');
            }
            if ($request->filled('status')) {
                $users->where('status', $request->status);
            }

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('role_name', function ($user) {
                    return $user->role ? $user->role->name : '-';
                })
                // mengambil nama department berdasarkan id
                ->addColumn('department_name', function ($user) {
                    return $user->department ? $user->department->name : '-';
                })
                // mengambil nama company berdasakran id
                ->addColumn('company_name', function ($user) {
                    return $user->company ? $user->company->name : '-';
                })
                // mengambil nama hod berdasarkan id
                ->addColumn('hod_name', function ($user) {
                    if ($user->hod) {
                        $hodUser = User::find($user->hod);
                        return $hodUser ? $hodUser->name : '-';
                    }
                    return '-';
                })
                // mengambil nama hod2 berdasarkan id
                ->addColumn('hod2_name', function ($user) {
                    if ($user->hod2) {
                        $hod2User = User::find($user->hod2);
                        return $hod2User ? $hod2User->name : '-';
                    }
                    return '-';
                })
                ->addColumn('action', function ($user) use ($menuId) {
                    $buttons = '<div class="d-flex justify-content-center">'; // Bungkus agar rapi di tengah

                    // Show button
                    if (PermissionHelper::hasPermission($menuId, 'show')) {
                        $buttons .= '<a href="' . url('administrator/users/' . $user->id) . '" class="btn btn-info btn-xs me-1" title="View">
                    <i class="fas fa-eye fa-xs"></i>
                </a>';
                    }

                    // Edit button
                    if (PermissionHelper::hasPermission($menuId, 'edit')) {
                        $buttons .= '<a href="' . url('administrator/users/' . $user->id . '/edit') . '" class="btn btn-warning btn-xs me-1" title="Edit">
                    <i class="fas fa-pen fa-xs"></i>
                </a>';
                    }

                    // Delete button
                    if (PermissionHelper::hasPermission($menuId, 'delete')) {
                        $buttons .= '<button onclick="deleteUser(' . $user->id . ')" class="btn btn-danger btn-xs" title="Delete">
                    <i class="fas fa-trash fa-xs"></i>
                </button>';
                    }

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('administrator.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $departments = Department::all();
        $companies = Company::all();
        $users = User::all();

        return view('administrator.users.create', compact('roles', 'departments', 'companies', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username'      => 'required|alpha_dash|max:50|unique:users,username',
            'name'          => 'required|max:100',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'email'         => 'nullable|email|unique:users,email',
            'password'      => 'required|min:8',
            'role_id'       => 'required|exists:roles,id',
            'phone'         => 'nullable|max:20',
            'employee_id'   => 'required|unique:users,employee_id',
            'department_id' => 'required|exists:departments,id',
            'company_id'    => 'required|exists:companies,id',
            'hod'           => 'nullable|exists:users,id',
            'hod2'          => 'nullable|exists:users,id',
        ]);

        // Ambil semua data kecuali password dan photo untuk diproses manual
        $data = $request->except(['password', 'photo']);

        // Hash password agar aman
        $data['password'] = Hash::make($request->password);

        // Default status jika tidak ada di form
        $data['status'] = 'ACTIVE';

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $basePath = env('FILE_PATH');
            $uploadPath = $basePath . DIRECTORY_SEPARATOR . 'photo';
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $photo    = $request->file('photo');
            $filename = time() . '_' . str_replace(' ', '_', $photo->getClientOriginalName());
            $photo->move($uploadPath, $filename);
            $data['photo'] = $filename;
        }
        User::create($data);
        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('administrator.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $departments = Department::all();
        $companies = Company::all();
        $users = User::all();

        return view('administrator.users.edit', compact('user', 'roles', 'departments', 'companies', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username'      => 'required|alpha_dash|max:50|unique:users,username,' . $user->id,
            'name'          => 'required|max:100',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'email'         => 'nullable|email|unique:users,email,' . $user->id,
            'password'      => 'nullable|min:8',
            'role_id'       => 'required|exists:roles,id',
            'employee_id'   => 'required|unique:users,employee_id,' . $user->id,
            'department_id' => 'required|exists:departments,id',
            'company_id'    => 'required|exists:companies,id',
            'hod'           => 'nullable|exists:users,id',
            'hod2'          => 'nullable|exists:users,id',
        ]);

        $data = $request->except(['password', 'photo']);

        // Handle photo upload ke folder eksternal
        if ($request->hasFile('photo')) {
            // Ambil base path dari .env
            $basePath = env('FILE_PATH');
            $uploadPath = $basePath . DIRECTORY_SEPARATOR . 'photo';
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            if ($user->photo) {
                $oldFilePath = $uploadPath . DIRECTORY_SEPARATOR . $user->photo;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $photo    = $request->file('photo');
            $filename = time() . '_' . str_replace(' ', '_', $photo->getClientOriginalName());
            $photo->move($uploadPath, $filename);
            $data['photo'] = $filename;
        }

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // 1. Ambil path eksternal dari .env
        $basePath = env('FILE_PATH');
        $uploadPath = $basePath . DIRECTORY_SEPARATOR . 'photo';

        // 2. Hapus foto dari folder eksternal jika ada
        if ($user->photo) {
            $filePath = $uploadPath . DIRECTORY_SEPARATOR . $user->photo;

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // 3. Hapus data user dari database
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User and their photo have been deleted successfully.'
        ]);
    }
}
