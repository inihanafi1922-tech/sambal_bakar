<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Ayam Bakar Sambal Bawang', 'price' => 28000, 'category' => 'Makanan', 'has_spicy_level' => true],
            ['name' => 'Ayam Geprek Sambal Matah', 'price' => 26000, 'category' => 'Makanan', 'has_spicy_level' => true],
            ['name' => 'Ikan Nila Bakar', 'price' => 32000, 'category' => 'Makanan', 'has_spicy_level' => true],
            ['name' => 'Sate Ayam Madura', 'price' => 25000, 'category' => 'Makanan', 'has_spicy_level' => false],
            ['name' => 'Tahu Tempe Bakar', 'price' => 15000, 'category' => 'Makanan', 'has_spicy_level' => true],
            ['name' => 'Nasi Putih', 'price' => 5000, 'category' => 'Makanan', 'has_spicy_level' => false],
            ['name' => 'Nasi Bakar', 'price' => 12000, 'category' => 'Makanan', 'has_spicy_level' => false],
            ['name' => 'Sambal Bawang', 'price' => 8000, 'category' => 'Sambal', 'has_spicy_level' => true],
            ['name' => 'Sambal Matah', 'price' => 8000, 'category' => 'Sambal', 'has_spicy_level' => true],
            ['name' => 'Sambal Terasi', 'price' => 8000, 'category' => 'Sambal', 'has_spicy_level' => true],
            ['name' => 'Sambal Ijo', 'price' => 8000, 'category' => 'Sambal', 'has_spicy_level' => true],
            ['name' => 'Es Teh Manis', 'price' => 6000, 'category' => 'Minuman', 'has_spicy_level' => false],
            ['name' => 'Es Jeruk', 'price' => 8000, 'category' => 'Minuman', 'has_spicy_level' => false],
            ['name' => 'Lemon Tea', 'price' => 10000, 'category' => 'Minuman', 'has_spicy_level' => false],
            ['name' => 'Air Mineral', 'price' => 5000, 'category' => 'Minuman', 'has_spicy_level' => false],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['name' => $product['name']], $product);
        }
    }
}
