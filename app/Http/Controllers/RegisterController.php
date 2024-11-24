<?php

namespace App\Http\Controllers;

use App\Models\Usaha;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function index(){
        return view('auth.signup');
    }

    public function register(Request $request)
{
    $request->validate([
        "nama" => "required|string|min:3|max:60",
        "alamat" => "required|string|min:4|max:60",
        "nama_usaha" => "required|string|min:3|max:60",
        "no_telepon" => "required|digits_between:10,13", // Membatasi antara 10-13 digit
        "email" => [
            "required",
            "email",
            "regex:/^[^@]+@[^@]+\.[^@\.]+$/", // Format email harus valid dan mengandung "."
            "unique:users,email",
        ],
        "password" => "required|string|min:8", // Minimal 8 karakter
    ], [
        'nama.required' => 'The nama field is required.',
        'nama.min' => 'The nama field must be at least 3 characters.',
        'nama.max' => 'The nama field must not be greater than 60 characters.',
        'alamat.required' => 'The alamat field is required.',
        'alamat.min' => 'The alamat field must be at least 4 characters.',
        'alamat.max' => 'The alamat field must not be greater than 60 characters.',
        'nama_usaha.required' => 'The nama usaha field is required.',
        'nama_usaha.min' => 'The nama usaha field must be at least 3 characters.',
        'nama_usaha.max' => 'The nama usaha field must not be greater than 60 characters.',
        'no_telepon.required' => 'The no telepon field is required.',
        'no_telepon.digits_between' => [
            'min' => 'The no telepon field must be at least 10 characters.',
            'max' => 'The no telepon field must not be greater than 13 characters.',
        ],
        'email.required' => 'The email field is required.',
        'email.email' => 'The email field must be a valid email address.',
        'email.regex' => 'The email field must be a valid email address',
        'email.unique' => 'The email has already been taken.',
        'password.required' => 'The password field is required.',
        'password.min' => 'The password field must be at least 8 characters.',
    ]);

    try {
        DB::transaction(function () use ($request) {
            // Membuat usaha
            $usaha = Usaha::create($request->only('nama_usaha', 'alamat'));

            // Membuat user
            $userAttributes = $request->except('nama_usaha', 'alamat');
            $userAttributes['id_role'] = 1;
            $userAttributes['id_usaha'] = $usaha->id;

            $user = User::create($userAttributes);
            
            activity()
                ->causedBy($user)
                ->event('Register')
                ->log('User registered');
        });

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    } catch (\Throwable $th) {
        return redirect('/register')->with('error', 'Registrasi gagal! Silakan coba lagi.');
    }
}

    
}
