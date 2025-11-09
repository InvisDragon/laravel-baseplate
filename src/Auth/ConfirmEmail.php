<?php

namespace InvisibleDragon\LaravelBaseplate\Auth;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Password;
use InvisibleDragon\LaravelBaseplate\Notifications\ConfirmUserNotification;

class ConfirmEmail {

    /**
     * This function sends a confirmation email to a new user
     */
    public static function sendConfirmEmail( CanResetPassword $user ) {

        $token = Password::sendResetLink([ 'id' => $user->id ], function($user, $token) {
            $user->notify( new ConfirmUserNotification($token) );
        });

    }

}
