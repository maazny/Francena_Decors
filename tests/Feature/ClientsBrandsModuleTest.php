<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientsBrandsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_clients_and_brands_routes_are_available(): void
    {
        $response = $this->get('/clients-brands');

        $response->assertOk();
        $response->assertSee('Clients & Brands');
        $this->assertTrue(route('clients-brands.index') !== null);
        $this->assertTrue(route('admin.client-brands.index') !== null);
    }
}
