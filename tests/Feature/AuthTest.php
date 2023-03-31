<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_cart()
    {
        $response = $this->get('/cart');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_guests_cannot_access_wishlist()
    {
        $response = $this->get('/wishlist');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
