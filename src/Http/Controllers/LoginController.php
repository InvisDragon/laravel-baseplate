<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    public function login(Request $request)
    {

        if ($request->post()) {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::guard('web')->attempt($credentials)) {
                $request->session()->regenerate();
                $request->session()->put('auth.password_confirmed_at', time());
                return redirect()->intended('/');
            }

            return back()->withErrors([
                'email' => __('The provided credentials do not match our records.'),
            ])->onlyInput('email');
        }

        return view('baseplate::baseplate.layout');

    }
}
