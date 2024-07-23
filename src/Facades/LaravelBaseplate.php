<?php

namespace InvisibleDragon\LaravelBaseplate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \InvisibleDragon\LaravelBaseplate\LaravelBaseplate
 */
class LaravelBaseplate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \InvisibleDragon\LaravelBaseplate\LaravelBaseplate::class;
    }
}
