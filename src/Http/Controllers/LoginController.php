<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

        return view('baseplate::baseplate.login');

    }

    public function reset_password( Request $request, string $token ) {

        if ($request->post()) {

            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => ['required'],
                'password_confirmation' => ['required', 'same:password'],
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('security-message', __($status))
                : back()->withErrors(['email' => [__($status)]]);

        }

        return view('baseplate::baseplate.reset_password', [ 'token' => $token, 'email' => $request->email ]);

    }

    public function forgot_password( Request $request ) {

        if(!config('baseplate.allow_forgot_password')) {
            return abort(400, 'Forgot Password is not allowed');
        }

        if ($request->post()) {

            $request->validate([
                'email' => ['required', 'email'],
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);

        }

        return view('baseplate::baseplate.forgot_password');

    }

    public function logout() {

        Auth::logout();
        return redirect('/');

    }

}
