<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Wireless Mouse',
                'sku' => 'PROD-001',
                'description' => 'High precision wireless mouse with ergonomic design.',
                'price' => 850.00,
                'stock_quantity' => 50,
                'image' => 'image/products/mouse.jpg',
            ],
            [
                'name' => 'Mechanical Keyboard',
                'sku' => 'PROD-002',
                'description' => 'RGB mechanical keyboard with blue switches.',
                'price' => 3200.00,
                'stock_quantity' => 30,
                'image' => 'image/products/keyboard.jpg',
            ],
            [
                'name' => 'HD Monitor 24"',
                'sku' => 'PROD-003',
                'description' => 'Full HD 1080p LED monitor, perfect for work and gaming.',
                'price' => 12500.00,
                'stock_quantity' => 20,
                'image' => 'image/products/monitor.jpg',
            ],
            [
                'name' => 'USB-C Cable',
                'sku' => 'PROD-004',
                'description' => 'Fast charging and data transfer cable (1m).',
                'price' => 350.00,
                'stock_quantity' => 100,
                'image' => 'image/products/usb_cable.jpg',
            ],
            [
                'name' => 'Bluetooth Speaker',
                'sku' => 'PROD-005',
                'description' => 'Portable Bluetooth speaker with deep bass sound.',
                'price' => 2200.00,
                'stock_quantity' => 25,
                'image' => 'image/products/speaker.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
