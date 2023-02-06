<?php

namespace Lancodev\LunarPaypal;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Lancodev\LunarPaypal\Commands\LunarPaypalCommand;

class LunarPaypalServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('lunar-paypal')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_lunar-paypal_table')
            ->hasCommand(LunarPaypalCommand::class);
    }
}
