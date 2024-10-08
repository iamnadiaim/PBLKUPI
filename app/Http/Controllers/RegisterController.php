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
        "nama" => "required",
        "alamat" => "required",
        "nama_usaha" => "required",
        "no_telepon" => "required|min:12|max:13",
        "email" => "required|email|unique:users",
        "password" => "required",
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
            ->causedBy($user) // Link the activity to the created user
            ->event('Register')
            ->log('User registered');
        });

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    } catch (\Throwable $th) {
        throw $th;
        return redirect('/register')->with('error', 'Registrasi gagal! Silakan coba lagi.');
    }
}

}
