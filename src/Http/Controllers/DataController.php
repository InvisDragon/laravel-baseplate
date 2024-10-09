<?php

namespace InvisibleDragon\LaravelBaseplate\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use InvisibleDragon\LaravelBaseplate\Data\DataDescriber;

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

    public function getEditDataClass() {
        return $this->getDataClass();
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
        return $this->getDataClass()::collect($this->getQuery($request)->paginate());
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
        $obj = $this->getEditDataClass()::validateAndCreate($input);
        $model = $this->createModel(array_merge(
            $obj->except('id')->toArray(),
            $request->route()->parameters() // Automatically include items like company_id
        ));
        $model->save();

        return $this->getSingleDataClass($model);
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
            if($request->get('context') === 'edit') {
                return ($this->getEditDataClass())::from($obj);
            }
            return $this->getSingleDataClass($obj);
        } else {
            abort(404);
        }
    }

    /**
     * Deletes an object by it's id
     */
    public function destroy(Request $request)
    {
        $obj = $this->getSingleObject($request);
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

    /**
     * Describe this resource with a JSON representation which can be used to
     * make basic CRUD forms on the frontend
     */
    public function describe()
    {
        $cls = $this->getEditDataClass();

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
        static::resourceRootTraits($prefix);
    }

    /**
     * Code pattern from Laravel @
     * https://github.com/laravel/framework/blob/7aabb896018f462bab291c50295ce613c8d840f3/src/Illuminate/Database/Eloquent/Model.php#L308
     * @param string $prefix
     * @return void
     */
    protected static function resourceRootTraits(string $prefix)
    {
        $class = static::class;
        $booted = [];

        foreach (class_uses_recursive($class) as $trait) {
            $method = 'routes'.class_basename($trait);

            if (method_exists($class, $method) && ! in_array($method, $booted)) {
                forward_static_call([$class, $method], $prefix);

                $booted[] = $method;
            }

        }
    }

}
