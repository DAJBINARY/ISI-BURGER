<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::create([
            'name' => 'Cheeseburger',
            'price' => 5.99,
            'description' => 'Un délicieux cheeseburger.',
            'image' => 'cheeseburger.jpg',
            'stock' => 10,
        ]);
    }
}
