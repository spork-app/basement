<?php

namespace Spork\Basement\Repositories;

use Spork\Basement\Contracts\Services\CloudflareDomainServiceContract;

class CloudflareRepository
{
    public function __construct(
        public CloudflareDomainServiceContract $service
    ) {
    }

    public function removeEmailRelatedDns()
    {
        // magic to fetch the dns records from cloudflare
    }

    public function addDnsRecordsBulk(array $dnsRecords)
    {
        // dns records should be arrays of data that match how cloudflare wants dns records.
    }

    public function addGoogleDns()
    {
        $this->addDnsRecordsBulk([
            ['TXT', 'austinkregel', '_dmark.www'],
        ]);
    }

    public function enableCloudflareFeatures()
    {
        // types: development_mode,email_obfuscation,always_use_https,hotlink_protection,minify,polish,http2,automatic_https_rewrites,
        // PATCH zones/:zone_identifier/settings/:type
        // body: {"value": "on"}
    }
}
