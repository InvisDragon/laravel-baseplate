<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\Models\Activity;

trait ActivityLogRoute
{

    public static function routesActivityLogRoute(string $prefix)
    {
        Route::get($prefix . '/{id}/activity', [static::class, 'listActivity']);
    }

    public function listActivity(Request $request, string $id)
    {

        $obj = $this->getSingleObject($request);
        $query = Activity::forSubject($obj)->orderBy('created_at', 'DESC');

        return $query->paginate();

    }

}
