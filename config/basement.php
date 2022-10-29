<?php

use Spork\Basement\Services\NamecheapService;

return [
    'enabled' => true,
    'middleware' => [],
    'default' => [
        'domain_service' => env('BASEMENT_DEFAULT_DOMAIN_SERVICE', NamecheapService::class),
    ],
];
