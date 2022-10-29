<?php

namespace Spork\Basement\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Spork\Basement\Contracts\Services\CloudflareDomainServiceContract;

class CloudflareDomainService implements CloudflareDomainServiceContract
{
    public const CLOUDFLARE_URL = 'https://api.cloudflare.com/client/v4/';

    public function __construct(
        public string $apiKey,
        public string $email
    ) {
    }

    public function getDomains(int $limit = 10, int $page = 1): LengthAwarePaginator
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'x-auth-email' => $this->email,
        ])->get(static::CLOUDFLARE_URL.'/zones', [
            'per_page' => $limit,
            'page' => $page,
            'status' => 'active',
            'match' => 'all',
        ]);

        $data = $response->json();

        return new LengthAwarePaginator(
            $data['result'],
            $data['result_info']['total_count'],
            $data['result_info']['per_page']
        );
    }

    public function deleteDnsRecord(string $domain, string $dnsRecordId): void
    {
        Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'x-auth-email' => $this->email,
        ])->delete(static::CLOUDFLARE_URL."/zones/$domain/dns_records/$dnsRecordId");
    }

    public function getDomainNs(string $domain): array
    {
        // TODO: Implement getDomainNs() method.
    }

    public function updateDomainNs(string $domain, array $nameservers): array
    {
        // TODO: Implement updateDomainNs() method.
    }

    public function getDns(string $domain, ?string $type = null, int $limit = 10, int $page = 1): LengthAwarePaginator
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'x-auth-email' => $this->email,
        ])->get(static::CLOUDFLARE_URL."/zones/$domain/dns_records", array_merge([
            'per_page' => $limit,
            'page' => $page,
        ], isset($type) ? compact('type') : []));

        $data = $response->json();

        return new LengthAwarePaginator($data['result'], $data['result_info']['count'], $data['result_info']['per_page']);
    }

    public function createDnsRecord(string $domain, array $dnsRecordArray): void
    {
        Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'x-auth-email' => $this->email,
        ])->post(static::CLOUDFLARE_URL."/zones/$domain/dns_records", $dnsRecordArray);
    }

    public function hasEmailRouting(string $domain): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'x-auth-email' => $this->email,
            'content-type' => 'application/json',
        ])->get(static::CLOUDFLARE_URL."/zones/$domain/email/routing");

        return $response->json()['result']['enabled'];
    }
}
