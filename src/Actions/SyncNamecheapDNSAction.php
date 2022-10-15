<?php

namespace Spork\Basement\Actions;

use Spork\Basement\Services\NamecheapService;
use Spork\Core\Contracts\ActionInterface;

class SyncNamecheapDNSAction implements ActionInterface 
{
    public function __invoke(NamecheapService $service)
    {
        request()->validate([
            'domains' => 'required|array',
            'nameservers' => 'required'
        ]);

        $domains = request()->get('domains');

        $nameservers = explode(',', request()->get('nameservers', ''));

        foreach ($domains as $domain) {
            $service->updateDomainNs($domain, $nameservers);
        }
        return 'OK';
    }

    public function name(): string
    {
        return 'Set Namecheap DNS';
    }

    public function route(): string
    {
        return '/api/basement/namecheap';
    }

    public function tags(): array
    {
        return [
            'basement',
            'domains'
        ];
    }
}