<?php

namespace Spork\Basement\Repositories;

use Spork\Basement\Contracts\Services\CloudflareDomainServiceContract;
use Spork\Basement\Contracts\Services\NamecheapDomainServiceContract;

class NamecheapRepository
{
    public function __construct(
        public NamecheapDomainServiceContract $namecheapService,
        public CloudflareDomainServiceContract $cloudflareDomain
    ) {
    }

    public function migrateAllDnsToCloudflare(array $dnsServers)
    {
        $domains = $this->namecheapService->getDomains(100);

        foreach ($domains as $domain) {
            $domainDnsServers = $this->namecheapService->getDomainNs($domain['domain']);

            if (empty(array_diff($domainDnsServers, $dnsServers))) {
                continue;
            }

            return $this->namecheapService->updateDomainNs($domain['domain'], $dnsServers);
        }
    }
}
