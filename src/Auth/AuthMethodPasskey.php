<?php

namespace InvisibleDragon\LaravelBaseplate\Auth;

use Illuminate\Http\Request;
use InvisibleDragon\LaravelBaseplate\Models\UserAuthMethod;

class AuthMethodPasskey extends AuthMethod {

    public static function get_label() : string {
        return __('Using a passkey');
    }

    public static function get_auth_html() : string {
        return view('baseplate::baseplate.auth_method.passkey')->render();
    }

    public static function authenticate(Request $request, $user) {
        // TODO
        return false;
    }

    static function get_challenge() {
        return static::base64_string(random_bytes(32));
    }

    static function base64_string($input) {
        return \rtrim(\strtr(\base64_encode($input), '+/', '-_'), '=');
        return '=?BINARY?B?' . base64_encode( $input ) . '?=';
    }

    public static function setup(Request $request) {

        $user = $request->user();

        if($request->post()) {

            $passkey = json_decode($request->post('passkey'), true);
            $authMethod = new UserAuthMethod([
                'user_id' => $user->id,
                'type' => 'passkey',
                'key_id' => $passkey['id'],
                'key' => json_encode($passkey),
            ]);
            $authMethod->save();

            // TOOD: Should notify user of new passkey added!

            return redirect()->route('security-options-form')->with('security-message', __('Passkey has been set successfully'));

        }


        $credentialOptions = [
            'challenge' => static::get_challenge(),
            'rp' => [
              'name' => config('app.name'),
              'id' => $request->getHost(),
            ],
            'user' => [
              'id' => static::base64_string( $user->id ),
              'name' => $user->name,
              'displayName' => $user->name,
            ],
            'pubKeyCredParams' => [[
              'alg' => -7, 'type' => 'public-key'
            ],[
              'alg' => -257, 'type' => 'public-key'
            ]],
            'excludeCredentials' => [],
            'authenticatorSelection' => [
              'authenticatorAttachment' => 'platform',
              'requireResidentKey' => true,
            ]
        ];

        return view('baseplate::baseplate.auth_method.passkey_setup', [ 'credentialOptions' => $credentialOptions ]);
    }

}
