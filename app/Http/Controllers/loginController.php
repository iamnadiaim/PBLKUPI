<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function index() : View
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($validate)) { 

            activity()
            ->causedBy(auth()->user())
            ->log('user has been login');
         
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->with("errorLogin", "Email Atau Password Salah");
    }
}

