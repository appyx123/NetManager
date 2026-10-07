<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Memastikan pendaftaran mandiri (public registration) dinonaktifkan pada sistem ISP tertutup dan dialihkan ke login.
     */
    public function test_registration_screen_is_disabled_and_redirects_to_login(): void
    {
        $response = $this->get('/register');

        $response->assertRedirect(route('login'));
    }

    /**
     * Memastikan endpoint POST pendaftaran mandiri menolak pembuatan akun publik dan dialihkan ke login.
     */
    public function test_registration_endpoint_rejects_post_requests(): void
    {
        $response = $this->post('/register', [
            'name' => 'Intruder',
            'email' => 'intruder@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
