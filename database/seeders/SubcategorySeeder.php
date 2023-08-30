<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Subcategory::insert([
            [
                'name' => 'Легковые автомобили',
                'category_id' => 1,
            ],
            [
                'name' => 'Автомобили на запчасти',
                'category_id' => 1,
            ],
            [
                'name' => 'Мобильные телефоны',
                'category_id' => 2,
            ],
        ]);
    }
}
