<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomepageTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     * 
     *  Arrange, Act, Assert
     *
     * @return void
     */
    public function test_homepage_is_visible()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_homepage_contains_featured_products()
    {
        $featured_product = Product::create([
            'name' => 'Prodotto',
            'original_price' => 5.00,
            'hidden' => false,
            'featured' => true,
        ]);

        $response = $this->get('/');

        $response->assertViewHas('featured_products', function($collection) use ($featured_product) {
            return $collection->contains($featured_product);
        });
    }

    public function test_homepage_shows_not_featured_products()
    {
        $products = Product::factory(10)->create([
            'hidden' => false,
            'featured' => false
        ]);
        $product = $products->last();

        $response = $this->get('/');

        $response->assertViewHas('featured_products', function($collection) use ($product) {
            return $collection->contains($product);
        });
    }
}
