<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Support\DataAttributesCollection;
use Spatie\LaravelData\Support\DataProperty;

class DataPropertyJSON
{
    public function __construct(public DataProperty $property) {}

    public const DEFAULT_TYPES_INPUT_TYPES = [
        'DateTime' => 'datetime-local',
        'int' => 'number',
        'array' => 'repeater',
        'bool' => 'bool',
    ];

    protected $description, $inputType, $args, $default, $type;

    public function processAttribute( DataAttributesCollection $attributes)
    {
        if ($attribute = $attributes->first( AttributeDescription::class ) ) {
            $this->description = $attribute->description;
        }
        if ($attribute = $attributes->first( InputType::class ) ) {
            $this->inputType = $attribute->inputType;
            if(is_callable($this->inputType)) {
                $this->inputType = call_user_func($this->inputType);
                if(is_array($this->inputType)) { // Allow for array return to include additional arguments
                    $this->args = array_merge($this->args, $this->inputType);
                    $this->inputType = $this->inputType['inputType'];
                }
            }
        }
        if( $attribute = $attributes->first( ExistsModel::class ) ) {
            $this->inputType = 'foreign_id';
            if(is_callable($attribute->api_method)) {
                $this->args['apiMethod'] = call_user_func($attribute->api_method);
            } else {
                $this->args['apiMethod'] = $attribute->api_method;
            }
        }
        if( $attribute = $attributes->first( DefaultValue::class ) ) {
            $this->default = call_user_func( $attribute->default );
        }
        if( $attribute = $attributes->first( Image::class ) ) {
            $this->inputType = 'image';
            $this->type = 'file';
        }
    }

    public function toArray()
    {

        $this->description = null;
        $this->inputType = 'text';
        $this->args = [];
        $this->default = '';

        $this->type = $this->property->type->type->name;
        if(array_key_exists($this->type, static::DEFAULT_TYPES_INPUT_TYPES)) {
            $this->inputType = static::DEFAULT_TYPES_INPUT_TYPES[ $this->type ];
        }

        if($this->type == 'array') {
            $innerClass = $this->property->type->dataClass;
            if($this->property->type->iterableItemType === 'string') {
                $this->inputType = 'string[]';
            } elseif($innerClass) {
                $this->args['items'] = [
                    'type' => 'object',
                    'fields' => DataDescriber::describe($innerClass),
                ];
            }
        } elseif(enum_exists($this->type)) {
            // Handle enums
            $this->inputType = 'enum';
            $this->args['enum'] = collect($this->type::cases())->map(function($val) {
                return [
                    'key' => $val->name,
                    'value' => $val->value
                ];
            });
            $this->type = 'string';
        } elseif($this->type == 'bool') {
            $this->default = false;
        }

        $this->processAttribute($this->property->attributes);

        return [
            'name' => ucwords(str_replace('_', ' ', $this->property->name)),
            'type' => $this->type,
            'description' => $this->description,
            'inputType' => $this->inputType,
            'readOnly' => $this->property->isReadonly,
            'default' => $this->default,
            ...$this->args
        ];

    }
}
