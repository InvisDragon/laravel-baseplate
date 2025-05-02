<?php

namespace InvisibleDragon\LaravelBaseplate\Auth;

use Illuminate\Http\Request;

abstract class AuthMethod {

    public static function get_methods() {
        return [
            'password' => AuthMethodPassword::class,
        ];
    }

    public abstract static function get_label() : string;

    public abstract static function get_auth_html() : string;

    public abstract static function authenticate(Request $request, $user);

}
