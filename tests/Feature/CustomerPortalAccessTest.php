<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class CustomerPortalAccessTest extends TestCase
{
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
