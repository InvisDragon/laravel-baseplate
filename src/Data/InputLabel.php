<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Attribute;

#[Attribute]
class InputLabel
{
    /**
     * @param string|callable $label
     */
    public function __construct(public $label)
    {}
}
