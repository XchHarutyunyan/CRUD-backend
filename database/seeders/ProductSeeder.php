<?php

namespace Database\Seeders;
use App\Models\Product;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Product::insert([
            [
                'name' => 'Sample Product 1',
                'price' => 19.99,
                'description' => 'This is a sample product description.',
                'available' => true,
            ],
            [
                'name' => 'Sample Product 2',
                'price' => 29.99,
                'description' => 'Another sample product description.',
                'available' => true,
            ],
        ]);
    }
}
