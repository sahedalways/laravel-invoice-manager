<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Abdul Karim',
                'email' => 'karim@example.com',
                'phone' => '01711111111',
                'address' => 'Dhaka, Bangladesh',
            ],
            [
                'name' => 'Nusrat Jahan',
                'email' => 'nusrat@example.com',
                'phone' => '01722222222',
                'address' => 'Chittagong, Bangladesh',
            ],
            [
                'name' => 'Tanvir Rahman',
                'email' => 'tanvir@example.com',
                'phone' => '01733333333',
                'address' => 'Rajshahi, Bangladesh',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
