<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Attribute;

#[Attribute]
class ExistsModel extends Exists {

    public function __construct(public $api_method, ...$params) {
        parent::__construct(...$params);
    }


}
