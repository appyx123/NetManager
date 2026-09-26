<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPortalAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_root_redirects_to_dashboard(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($customer)->get('/');

        $response->assertRedirect('/client/dashboard');
    }
}
