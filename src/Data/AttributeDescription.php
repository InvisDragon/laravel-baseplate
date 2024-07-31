<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Attribute;

#[Attribute]
class AttributeDescription
{
    public function __construct(public string $description) {}
}
