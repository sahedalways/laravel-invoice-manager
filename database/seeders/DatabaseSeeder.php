<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserTableSeeder::class,
            SiteSettingSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            CurrencySeeder::class,
        ]);
    }
}
