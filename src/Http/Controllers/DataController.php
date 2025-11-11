<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use InvisibleDragon\LaravelBaseplate\Data\DataDescriber;
use InvisibleDragon\LaravelBaseplate\Data\ExistingValue;

/**
 * Base class that interacts with Spatie's Data classes to provide a basis for CRUD operations
 */
abstract class DataController extends ReadOnlyDataController
{

    /**
     * Return a single instance of the query (assumes id is primary key)
     */
    public function show(Request $request)
    {
        $obj = $this->getSingleObject($request);
        Gate::authorize('show', $obj);
        if ($obj) {
            if($request->get('context') === 'edit') {
                return ($this->getEditDataClass())::from($obj);
            }
            return $this->getSingleDataClass($obj);
        } else {
            abort(404);
        }
    }

    /**
     * Returns the data class, if different, for editing data
     */
    public function getEditDataClass() {
        return $this->getDataClass();
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
        Gate::authorize('create', $this->getModelClass());
        $input = $request->input();
        $input = $this->setRequestDefaultData($input, $request, true);
        $obj = $this->getEditDataClass()::validateAndCreate($input);
        $model = $this->createModel(array_merge(
            $obj->except('id')->toArray(),
            $request->route()->parameters() // Automatically include items like company_id
        ));
        $model->save();
        $model->refresh();

        return $this->getSingleDataClass($model);
    }

    /**
     * Deletes an object by it's id
     */
    public function destroy(Request $request)
    {
        $obj = $this->getSingleObject($request);
        Gate::authorize('delete', $obj);
        if ($obj) {
            if($obj->delete()) {
                return new JsonResponse(['status' => 'deleted']);
            } else {
                abort(503);
            }
        } else {
            abort(404);
        }
    }

    public function getDescription($cls) {
        return DataDescriber::describe($cls);
    }

    /**
     * Describe this resource with a JSON representation which can be used to
     * make basic CRUD forms on the frontend
     */
    public function describe()
    {
        $cls = $this->getEditDataClass();

        return new JsonResponse($this->getDescription($cls));
    }

    public function setRequestDefaultData( array $input, Request $request, bool $creating ) {
        $input['id'] = 0; // Gets around validation issue
        return $input;
    }

    public function update(Request $request)
    {
        $obj = $this->getSingleObject($request);
        Gate::authorize('update', $obj);
        if ($obj) {
            ExistingValue::$existing_value = $obj;
            $input = $this->getDataClass()::from($obj)->toArray();
            $input = array_merge($input, $request->input());
            $input = $this->setRequestDefaultData($input, $request, false);
            $newParams = $this->getDataClass()::validateAndCreate($input)->toArray();
            $obj->fill($newParams);
            $obj->save();
            ExistingValue::$existing_value = null;
            return $this->getSingleDataClass($obj);
        } else {
            abort(404);
        }
    }

    public static function resourceRoutes(string $prefix)
    {
        Route::get($prefix.'/describe', [static::class, 'describe']);
        parent::resourceRoutes($prefix);
    }

}
