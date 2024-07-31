<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Attribute;

#[Attribute]
class InputType
{
    public function __construct(public string $inputType) {}
}
