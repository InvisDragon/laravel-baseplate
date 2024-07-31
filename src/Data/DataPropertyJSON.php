<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Spatie\LaravelData\Support\DataProperty;

class DataPropertyJSON
{
    public function __construct(public DataProperty $property) {}

    public function toArray()
    {

        $description = null;
        $inputType = 'text';

        foreach ($this->property->attributes as $attribute) {
            if ($attribute instanceof AttributeDescription) {
                // Make sure it's translated at the final step!
                // TODO: Check this is detected by Laravel as guessing not
                $description = __($attribute->description);
            } elseif ($attribute instanceof InputType) {
                $inputType = $attribute->inputType;
            }
        }

        return [
            'name' => ucwords($this->property->name),
            'type' => $this->property->type->type->name,
            'description' => $description,
            'inputType' => $inputType,
            'readOnly' => $this->property->isReadonly,
        ];

    }
}
