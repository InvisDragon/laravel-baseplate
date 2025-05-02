<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Http\Request;
use InvisibleDragon\LaravelBaseplate\Auth\AuthMethod;
use InvisibleDragon\LaravelBaseplate\Auth\SuperUserMode;

class SuperUserModeController {

    public function auth(Request $request) {

        if($request->post()) {

            $methods = AuthMethod::get_methods();
            $method = $methods[ $request->post('auth_method') ];
            $r = $method::authenticate( $request, $request->user() );
            if(!$r) {
                return $r;
            }
            SuperUserMode::setSuperMode($request);
            // TODO: Secure this!
            return redirect($_GET['return_to']);
        }

        return view('baseplate::baseplate.superuser-login');

    }

}
