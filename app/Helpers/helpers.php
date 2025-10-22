<?php

use App\Models\Currency;
use App\Models\SiteSetting;

if (!function_exists('siteSetting')) {
  function siteSetting()
  {
    return cache()->remember('site_settings', 3600, function () {
      return SiteSetting::first();
    });
  }


  if (!function_exists('currency_symbol')) {
    /**
     * Get the current currency symbol
     *
     * @return string
     */
    function currency_symbol()
    {
      return cache()->remember('currency_symbol', 60 * 60, function () {
        $currency = Currency::first();
        return $currency ? $currency->symbol : '$';
      });
    }
  }
}
