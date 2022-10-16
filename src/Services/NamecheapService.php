<?php

namespace Spork\Basement\Services;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

// This service is meant to update namecheap domains to use cloudflare servers
class NamecheapService
{
    public const NAMECHEAP_URL = 'https://api.namecheap.com/xml.response';

    public function __construct(
        public string $apiUser,
        public string $apiKey,
        public string $username,
        public string $clientIp,
        public string $nameservers
    ) {
    }

    public function getDomains(int $limit = 10, int $page = 1)
    {
        $response = Http::get(static::NAMECHEAP_URL.'?'.http_build_query([
            'ApiUser' => $this->apiUser,
            'ApiKey' => $this->apiKey,
            'UserName' => $this->username,
            'Command' => 'namecheap.domains.getList',
            'ClientIp' => $this->clientIp,
            'PageSize' => $limit,
            'Page' => $page,
        ]));

        $domainResponse = json_decode(json_encode(simplexml_load_string($response->body())));

        if (isset($domainResponse->Errors->Error)) {
            throw new \Exception($domainResponse->Errors->Error);
        }

        $domains = array_map(fn ($obj) => $obj->{'@attributes'}, $domainResponse->CommandResponse->DomainGetListResult->Domain ?? []);

        return new LengthAwarePaginator(
            array_map(fn ($domain) => [
                'id' => (int) $domain->ID,
                'domain' => $domain->Name,
                'is_expired' => $domain->IsExpired === 'true',
                'is_locked' => $domain->IsLocked === 'true',
                'is_auto_renewing' => $domain->AutoRenew === 'true',
                'is_premium' => $domain->IsPremium === 'true',
                'is_namecheap_dns' => $domain->IsOurDNS === 'true',
                'has_whois_guard' => $domain->WhoisGuard === 'ENABLED',
                // 'original' => (array) $domain,
                'created_at' => Carbon::parse($domain->Created),
                'expires_at' => Carbon::parse($domain->Expires),
            ], $domains),
            $domainResponse->CommandResponse->Paging->TotalItems ?? 0,
            $limit,
            $page
        );
    }

    public function getDomainNs(string $domain)
    {
        [$domainPart, $tld] = explode('.', $domain);

        $response = Http::get(static::NAMECHEAP_URL.'?'.http_build_query([
            'ApiUser' => $this->apiUser,
            'ApiKey' => $this->apiKey,
            'UserName' => $this->username,
            'Command' => 'namecheap.domains.dns.getList',
            'ClientIp' => $this->clientIp,
            'SLD' => $domainPart,
            'TLD' => $tld,
        ]));

        $domainResponse = json_decode(json_encode(simplexml_load_string($response->body())));
        if (isset($domainResponse->Errors->Error)) {
            throw new \Exception($domainResponse->Errors->Error);
        }

        try {
            return $domainResponse->CommandResponse->DomainDNSGetListResult->Nameserver;
        } catch (\Throwable $e) {
            dd($domainResponse);
        }
    }

    public function updateDomainNs(string $domain, array $nameservers)
    {
        [$domainPart, $tld] = explode('.', $domain);

        $response = Http::get(static::NAMECHEAP_URL.'?'.http_build_query([
            'ApiUser' => $this->apiUser,
            'ApiKey' => $this->apiKey,
            'UserName' => $this->username,
            'Command' => 'namecheap.domains.dns.setCustom',
            'ClientIp' => $this->clientIp,
            'SLD' => $domainPart,
            'TLD' => $tld,
            'Nameservers' => implode(',', $nameservers),
        ]));

        $domainResponse = json_decode(json_encode(simplexml_load_string($response->body())));

        if (isset($domainResponse->Errors->Error)) {
            throw new \Exception($domainResponse->Errors->Error);
        }

        return $nameservers;
    }
}
