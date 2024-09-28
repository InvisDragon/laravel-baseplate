<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

trait ActivityLogNotesRoute
{

    public static function routesActivityLogNotesRoute(string $prefix)
    {
        Route::post($prefix . '/{id}/activity', [static::class, 'postActivityNote']);
    }

    public function postActivityNote(Request $request, string $id)
    {

        if(!$request->post('comment')) {
            return new JsonResponse([ 'comment' => 'Required' ], 422);
        }

        $obj = $this->getSingleObject($request);
        activity()
            ->performedOn($obj)
            ->by(auth()->user())
            ->event('note')
            ->log($request->post('comment'));

        return new JsonResponse([ 'status' => 'ok' ]);

    }

}
