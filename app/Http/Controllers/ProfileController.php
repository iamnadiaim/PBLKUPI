<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usaha;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        
        return view('user.edit', [
            'user' => $user,
            'nama' => $user->nama,
            'email' => $user->email,
            'namaUsaha' => $user->usaha->nama_usaha ,
            'alamat' => $user->usaha->alamat  ,
            'noTelepon' => $user->no_telepon,
            'role' => $user->role->nama_role , // Handle null untuk role yang tidak ada
        ]);
    }

    public function index()
    {
        $user = auth()->user();

        return view('user.index', [
            'user' => $user,
            'nama' => $user->nama,
            'email' => $user->email,
            'namaUsaha' => $user->usaha->nama_usaha,
            'alamat' => $user->usaha->alamat,
            'noTelepon' => $user->no_telepon,
            'role' => $user->role->nama_role ,
        ]);

    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama' => 'required|string|min:2|max:60',
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/',
                'unique:users,email,' . $user->id,
            ],
            'no_telepon' => 'required|string|min:7|max:13',
            'img_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // Batas ukuran maksimal 5 MB
            'nama_usaha' => 'required|string|min:2|max:60',
            'alamat' => 'required|string|min:2|max:60',
        ], [
            'nama.min' => 'Nama harus diisi minimal 2 karakter.',
            'nama.max' => 'Nama harus diisi maksimal 60 karakter.',
            'nama_usaha.min' => 'Nama usaha harus diisi minimal 2 karakter.',
            'nama_usaha.max' => 'Nama usaha harus diisi maksimal 60 karakter.',
            'alamat.min' => 'Alamat harus diisi minimal 2 karakter.',
            'alamat.max' => 'Alamat harus diisi maksimal 60 karakter.',
            'img_profile.max' => 'Foto profil tidak boleh lebih dari 5 MB.', // Pesan error khusus
            'email.unique' => 'Email sudah terdaftar.',
            'email.regex' => 'Format email tidak sesuai. Email harus menggunakan domain @gmail.com.',
        ]);

        // Handle profile image upload
        if ($request->hasFile('img_profile') && $request->file('img_profile')->isValid()) {
            // Hapus gambar lama jika ada
            if ($user->img_profile && Storage::exists('public/' . $user->img_profile)) {
                Storage::delete('public/' . $user->img_profile);
            }
            
            $profilePath = $request->file('img_profile')->store('images', 'public');
            $user->img_profile = $profilePath;
        }

        // Update user information
        $user->update($request->only('nama', 'email', 'no_telepon'));

        // Update usaha information
        if ($user->usaha) {
            $user->usaha->update($request->only('nama_usaha', 'alamat'));
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    public function password()
    {
        return view('user.password');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            "old_password" => "required",
            "new_password" => "required|min:8",
            "confirm_password" => "required|same:new_password"
        ]);

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->with('error', 'Password lama tidak sesuai');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profile')->with('success', 'Password berhasil diubah');
    }
}