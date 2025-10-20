<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Stock;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        Product::all()->each(function ($product) {
            Stock::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $product->stock_quantity,
                'reference' => 'Initial static stock',
            ]);
        });
    }
}
