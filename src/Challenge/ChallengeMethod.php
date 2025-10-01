<?php

namespace InvisibleDragon\LaravelBaseplate\Challenge;

abstract class ChallengeMethod {

    public static function get_methods() {
        return [
            'dummy' => ChallengeMethodDummy::class,
            'gr3' => ChallengeMethodGR3::class,
        ];
    }

    public static function get_method() {
        return static::get_methods()[ config('baseplate.challenge_method', 'dummy') ];
    }

    public static abstract function output_scripts();

    public static abstract function check_token(string $token);

}
