<?php

namespace Database\Seeders;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {

    // for creating admin user
    User::create(attributes: [
      'f_name' => 'Mr',
      'l_name' => 'Admin',
      'email' => 'admin@admin.com',
      'phone_no' => '0177xxxxxxx',
      'password' => 12345678,
      'user_type' => 'admin',
      'email_verified_at' => Carbon::now(),
    ]);
  }
}
