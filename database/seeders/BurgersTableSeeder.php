<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Burger;
class BurgersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Burger::create([
            'nom' => 'Classic Burger',
            'prix' => 1495.99,
            'description' => 'Un burger classique avec du fromage, de la salade et de la sauce.',
            'image' => 'images/Classic Burger.jpg',
            'stock' => 50,
        ]);

        Burger::create([
            'nom' => 'Cheeseburger',
            'prix' => 2500,
            'description' => 'Un burger avec du fromage fondu et des oignons caramélisés.',
            'image' => 'images/Bacon Burger.jpg',
            'stock' => 30,
        ]);

        Burger::create([
            'nom' => 'Bacon Burger',
            'prix' => 3700.99,
            'description' => 'Un burger avec du bacon croustillant et de la sauce barbecue.',
            'image' => 'images/bacon-burger.jpg',
            'stock' => 20,
        ]);
    }
}
