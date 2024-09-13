<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Uncastable;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class ImageCast implements Cast
{
    /**
     * @param  string|callable  $filename
     * @return void
     */
    public function __construct(public $filename) {}

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_string($value)) {
            if (stripos($value, 'data:') === 0) {
                // Store and such
                $filename = $this->filename;
                if (is_callable($filename)) {
                    $filename = call_user_func($filename);
                }

                $fileContents = substr($value, strpos($value, ',') + 1);
                $fileContents = str_replace(' ', '+', $fileContents);
                $fileContents = base64_decode($fileContents);

                if (stripos($filename, '.') === false) {
                    $matches = [];
                    if (preg_match('/image\/([a-z]+)/', $value, $matches)) {
                        $filename .= '.'.$matches[1];
                    }
                }

                $ret = Storage::disk('public')->put($filename, $fileContents);

                if ($ret) {
                    return Storage::disk('public')->url($filename);
                }
            }
        }

        return Uncastable::create();
    }
}
