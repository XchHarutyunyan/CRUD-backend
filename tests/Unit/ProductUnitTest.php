<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product()
    {
        $productData = [
            'name' => 'Sample Product',
            'price' => 10.99,
            'description' => 'Sample description',
            'available' => true,
        ];

        $product = Product::create($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($productData['name'], $product->name);
        $this->assertEquals($productData['price'], $product->price);
        $this->assertEquals($productData['description'], $product->description);
        $this->assertEquals($productData['available'], $product->available);
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();

        $updatedData = [
            'name' => 'Updated Product',
            'price' => 19.99,
            'description' => 'Updated description',
            'available' => false,
        ];

        $product->update($updatedData);

        $this->assertEquals($updatedData['name'], $product->name);
        $this->assertEquals($updatedData['price'], $product->price);
        $this->assertEquals($updatedData['description'], $product->description);
        $this->assertEquals($updatedData['available'], $product->available);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $product->delete();

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_can_show_product()
    {
        $product = Product::factory()->create();

        $response = $this->get("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'name' => $product->name,
                'price' => $product->price,
                'description' => $product->description,
                'available' => $product->available,
            ]);
    }

    public function test_can_list_products()
    {
        Product::factory(3)->create();

        $response = $this->get('/api/products');

        $response->assertStatus(201)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'price', 'description', 'available'],
                ],
            ]);
    }
}
