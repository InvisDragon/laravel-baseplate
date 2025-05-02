<?php

namespace InvisibleDragon\LaravelBaseplate\Auth;

use Illuminate\Http\Request;

/**
 * This class outlines the super user mode where the currently
 * authenticated user has re-authenticated themselves
 * recently
 */
class SuperUserMode {

    const SUPER_USER_SESSION_KEY = '_lb_super_user_active';
    const SUPER_USER_ACTIVE_TIME = 60 * 5; // 5 minutes since last usage

    public static function isInSuperUserMode( Request $request ) {
        $lastActive = $request->session()->get( static::SUPER_USER_SESSION_KEY );
        if(!$lastActive) return false;
        $minSession = now()->subSeconds(static::SUPER_USER_ACTIVE_TIME);
        $isInSuperUserMode = $lastActive > $minSession;
        if($isInSuperUserMode) {
            static::setSuperMode($request);
        }
        return $isInSuperUserMode;
    }

    public static function requestSuperUser( Request $request ) {
        return redirect()->route('super-user-auth', ['return_to' => url()->current() ]);
    }

    public static function setSuperMode( Request $request ) {
        $request->session()->put( static::SUPER_USER_SESSION_KEY, now() );
    }

}
