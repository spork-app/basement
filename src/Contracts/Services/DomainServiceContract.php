<?php

namespace Spork\Basement\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DomainServiceContract
{
    public function getDomains(int $limit = 10, int $page = 1): LengthAwarePaginator;
    public function getDns(string $domain, string $type = 'A', int $limit = 10, int $page =1): LengthAwarePaginator;
    public function deleteDnsRecord(string $domain, string $dnsRecordId): void;
    public function createDnsRecord(string $domain, array $dnsRecordArray): void;
    public function getDomainNs(string $domain): array;
    public function updateDomainNs(string $domain, array $nameservers): array;
}
