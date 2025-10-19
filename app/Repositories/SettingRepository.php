<?php

namespace App\Repositories;


use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;



class SettingRepository
{
  /**
   * Get or create site settings (id = 1).
   *
   * @return \App\Models\SiteSetting
   */
  public function getSiteSettings(): SiteSetting
  {
    return SiteSetting::firstOrNew(['id' => 1]);
  }


  /**
   * Save or update site settings
   *
   * @param array $data
   * @return SiteSetting
   */
  public function saveSiteSettings(array $data): SiteSetting

  {
    $settings = $this->getSiteSettings();

    $settings->site_title        = $data['site_title'] ?? $settings->site_title;
    $settings->site_phone_number = $data['site_phone_number'] ?? $settings->site_phone_number;
    $settings->site_email        = $data['site_email'] ?? $settings->site_email;
    $settings->copyright_text    = $data['copyright_text'] ?? $settings->copyright_text;

    // Handle Logo
    if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
      $ext = $data['logo']->getClientOriginalExtension();
      $data['logo']->storeAs('image/settings', 'logo.' . $ext, 'public');
      $settings->logo = $ext;
    }

    // Handle Favicon
    if (isset($data['favicon']) && $data['favicon'] instanceof UploadedFile) {
      $ext = $data['favicon']->getClientOriginalExtension();
      $data['favicon']->storeAs('image/settings', 'favicon.' . $ext, 'public');
      $settings->favicon = $ext;
    }


    $settings->save();


    // Clear cache
    cache()->forget('site_settings');

    return $settings;
  }
}
