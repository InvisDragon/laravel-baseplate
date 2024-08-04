<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use InvisibleDragon\LaravelBaseplate\Data\DataDescriber;
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
     * Return the data class used to view this specific object if you wish to override this
     *
     * @param $obj object Model object
     * @return class-string<T>
     */
    public function getSingleDataClass($obj) {
        return $this->getDataClass()::from($obj);
    }

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
     * Save model with arguments
     *
     * @param $args array Finalized arguments to save for model
     * @return mixed Model with saved arguments
     */
    public function createModel($args) {
        return new ($this->getModelClass())($args);
    }

    /**
     * Prepare to store an item
     */
    public function store(Request $request)
    {
        $input = $request->input();
        $input['id'] = 0; // Gets around validation issue
        $obj = $this->getDataClass()::validateAndCreate($input);
        $model = $this->createModel(array_merge(
            $obj->except('id')->toArray(),
            $request->route()->parameters() // Automatically include items like company_id
        ));
        $model->save();

        return $this->getDataClass()::from($model);
    }

    public function getSingleObject(Request $request)
    {
        $params = array_values( $request->route()->parameters() );
        $id = array_pop( $params );
        $query = $this->getQuery($request)->where('id', $id);
        $obj = $query->first();
        return $obj;
    }

    /**
     * Return a single instance of the query (assumes id is primary key)
     */
    public function show(Request $request)
    {
        $obj = $this->getSingleObject($request);
        if ($obj) {
            return $this->getSingleDataClass($obj);
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

        return new JsonResponse(DataDescriber::describe($cls));
    }

    public function update(Request $request)
    {
        $obj = $this->getSingleObject($request);
        if ($obj) {
            $input = $request->input();
            $input['id'] = 0; // Gets around validation issue
            $newParams = $this->getDataClass()::validateAndCreate($input)->toArray();
            $obj->fill($newParams);
            $obj->save();
            return $this->getSingleDataClass($obj);
        } else {
            abort(404);
        }
    }

    public static function resourceRoutes(string $prefix)
    {
        Route::get($prefix.'/describe', [static::class, 'describe']);
        Route::resource($prefix, static::class);
    }
}
