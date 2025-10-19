<?php

use App\Livewire\Dashboard;
use App\Livewire\Settings\PasswordSettings;
use App\Livewire\Settings\SiteSettings;
use Illuminate\Support\Facades\Route;



// for admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
  /* Admin Dashboard */
  Route::get('dashboard', Dashboard::class)->name('dashboard');

  // for settings group routes
  Route::prefix('/settings')->name('settings.')->group(function () {
    Route::get('/site', SiteSettings::class)->name('site');
    Route::get('/password', PasswordSettings::class)->name('password');
  });
});
