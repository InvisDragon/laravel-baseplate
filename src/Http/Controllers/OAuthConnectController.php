<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

abstract class OAuthConnectController {

    public static function resourceRoutes(string $prefix)
    {
        Route::get( $prefix . '/connect', [ static::class, 'connect' ] )->name(
            'oauth-' . static::getServiceName() . '-connect'
        );
    }

    public abstract function getAuthorizationUrl() : string;
    public abstract static function getServiceName() : string;

    public function getClientId() {
        return config('services.' . static::getServiceName() . '.key');
    }

    public function getRedirectUrl() {
        return route( 'oauth-' . static::getServiceName() . '-connect' );
    }

    public function connect()
    {

        $uri = url()->query( $this->getAuthorizationUrl(), [
            'redirect_uri' => $this->getRedirectUrl(),
            'client_id' => $this->getClientId(),
            'response_type' => 'code',
        ] );
        return response()->redirectTo( $uri );

    }



}
