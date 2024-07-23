<?php

namespace InvisibleDragon\LaravelBaseplate\Commands;

use Illuminate\Console\Command;

class LaravelBaseplateCommand extends Command
{
    public $signature = 'laravel-baseplate';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
