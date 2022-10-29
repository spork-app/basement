<?php

use Illuminate\Support\Facades\Route;
use Spork\Basement\Contracts\Services\NamecheapDomainServiceContract;

// Route::get('/', 'Controller@method');

Route::get('domains/namecheap', function (NamecheapDomainServiceContract $service) {
    return $service->getDomains(
        request()->get('limit', 100),
        request()->get('page', 1)
    );
});
