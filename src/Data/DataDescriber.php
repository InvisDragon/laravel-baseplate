<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Spatie\LaravelData\Support\DataContainer;

class DataDescriber
{
    public static function describe($cls)
    {
        // TODO: figure out how to use the cache if available
        $dataClass = DataContainer::get()->dataClassFactory()->build(new \ReflectionClass($cls));

        // Now we transform dataClass into a nice JSON object
        return collect($dataClass->properties)
            ->filter(function ($input) {
                // Don't want to include ID in this as we don't want forms with ID at the top
                return $input->name != 'id';
            })
            ->map(function ($input) {
                return (new DataPropertyJSON($input))->toArray();
            })->toArray();
    }
}
