<?php

namespace Spork\Basement\Actions;

use Spork\Core\Contracts\ActionInterface;

class SyncNamecheapDNSAction implements ActionInterface 
{
    public function __invoke()
    {
        request()->validate([
            'domains' => 'required|array'
        ]);
        
        return request()->get('domains');
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

    public function validation(array $rules): void
    {
        dd($rules);
        request()->validation($rules);
    }
}