<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use InvisibleDragon\LaravelBaseplate\Data\TagData;

trait TagRoutes
{

    public static function routesTagRoutes(string $prefix)
    {
        Route::get($prefix . '/{id}/tags', [static::class, 'listTags']);
        Route::post($prefix . '/{id}/tags', [ static::class, 'addTag' ]);
    }

    public function addTag(Request $request)
    {

        if(!$request->post('tag')) {
            return new JsonResponse([ 'tag' => 'Required' ], 422);
        }
        $obj = $this->getSingleObject($request);
        $obj->attachTag( $request->post('tag') );
        return new JsonResponse([ 'status' => 'ok' ]);

    }

    public function listTags(Request $request, string $id)
    {

        $obj = $this->getSingleObject($request);
        return TagData::collect( $obj->tags );

    }

}
