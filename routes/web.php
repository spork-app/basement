<?php

use Illuminate\Support\Facades\Route;
use Spork\Basement\Services\NamecheapService;

// Route::get('/', 'Controller@method');

Route::get('link-server', function () {
    return view('basement::link-server', [
        'user' => auth()->id()
     ]);
});
 
Route::get('link', function () {
    return 'value';
})->name('servers.create-no-credential');

Route::get('domains/namecheap', function(NamecheapService $service) {
    return $service->getDomains(100, 1);
});