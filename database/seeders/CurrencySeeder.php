<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert default currency if not exists
        Currency::updateOrCreate(
            ['code' => 'USD'],
            ['symbol' => '$']
        );
    }
}
