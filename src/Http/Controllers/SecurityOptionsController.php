<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvisibleDragon\LaravelBaseplate\Auth\AuthMethod;
use InvisibleDragon\LaravelBaseplate\Auth\SuperUserMode;

class SecurityOptionsController
{

    public function form() {

        return view('baseplate::baseplate.security_options');

    }

    public function change_password(Request $request) {

        if ($request->post()) {
            $credentials = $request->validate([
                'current_password' => ['required'],
                'new_password' => ['required'],
                'new_password_confirm' => ['required', 'same:new_password'],
            ]);

            $user = auth()->user();

            if(!Hash::check( $credentials['current_password'], $user->getAuthPassword() )) {
                return back()->withErrors([
                    'current_password' => __('The provided credentials do not match our records.'),
                ]);
            }

            $user->forceFill([
                'password' => Hash::make($credentials['new_password'])
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));

            return redirect()->route('security-options-form')->with('security-message', __('Password has been set successfully'));

        }

        if(!auth()->user()->getAuthPassword()) {
            return abort( 400, __( 'Account does not have a password which can be changed') );
        }

        return view('baseplate::baseplate.change_password');

    }

    public function register_auth_method(Request $request, string $auth_method)
    {

        if(!SuperUserMode::isInSuperUserMode($request)) {
            return SuperUserMode::requestSuperUser($request);
        }

        $auth_cls = AuthMethod::get_methods()[ $auth_method ];
        if(!$auth_cls) abort(400, 'Invalid auth method');

        return $auth_cls::setup($request);

    }

}
