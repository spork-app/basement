<?php

namespace Spork\Basement\Contracts\Services;

interface CloudflareDomainServiceContract extends DomainServiceContract
{
    public function hasEmailRouting(string $domain): bool;
}
