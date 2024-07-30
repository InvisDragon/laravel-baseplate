<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use InvisibleDragon\LaravelBaseplate\Data\DataPropertyJSON;
use ReflectionClass;
use ReflectionProperty;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\DataContainer;

/**
 * Base class that interacts with Spatie's Data classes to provide a basis for CRUD operations
 */
abstract class DataController {

    /**
     * Return the class name of the Data subclass you wish to use
     *
     * @template T
     * @return class-string<T>
     */
    public abstract function getDataClass();

    /**
     * Return the query of which you wish to base this controller around. This
     * should include restrictions such as what the current request context/user is
     * allowed to see
     */
    public abstract function getQuery(Request $request);

    /**
     * Return a list of the items
     */
    public function index( Request $request ) {
        return $this->getDataClass()::collect( $this->getQuery($request) );
    }

    /**
     * Describe this resource
     */
    public function describe() {
        $cls =  $this->getDataClass();
        // TODO: figure out how to use the cache if available
        $dataClass = DataContainer::get()->dataClassFactory()->build(new ReflectionClass($cls));
        // Now we transform dataClass into a nice JSON object
        return new JsonResponse( collect($dataClass->properties)
            ->filter(function($input){
                // Don't want to include ID in this as we don't want forms with ID at the top
                return $input->name != 'id';
            })
            ->map(function($input){
                return (new DataPropertyJSON($input))->toArray();
            })->toArray() );
    }


    public static function resourceRoutes( string $prefix ) {
        Route::get( $prefix . '/describe', [ static::class, 'describe' ] );
        Route::resource($prefix, static::class);
    }

}
