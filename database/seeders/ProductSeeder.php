<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        $categoryIds = Category::query()->pluck('id', 'name');

        $products = [
            [
                'category' => 'Sembako',
                'code' => 'SBK-001',
                'name' => 'Beras Premium 5 kg',
                'unit' => 'karung',
                'price' => 75000,
                'stock' => 25,
            ],
            [
                'category' => 'Sembako',
                'code' => 'SBK-002',
                'name' => 'Minyak Goreng 1 L',
                'unit' => 'botol',
                'price' => 18000,
                'stock' => 7,
            ],
            [
                'category' => 'Sembako',
                'code' => 'SBK-003',
                'name' => 'Gula Pasir 1 kg',
                'unit' => 'pcs',
                'price' => 16000,
                'stock' => 0,
            ],
            [
                'category' => 'Minuman',
                'code' => 'MIN-001',
                'name' => 'Air Mineral 600 ml',
                'unit' => 'botol',
                'price' => 4000,
                'stock' => 48,
            ],
            [
                'category' => 'Minuman',
                'code' => 'MIN-002',
                'name' => 'Teh Botol 450 ml',
                'unit' => 'botol',
                'price' => 5500,
                'stock' => 4,
            ],
            [
                'category' => 'Makanan Ringan',
                'code' => 'MKN-001',
                'name' => 'Keripik Singkong',
                'unit' => 'bungkus',
                'price' => 12000,
                'stock' => 12,
            ],
            [
                'category' => 'Makanan Ringan',
                'code' => 'MKN-002',
                'name' => 'Mi Instan',
                'unit' => 'pcs',
                'price' => 3500,
                'stock' => 0,
            ],
            [
                'category' => 'Kebutuhan Rumah Tangga',
                'code' => 'RT-001',
                'name' => 'Sabun Cuci Piring 700 ml',
                'unit' => 'botol',
                'price' => 14000,
                'stock' => 6,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['code' => $product['code']],
                [
                    'category_id' => $categoryIds->get($product['category']),
                    'name' => $product['name'],
                    'unit' => $product['unit'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                ],
            );
        }
    }
}
