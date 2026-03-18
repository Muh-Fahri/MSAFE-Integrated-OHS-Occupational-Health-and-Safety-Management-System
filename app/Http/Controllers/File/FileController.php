<?php

namespace App\Http\Controllers\File;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show($path)
    {
        // HAPUS tanda komentar dd di bawah ini untuk mengetes apakah route masuk ke sini
        // dd("Route Berhasil Masuk! Mencari path: " . $path);

        // Karena kamu pakai ->store('incidents', 'public'),
        // maka Laravel menyimpannya di disk PUBLIC
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path);
        }

        // Jika tidak ketemu di public, cek di local (private)
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->response($path);
        }

        abort(404, "File tidak ditemukan secara fisik di storage. Path: " . $path);
    }
}
