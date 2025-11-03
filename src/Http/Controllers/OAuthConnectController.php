<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/**
 * This class provides the baselines to do an OAuth 2.0 connection
 * within your application
 */
abstract class OAuthConnectController {

    public static function resourceRoutes(string $prefix)
    {
        Route::get( $prefix . '/connect', [ static::class, 'connect' ] )->name(
            'oauth-' . static::getServiceName() . '-connect'
        );
    }

    public abstract static function getAuthorizationUrl() : string;
    public abstract static function getTokenUrl() : string;
    public abstract static function getServiceName() : string;
    public abstract static function storeToken($token);

    public static function getClientId() {
        return config('services.' . static::getServiceName() . '.key');
    }

    public static function getClientSecret() {
        return config('services.' . static::getServiceName() . '.secret');
    }

    public static function getRedirectUrl() {
        return route( 'oauth-' . static::getServiceName() . '-connect' );
    }

    public function complete() {
        return 'Account Connected';
    }

    public function connect(Request $request)
    {

        if($request->get('code')) {
            $resp = Http::withBasicAuth( static::getClientId(), static::getClientSecret() )
                ->post( static::getTokenUrl(), [
                    'grant_type' => 'authorization_code',
                    'code' => $request->get('code'),
                    'redirect_uri' => static::getRedirectUrl(),
                ] );
            if($resp->successful()) {
                $body = $resp->json();
                static::storeToken($body);
                return $this->complete();
            } else {
                throw new Exception( 'Authentication Failed' );
            }
        }

        $uri = url()->query( static::getAuthorizationUrl(), [
            'redirect_uri' => static::getRedirectUrl(),
            'client_id' => static::getClientId(),
            'response_type' => 'code',
        ] );
        return response()->redirectTo( $uri );

    }



}
