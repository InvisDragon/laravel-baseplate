<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Support\DataProperty;

class DataPropertyJSON
{
    public function __construct(public DataProperty $property) {}

    public const DEFAULT_TYPES_INPUT_TYPES = [
        'DateTime' => 'datetime-local',
        'int' => 'number',
        'array' => 'repeater',
    ];

    public function toArray()
    {

        $description = null;
        $inputType = 'text';
        $args = [];
        $default = '';

        $type = $this->property->type->type->name;
        if(array_key_exists($type, static::DEFAULT_TYPES_INPUT_TYPES)) {
            $inputType = static::DEFAULT_TYPES_INPUT_TYPES[ $type ];
        }

        if($type == 'array') {
            $innerClass = $this->property->type->dataClass;
            if($innerClass) {
                $args['items'] = [
                    'type' => 'object',
                    'fields' => DataDescriber::describe($innerClass),
                ];
            }
        }

        foreach ($this->property->attributes as $attribute) {
            if ($attribute instanceof AttributeDescription) {
                $description = $attribute->description;
            } elseif ($attribute instanceof InputType) {
                $inputType = $attribute->inputType;
            } elseif( $attribute instanceof ExistsModel) {
                $inputType = 'foreign_id';
                if(is_callable($attribute->api_method)) {
                    $args['apiMethod'] = call_user_func($attribute->api_method);
                } else {
                    $args['apiMethod'] = $attribute->api_method;
                }
            } elseif( $attribute instanceof DefaultValue ) {
                $default = call_user_func( $attribute->default );
            }
        }

        return [
            'name' => ucwords(str_replace('_', ' ', $this->property->name)),
            'type' => $type,
            'description' => $description,
            'inputType' => $inputType,
            'readOnly' => $this->property->isReadonly,
            'default' => $default,
            ...$args
        ];

    }
}
