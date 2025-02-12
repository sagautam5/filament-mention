<?php

namespace Asmit\Mention;

use Asmit\Mention\Forms\Components\RichMention;
use Filament\Forms\Components\Livewire;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MentionServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('asmit-mention')
            ->hasViews();
    }
    public function packageBooted()
    {
        FilamentAsset::register([
            AlpineComponent::make(id:'tributejs', path: __DIR__.'/../dist/tributejs.js'),
            Css::make(id:'asmit-mention', path: __DIR__.'/../dist/asmit-mention.css'),
        ], package: 'asmit/mention');
    }
}
