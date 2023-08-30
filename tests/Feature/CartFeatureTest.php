<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_product_to_cart()
    {
        $product = Product::factory()->create([
            'available' => true,
        ]);

        $data = [
            'product_id' => $product->id,
            'quantity' => 2,
        ];

        $response = $this->postJson('/api/cart', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'product_id',
                'quantity',
            ]);
    }

    public function test_cannot_add_product_to_cart_when_out_of_stock()
    {
        $product = Product::factory()->create([
            'available' => false,
        ]);

        $data = [
            'product_id' => $product->id,
            'quantity' => 1,
        ];

        $response = $this->postJson('/api/cart', $data);

        $response->assertStatus(422)
            ->assertJson([
                'error' => 'The product is out of stock',
            ]);
    }

    public function test_can_remove_product_from_cart()
    {
        $cartItem = Cart::factory()->create();

        $response = $this->deleteJson("/api/cart/{$cartItem->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('carts', ['id' => $cartItem->id]);
    }

    public function test_cannot_remove_nonexistent_cart_item()
    {
        $response = $this->deleteJson("/api/cart/123");

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Cart item not found',
            ]);
    }
}
