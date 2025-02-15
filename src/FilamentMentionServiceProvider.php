<?php

namespace Asmit\FilamentMention;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMentionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('asmit-filament-mention')
            ->hasViews()
            ->hasConfigFile(['filament-mention'])
            ->hasInstallCommand(function (InstallCommand $installCommand) {
                $installCommand->startWith(function (InstallCommand $command) {
                    $command->info('Hello, and welcome to my great new package!');
                    $command->newLine(1);
                })
                    ->publishConfigFile()
                    ->endWith(function (InstallCommand $installCommand) {
                        $installCommand->newLine(1);
                        $installCommand->info('========================================================================================================');
                        $installCommand->info("Get ready to breathe easy! Our package has just saved you from a day's worth of headaches and hassle.");
                        $installCommand->info('========================================================================================================');
                    });
            });
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            AlpineComponent::make(id: 'asmit-filament-mention', path: __DIR__.'/../dist/tributejs.js'),
            Css::make(id: 'asmit-filament-mention', path: __DIR__.'/../resources/css/asmit-filament-mention.css')->loadedOnRequest(),
        ], package: 'asmit/filament-mention');
    }
}
