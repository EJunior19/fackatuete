<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Forzar que <x-icon> use nuestro propio componente
        Blade::component('icon', \App\View\Components\Icon::class);
    }
}
