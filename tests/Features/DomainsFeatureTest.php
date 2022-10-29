<?php

namespace Spork\Basement\Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Spork\Basement\Contracts\Services\NamecheapDomainServiceContract;
use Spork\Basement\Services\NamecheapService;
use Spork\Basement\Tests\TestCase;

class DomainsFeatureTest extends TestCase
{
    public function test_the_application_returns_a_successful_response()
    {
        $mockService = Mockery::mock(NamecheapService::class);

        $this->app->bind(NamecheapDomainServiceContract::class, fn () => $mockService);

        $this->app->when(NamecheapService::class)
            ->needs('$apiUser')
            ->give('fake-user');
        $this->app->when(NamecheapService::class)
            ->needs('$apiKey')
            ->give('fake-key');
        $this->app->when(NamecheapService::class)
            ->needs('$username')
            ->give('fakeuser');
        $this->app->when(NamecheapService::class)
            ->needs('$clientIp')
            ->give('fake-ip');
        $this->app->when(NamecheapService::class)
            ->needs('$nameservers')
            ->give('ns.fake.tools');

        $mockService->shouldReceive('getDomains')
            ->once()
            ->with(100, 1)
            ->andReturn(new LengthAwarePaginator([], 0, 100, 1));

        $response = $this->get('/api/basement/domains/namecheap');
        $response->assertStatus(200);
    }

    public function test_the_application_handles_page_and_limit_returns_a_response()
    {
        $mockService = Mockery::mock(NamecheapService::class);

        $this->app->bind(NamecheapDomainServiceContract::class, fn () => $mockService);

        $this->app->when(NamecheapService::class)
            ->needs('$apiUser')
            ->give('fake-user');
        $this->app->when(NamecheapService::class)
            ->needs('$apiKey')
            ->give('fake-key');
        $this->app->when(NamecheapService::class)
            ->needs('$username')
            ->give('fakeuser');
        $this->app->when(NamecheapService::class)
            ->needs('$clientIp')
            ->give('fake-ip');
        $this->app->when(NamecheapService::class)
            ->needs('$nameservers')
            ->give('ns.fake.tools');

        $mockService->shouldReceive('getDomains')
            ->once()
            ->with(1, 51)
            ->andReturn(new LengthAwarePaginator([], 0, 100, 1));

        $response = $this->get('/api/basement/domains/namecheap?page=51&limit=1');
        $response->assertStatus(200);
    }
}
