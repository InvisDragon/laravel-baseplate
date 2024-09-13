<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Attribute;

#[Attribute]
class InputType
{
    /**
     * @param  string|callable  $inputType
     */
    public function __construct(public $inputType) {}
}
