<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product()
    {
        $data = [
            'name' => 'Sample Product',
            'price' => 10.99,
            'description' => 'Sample description',
            'available' => true,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201)
            ->assertJson([
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'],
                'available' => $data['available'],
            ]);

        $this->assertDatabaseHas('products', [
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
            'available' => $data['available'],
        ]);
    }

    public function test_can_update_product()
    {
        $product = Product::factory(1)->create()->first();

        $data = [
            'name' => 'Updated Product',
            'price' => 19.99,
            'description' => 'Updated description',
            'available' => false,
        ];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'],
                'available' => $data['available'],
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
            'available' => $data['available'],
        ]);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory(1)->create()->first();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Product deleted successfully']);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_can_show_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

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

        $response = $this->getJson('/api/products');

        $response->assertStatus(201)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'price', 'description', 'available'],
                ],
            ]);
    }
}
