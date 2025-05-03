<?php

namespace InvisibleDragon\LaravelBaseplate\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthMethodPassword extends AuthMethod {

    public static function get_label() : string {
        return __('Using a password');
    }

    public static function get_auth_html() : string {
        return view('baseplate::baseplate.auth_method.password')->render();
    }

    public static function authenticate(Request $request, $user) {

        $data = $request->validate([
            'password' => 'required'
        ]);

        if(!$user->getAuthPassword()) {
            return false;
        }

        if(!Hash::check( $data['password'], $user->getAuthPassword() )) {
            return back()->withErrors([
                'current_password' => __('The provided credentials do not match our records.'),
            ]);
        }

        return true;

    }

    public static function setup(Request $request) {
        // todo
    }

}
