<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('custom-local-stylesheet', asset('css/filament.css')),
            Css::make('fontawesomepro-stylesheet', asset('css/fontawesomepro.min.css')),
            //Css::make('login-modern-stylesheet', asset('css/login-modern.css')),
            //Css::make('login-fix-stylesheet', asset('css/login-fix.css')),
        ]);


        FilamentAsset::register([
            Js::make('fontawesomepro-script', asset('js/fontawesomepro.min.js'))->loadedOnRequest(),
            //Js::make('login-modern-script', asset('js/login-modern.js'))->loadedOnRequest(),
        ]);
    }
}
