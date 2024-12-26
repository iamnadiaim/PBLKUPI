<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usaha;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'errors' => $validator->errors()
            ], 400));
        }

        if (Auth::attempt($request->only(['email','password']))) {

            $user = auth()->user();
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json(["token" => $token,"user" => $user], 200);
        }

        throw new HttpResponseException(response()->json([
            "error" => [
                "message" => "Email atau password Salah"
            ]
        ], 401));
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
    
                User::create($userAttributes);
            });
    
            return response()->json([ 'message' => 'Register berhasil']);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([ 'message' => 'Register gagal! coba lagi']);
        }
    }
        public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Token Berhasil Dihapus']);
    }
}   