<?php

namespace InvisibleDragon\LaravelBaseplate\Auth;

use Illuminate\Http\Request;
use InvisibleDragon\LaravelBaseplate\Models\UserAuthMethod;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialUserEntity;

class AuthMethodPasskey extends AuthMethod {

    public static function get_label() : string {
        return __('Using a passkey');
    }

    public static function get_auth_html() : string {
        $challenge = random_bytes(32);
        request()->session()->put('_passkey_challenge', $challenge);
        $publicKeyCredentialRequestOptions =
            PublicKeyCredentialRequestOptions::create(
                $challenge, // Challenge
                rpId: request()->getHost()
            );

        $serializer = static::get_serializer();
        $jsonObject = $serializer->serialize(
            $publicKeyCredentialRequestOptions,
            'json',
            [
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true, // Highly recommended!
                JsonEncode::OPTIONS => JSON_THROW_ON_ERROR, // Optional
            ]
        );

        return view('baseplate::baseplate.auth_method.passkey', [
            'requestOptions' => $jsonObject,
        ])->render();
    }

    public static function authenticate(Request $request, $user) {

        $serializer = static::get_serializer();

        // Get the data submitted
        $data = $request->post('passkey');
        $submittedCredential = $serializer->deserialize($data, PublicKeyCredential::class, 'json');
        if (!$submittedCredential->response instanceof AuthenticatorAssertionResponse) {
            abort('invalid request');
        }

        $challenge = request()->session()->get('_passkey_challenge');
        $publicKeyCredentialRequestOptions =
            PublicKeyCredentialRequestOptions::create(
                $challenge, // Challenge
                rpId: request()->getHost()
            );

        $key_id = base64_encode( $submittedCredential->rawId );
        $userAuthMethod = UserAuthMethod::query()->where('key_id', $key_id )->get()[0];
        if(!$userAuthMethod) return false;


        $csmFactory = new CeremonyStepManagerFactory();
        $requestCSM = $csmFactory->requestCeremony();
        $authenticatorAssertionResponseValidator = AuthenticatorAssertionResponseValidator::create(
            $requestCSM
        );

        // Load the key from database
        $publicKeyCredentialSource = $serializer->deserialize($userAuthMethod->key, PublicKeyCredentialSource::class, 'json');

        // Perform ceremony and see if we match
        $publicKeyCredentialSource = $authenticatorAssertionResponseValidator->check(
            $publicKeyCredentialSource,
            $submittedCredential->response,
            $publicKeyCredentialRequestOptions,
            $request->getHost(),
            $userAuthMethod->user_id
        );

        // Login as this user id!
        return $userAuthMethod->user_id;

    }

    static function get_serializer() {
        $attestationStatementSupportManager = AttestationStatementSupportManager::create();
        $attestationStatementSupportManager->add(NoneAttestationStatementSupport::create());
        $factory = new WebauthnSerializerFactory($attestationStatementSupportManager);
        return $factory->create();
    }

    public static function setup(Request $request) {

        $user = $request->user();

        $serializer = static::get_serializer();

        // RP Entity i.e. the application
        $rpEntity = PublicKeyCredentialRpEntity::create(
            config('app.name'),
            $request->getHost(),
            null
        );
        $userEntity = PublicKeyCredentialUserEntity::create(
            $user->name,
            $user->id,
            $user->name,
            null
        );

        // Challenge
        if($request->post()) {
            $challenge = request()->session()->get('_passkey_challenge');
        } else {
            $challenge = random_bytes(32);
            request()->session()->put('_passkey_challenge', $challenge);
        }

        $publicKeyCredentialCreationOptions =
            PublicKeyCredentialCreationOptions::create(
                $rpEntity,
                $userEntity,
                $challenge,
                authenticatorSelection: new AuthenticatorSelectionCriteria('platform'),
            )
        ;

        if($request->post()) {

            $passkey = $request->post('passkey');
            $publicKeyCredential = $serializer->deserialize(
                $passkey,
                PublicKeyCredential::class,
                'json'
            );
            if ($publicKeyCredential->response instanceof AuthenticatorAttestationResponse) {
                $csmFactory = new CeremonyStepManagerFactory();
                $creationCSM = $csmFactory->creationCeremony();
                $authenticatorAttestationResponseValidator = AuthenticatorAttestationResponseValidator::create(
                    $creationCSM
                );
                $publicKeyCredentialSource = $authenticatorAttestationResponseValidator->check(
                    $publicKeyCredential->response,
                    $publicKeyCredentialCreationOptions,
                    $request->getHost()
                );

                $data = $serializer->serialize($publicKeyCredentialSource, 'json');

                $authMethod = new UserAuthMethod([
                    'user_id' => $user->id,
                    'type' => 'passkey',
                    'key_id' => base64_encode( $publicKeyCredential->rawId ),
                    'key' => $data,
                ]);
                $authMethod->save();

                // TOOD: Should notify user of new passkey added!

                // Cleanup!
                request()->session()->remove('_passkey_challenge');
            } else {
                abort(400, 'Invalid Passkey');
            }


            return redirect()->route('security-options-form')->with('security-message', __('Passkey has been set successfully'));

        }

        $jsonObject = $serializer->serialize(
            $publicKeyCredentialCreationOptions,
            'json',
            [
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true, // Highly recommended!
                JsonEncode::OPTIONS => JSON_THROW_ON_ERROR, // Optional
            ]
        );

        return view('baseplate::baseplate.auth_method.passkey_setup', [ 'credentialOptions' => $jsonObject ]);
    }

}
