<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class LoginController extends Controller
{
    public function index() : View
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required',]);

        if (Auth::attempt($validate)) { 

            $request->session()->regenerate(); 
            return redirect()->intended('/dashboard');
        }

        return back()->with("errorLogin", "Email Atau Password Salah");
    }
}
