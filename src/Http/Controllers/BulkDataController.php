<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

abstract class BulkDataController {

    public static function resourceRoutes(string $prefix)
    {
        Route::resource($prefix, static::class);
    }

    public abstract function getControllers() : array;

    public function index(Request $request)
    {
        $controllers = $this->getControllers();
        return response()->json( collect($controllers)->map(function ($controller) use($request) {

            if(!is_array($controller)) {
                if(class_exists($controller)) {
                    $controller = [$controller, 'index']; // Default
                } else {
                    $controller = [ static::class, $controller ];
                }
            }
            $instance = new $controller[0]();
            $method = $controller[1];
            return $instance->$method( $request );

        }) );
    }

}
