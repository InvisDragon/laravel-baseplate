<?php

namespace InvisibleDragon\LaravelBaseplate;

use InvisibleDragon\LaravelBaseplate\Commands\LaravelBaseplateCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelBaseplateServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-baseplate')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_baseplate_table')
            ->hasCommand(LaravelBaseplateCommand::class);
    }

    public function packageRegistered()
    {
        // Create views
    }
}
