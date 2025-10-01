<?php

namespace InvisibleDragon\LaravelBaseplate\Challenge;

use Illuminate\Support\Facades\Http;

/**
 * Google Recaptcha v3
 */
class ChallengeMethodGR3 extends ChallengeMethod {

    public static function get_key() {
        return config('baseplate.google_recpatcha_key');
    }

    public static function get_secret() {
        return config('baseplate.google_recpatcha_secret');
    }

    public static function get_threshold() {
        return intval(config('baseplate.google_recpatcha_threshold', 0.6));
    }

    public static function output_scripts() {

        $key = static::get_key();
        echo '<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render=' . $key . '"></script>';

        ?><script type="text/javascript">
        document.querySelectorAll(".challenge_form").forEach(function(form){
            let hasC = false;
            form.addEventListener("submit", function(e){
                if(!hasC) e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute( <?= json_encode($key); ?>, {action: "submit"}).then(function(token) {
                        hasC = true;
                        let input = document.createElement("input");
                        input.setAttribute("type", "hidden");
                        input.setAttribute("name", "challenge");
                        input.value = token;
                        form.appendChild(input);
                        form.submit();
                    });
                });
            });
        });
        </script><?php

    }

    public static function check_token(string $token) {

        $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => static::get_secret(),
            'response' => $token,
        ]);
        if($resp->status() == 200) {
            $score = $resp->json('score');
            if($score == null) return false;
            return $score >= static::get_threshold();
        }
        return false;


    }

}
