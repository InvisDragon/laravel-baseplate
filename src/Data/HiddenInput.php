<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Attribute;

#[Attribute]
class HiddenInput extends InputType {

    public function __construct() {
        parent::__construct('hidden');
    }

}
