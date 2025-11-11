<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Spatie\LaravelData\Support\Validation\References\ExternalReference;

class ExistingValue implements ExternalReference
{

    public static $existing_value = null;

    public function __construct(
        public $name = null
    ) {
    }

    public function getValue(): mixed
    {
        if(!static::$existing_value) return null;
        return $this->name ?
            data_get(static::$existing_value, $this->name) : static::$existing_value;
    }
}
