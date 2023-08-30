<?php

namespace Tests\Unit;

use App\Http\Controllers\CartController;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class CartUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_cart_item()
    {
        $product = Product::factory()->create(['available' => true]);

        $requestData = [
            'product_id' => $product->id,
            'quantity' => 2,
        ];
        $cart = Cart::create($requestData);

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertEquals($requestData['product_id'], $cart->product_id);
        $this->assertEquals($requestData['quantity'], $cart->quantity);
    }

    public function test_can_destroy_cart_item()
    {
        $cartItem = Cart::factory()->create();

        $controller = new CartController();
        $response = $controller->destroy($cartItem->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertDatabaseMissing('carts', ['id' => $cartItem->id]);
    }
}
