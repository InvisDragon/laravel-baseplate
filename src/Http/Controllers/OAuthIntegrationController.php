<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

/**
 * This class provides an integration, for tenanted applications
 */
abstract class OAuthIntegrationController extends OAuthConnectController {

    public function storeToken($token) {
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

}
