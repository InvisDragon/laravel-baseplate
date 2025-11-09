<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use InvisibleDragon\LaravelBaseplate\Auth\AuthMethod;
use InvisibleDragon\LaravelBaseplate\Challenge\ChallengeMethod;

class LoginController
{
    public function login(Request $request)
    {

        if ($request->post()) {

            // Challenge
            if(!ChallengeMethod::check_request($request) ) {
                return back()->withErrors([
                    'email' => __('Request could not be authenticated'),
                ]);
            }

            $methods = AuthMethod::get_methods();
            $method = $methods[ $request->post('auth_method') ];

            $user = null;
            if($request->post('email')) {
                // Try to provide the user to authenticate against if we can
                $provider = auth()->createUserProvider(config('auth.guards.web.provider'));
                $user = $provider->retrieveByCredentials(['email' => $request->post('email')]);
                if(!$user) {
                    return back()->withErrors([
                        'email' => __('The provided credentials do not match our records.'),
                    ])->onlyInput('email');
                }
            }

            $r = $method::authenticate( $request, $user );
            if($r === true && $user !== null) {
                // Authenticate $user if we just get told yes
                Auth::login($user);
                return redirect()->intended('/');
            } if(is_int($r) && $r >= 0) {

                // We might get returned an integer to login as
                $provider = auth()->createUserProvider(config('auth.guards.web.provider'));
                $user = $provider->retrieveById($r);
                Auth::login($user);
                return redirect()->intended('/');

            } else {

                return back()->withErrors([
                    'email' => __('Account could not be authenticated'),
                ])->onlyInput('email');

            }

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

    public function confirm_registration(Request $request, string $token) {
        Password::reset(
            [ 'email' => $request->get('email'), 'token' => $token, 'password' => null, ],
            function ($user, $password) {

                $user->is_active = true;
                $user->email_verified_at = new \DateTime();
                $user->save();

            }
        );
        return redirect()->route('login')->with('security-message', __('Your account has been confirmed'));
    }

}
