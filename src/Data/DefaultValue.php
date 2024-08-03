<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Attribute;

#[Attribute]
class DefaultValue
{
    /**
     * @param callable $default
     */
    public function __construct(public $default)
    {

    }
}
