<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use InvisibleDragon\LaravelBaseplate\Data\DataPropertyJSON;
use ReflectionClass;
use Spatie\LaravelData\Support\DataContainer;

/**
 * Base class that interacts with Spatie's Data classes to provide a basis for CRUD operations
 */
abstract class DataController
{
    /**
     * Return the class name of the Data subclass you wish to use
     *
     * @template T
     *
     * @return class-string<T>
     */
    abstract public function getDataClass();

    /**
     * Return the class name of the Model subclass you wish to use
     *
     * @template T
     *
     * @return class-string<T>
     */
    abstract public function getModelClass();

    /**
     * Return the query of which you wish to base this controller around. This
     * should include restrictions such as what the current request context/user is
     * allowed to see
     */
    abstract public function getQuery(Request $request);

    /**
     * Return a list of the items
     */
    public function index(Request $request)
    {
        return $this->getDataClass()::collect($this->getQuery($request)->cursorPaginate());
    }

    /**
     * Prepare to store an item
     */
    public function store(Request $request)
    {
        $input = $request->input();
        $input['id'] = 0; // Gets around validation issue
        $obj = $this->getDataClass()::validateAndCreate($input);
        $model = new ($this->getModelClass())($obj->except('id')->toArray());
        $model->save();

        return $this->getDataClass()::from($model);
    }

    /**
     * Return a single instance of the query (assumes id is primary key)
     */
    public function show(Request $request, string $id)
    {
        $query = $this->getQuery($request)->where('id', $id);
        $obj = $query->first();
        if ($obj) {
            return $this->getDataClass()::from($obj);
        } else {
            abort(404);
        }
    }

    /**
     * Describe this resource with a JSON representation which can be used to
     * make basic CRUD forms on the frontend
     */
    public function describe()
    {
        $cls = $this->getDataClass();
        // TODO: figure out how to use the cache if available
        $dataClass = DataContainer::get()->dataClassFactory()->build(new ReflectionClass($cls));

        // Now we transform dataClass into a nice JSON object
        return new JsonResponse(collect($dataClass->properties)
            ->filter(function ($input) {
                // Don't want to include ID in this as we don't want forms with ID at the top
                return $input->name != 'id';
            })
            ->map(function ($input) {
                return (new DataPropertyJSON($input))->toArray();
            })->toArray());
    }

    public static function resourceRoutes(string $prefix)
    {
        Route::get($prefix.'/describe', [static::class, 'describe']);
        Route::resource($prefix, static::class);
    }
}
