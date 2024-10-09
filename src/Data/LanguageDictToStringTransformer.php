<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class LanguageDictToStringTransformer implements Transformer
{

    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        if(is_array($value)) {
            return $value['en'];
        }
        return $value;
    }
}
