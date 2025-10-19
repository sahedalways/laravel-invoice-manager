<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
  public function run(): void
  {
    SiteSetting::create([
      'site_title'        => 'SellVoix',
      'logo'              => 'jpeg',
      'favicon'           => 'jpeg',
      'site_phone_number' => '+88016XXXXXXX',
      'site_email'             => 'info@sellvoix.com',
      'copyright_text'    => '© ' . date('Y') . ' SellVoix. All rights reserved.',
    ]);
  }
}
