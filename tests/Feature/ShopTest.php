<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void 
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_shop_is_visible()
    {
        $response = $this->get('/shop');

        $response->assertStatus(200);
    }

    public function test_guest_can_access_shop()
    {
        $response = $this->actingAs($this->user)->get('/shop');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_see_taxed_prices()
    {
        $product = Product::factory()->create([
            'selling_price' => null,
        ]);
        
        $response = $this->get('/shop');

        $response->assertDontSee(priceLabel($product->taxed_price));
    }

    public function test_users_can_see_taxed_prices()
    {
        $product = Product::factory()->create([
            'selling_price' => null,
        ]);
        
        $response = $this->actingAs($this->user)->get('/shop');

        $response->assertSee(priceLabel($product->taxed_price));
    }
}
