<?php

namespace InvisibleDragon\LaravelBaseplate;

use InvisibleDragon\LaravelBaseplate\Commands\LaravelBaseplateCommand;
use InvisibleDragon\LaravelBaseplate\Http\Controllers\LoginController;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

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
            ->hasAssets()
            ->hasMigration('create_laravel_baseplate_table')
            ->hasCommand(LaravelBaseplateCommand::class);
    }

    public function packageRegistered()
    {
        // Create views
        $middleware = ['web'];

        // If this class exists, good chance we are a multi-tenant application
        if(class_exists('\Stancl\Tenancy\Middleware\InitializeTenancyByDomain')) {
            $middleware[] = InitializeTenancyByDomain::class;
            $middleware[] = PreventAccessFromCentralDomains::class;
        }

        Route::prefix('/b/')->middleware($middleware)->group(function () {

            Route::get( '/login', [ LoginController::class, 'login' ] )->name('login');
            Route::post( '/login', [ LoginController::class, 'login' ] )->name('login');

        });

    }
}
