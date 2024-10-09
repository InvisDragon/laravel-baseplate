<?php

namespace InvisibleDragon\LaravelBaseplate\Data;

use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\Tags\Tag;

class TagData extends Data
{

    public function __construct(
        public string $id,
        #[WithTransformer(LanguageDictToStringTransformer::class)]
        public mixed $name,
        public ?string $type
    )
    {

    }

    public static function getTags(Data $input, $cls) {
        $tags = Tag::query()->join('taggables', 'tag_id', '=', 'id')
            ->where('taggable_type', $cls)
            ->where('taggable_id', $input->id);
        return TagData::collect($tags->get());
    }

}
