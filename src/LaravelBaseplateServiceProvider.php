<?php

namespace InvisibleDragon\LaravelBaseplate;

use Illuminate\Support\Facades\Route;
use InvisibleDragon\LaravelBaseplate\Commands\LaravelBaseplateCommand;
use InvisibleDragon\LaravelBaseplate\Http\Controllers\LoginController;
use InvisibleDragon\LaravelBaseplate\Http\Controllers\SecurityOptionsController;
use InvisibleDragon\LaravelBaseplate\Http\Controllers\SuperUserModeController;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
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
            ->hasMigration('create_user_auth_methods_table')
            ->hasCommand(LaravelBaseplateCommand::class);
    }

    public function packageRegistered()
    {
        // Create views
        $middleware = ['web'];

        // If this class exists, good chance we are a multi-tenant application
        if (class_exists('\Stancl\Tenancy\Middleware\InitializeTenancyByDomain')) {
            $middleware[] = InitializeTenancyByDomain::class;
            $middleware[] = PreventAccessFromCentralDomains::class;
        }

        Route::prefix('/b/')->middleware($middleware)->group(function () {

            Route::get('/login', [LoginController::class, 'login'])->name('login');
            Route::post('/login', [LoginController::class, 'login'])->name('login');

            Route::get('/forgot-password', [LoginController::class, 'forgot_password'])->name('password.request');
            Route::post('/forgot-password', [LoginController::class, 'forgot_password'])->name('password.email');
            Route::get('/reset-password/{token}', [LoginController::class, 'reset_password'])->name('password.reset');
            Route::post('/reset-password/{token}', [LoginController::class, 'reset_password'])->name('password.reset');

            Route::middleware(['auth'])->group(function() {
                Route::get('/security-options', [SecurityOptionsController::class, 'form'])->name('security-options-form');
                Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
                Route::get('/security-options/change-my-password', [SecurityOptionsController::class, 'change_password'])
                    ->name('security-options-change-password');
                Route::post('/security-options/change-my-password', [SecurityOptionsController::class, 'change_password'])
                    ->name('security-options-change-password');

                Route::get('/security-options/register-auth-method/{auth_method}', [ SecurityOptionsController::class, 'register_auth_method' ])
                    ->name('security-options-register-auth-method');
                Route::post('/security-options/register-auth-method/{auth_method}', [ SecurityOptionsController::class, 'register_auth_method' ])
                ->name('security-options-register-auth-method');

                Route::get('/super-user-auth', [ SuperUserModeController::class, 'auth' ])->name('super-user-auth');
                Route::post('/super-user-auth', [ SuperUserModeController::class, 'auth' ])->name('super-user-auth');
            });

        });

    }
}
