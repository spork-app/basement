<?php

namespace Spork\Basement;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Spork\Basement\Services\NamecheapService;
use Spork\Core\Spork;

class SporkServiceProvider extends RouteServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/basement-scripts', 'basement');
    }

    public function register()
    {
        Spork::addFeature('Basement', 'ServerIcon', '/basement', 'tool', ['domains']);
        Spork::actions('Basement', __DIR__.'/Actions');

        if (config('spork.basement.enabled')) {
            Route::middleware($this->app->make('config')->get('spork.basement.middleware', ['auth:sanctum']))
                ->prefix('/api/basement')
                ->group(__DIR__.'/../routes/web.php');
        }

        $this->app->when(NamecheapService::class)
            ->needs('$apiUser')
            ->give($this->app->make('config')->get('services.namecheap.apiUser'));
        $this->app->when(NamecheapService::class)
            ->needs('$apiKey')
            ->give($this->app->make('config')->get('services.namecheap.apiKey'));
        $this->app->when(NamecheapService::class)
            ->needs('$username')
            ->give($this->app->make('config')->get('services.namecheap.apiUser'));
        $this->app->when(NamecheapService::class)
            ->needs('$clientIp')
            ->give($this->app->make('config')->get('services.namecheap.clientIp'));
        $this->app->when(NamecheapService::class)
            ->needs('$nameservers')
            ->give($this->app->make('config')->get('services.namecheap.nameservers'));
    }
}
