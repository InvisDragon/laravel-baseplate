<?php

namespace InvisibleDragon\LaravelBaseplate\Challenge;

use Illuminate\Support\Facades\Http;

/**
 * Do nothing challenge method
 */
class ChallengeMethodDummy extends ChallengeMethod {

    public static function output_scripts() {

        // Do nothing here :)

    }

    public static function check_token(string $token) {

        // We had no token :)
        return true;

    }

}
