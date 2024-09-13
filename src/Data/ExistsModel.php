<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Attribute;
use Spatie\LaravelData\Attributes\Validation\Exists;

#[Attribute]
class ExistsModel extends Exists
{
    public function __construct(public $api_method, ...$params)
    {
        parent::__construct(...$params);
    }
}
