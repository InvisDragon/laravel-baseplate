<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

/**
 * This class provides an integration, for tenanted applications
 */
abstract class OAuthIntegrationController extends OAuthConnectController {

    public static function storeToken($token) {
        $settings = tenant()->integrations ?: [];
        $settings[ static::getServiceName() ] = $token;
        tenant()->integrations = $settings;
        tenant()->save();
    }

    public static function getToken() {
        return tenant()->integrations[ static::getServiceName() ] ?: [];
    }

    public static function getFieldSettings($label) {
        return [
            'type' => 'integration',
            'inputType' => 'integration',
            'label' => $label,
            'name' => $label,
            'connectUrl' => static::getRedirectUrl(),
            'hasConnected' => !empty(static::getToken()),
        ];
    }

    public static function refreshToken() {
        $token = static::getToken();
        $resp = Http::withBasicAuth( static::getClientId(), static::getClientSecret() )->post(
            static::getTokenUrl(),
            [
                'grant_type' => 'refresh_token',
                'refresh_token' => $token['refresh_token']
            ]
        );
        $resp->throw();
        $token = array_merge( $token, $resp->json() );
        static::storeToken($token);
        return $token['access_token'];
    }

    public static function getHttpClient() {
        $token = static::getToken();
        return Http::withToken( $token['access_token'] )->retry(2, 0, function (\Exception $exception, PendingRequest $request) {
            if (! $exception instanceof RequestException || $exception->response->status() !== 401) {
                return false;
            }
            $request->withToken( static::refreshToken() );
            return true;
        });
    }

}
