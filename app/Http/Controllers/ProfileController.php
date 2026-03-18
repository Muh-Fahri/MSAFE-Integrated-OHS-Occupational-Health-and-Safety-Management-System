<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function show()
    {
        $user = Auth::user();

        return view('profile.show', compact('user'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'name' => 'required|string|max:100',
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'remove_photo' => 'nullable|boolean', // BARU: untuk mark hapus foto
            'current_password' => 'required_with:password|nullable',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'username.unique' => 'Username sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
            'current_password.required_with' => 'Password lama wajib diisi jika ingin mengganti password.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Validasi password lama jika user mau ganti password
        if ($request->filled('password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama salah.'])->withInput();
            }
        }

        // BARU: Handle hapus foto jika di-mark untuk dihapus
        if ($request->remove_photo) {
            if ($user->photo && file_exists(public_path('uploads/users/'.$user->photo))) {
                unlink(public_path('uploads/users/'.$user->photo));
            }
            $user->photo = null;
        }

        // Handle photo upload (hanya jika ada file baru yang diupload)
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo && file_exists(public_path('uploads/users/'.$user->photo))) {
                unlink(public_path('uploads/users/'.$user->photo));
            }

            // Upload foto baru
            $file = $request->file('photo');
            $filename = time().'_'.$user->username.'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/users'), $filename);
            $user->photo = $filename;
        }

        // Update data user
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Your profile has been successfully updated!');
    }

    /**
     * Delete photo
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->photo && file_exists(public_path('uploads/users/'.$user->photo))) {
            unlink(public_path('uploads/users/'.$user->photo));
        }

        $user->photo = null;
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Foto berhasil dihapus!');
    }
}
