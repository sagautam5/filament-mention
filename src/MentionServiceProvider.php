<?php

namespace Asmit\Mention;

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
            Js::make(id:'tributejs', path: 'https://cdnjs.cloudflare.com/ajax/libs/tributejs/3.3.2/tribute.min.js'),
            Css::make(id:'tributejs', path: 'https://cdnjs.cloudflare.com/ajax/libs/tributejs/3.3.2/tribute.min.css'),
        ], package: 'asmit-mention');
    }
}
