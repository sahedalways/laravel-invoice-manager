<?php

use App\Livewire\Customers\Customers;
use App\Livewire\Dashboard;
use App\Livewire\Invoices\Invoices;
use App\Livewire\Products\Products;
use App\Livewire\Reports\Reports;
use App\Livewire\Settings\PasswordSettings;
use App\Livewire\Settings\SiteSettings;
use App\Livewire\Stocks\Stocks;
use Illuminate\Support\Facades\Route;



// for admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
  /* Admin Dashboard */
  Route::get('dashboard', Dashboard::class)->name('dashboard');


  // Products
  Route::get('/products', Products::class)->name('products.index');

  // Stocks
  Route::get('/stocks', Stocks::class)->name('stocks.index');

  // Customers
  Route::get('/customers', Customers::class)->name('customers.index');

  // Invoices
  Route::get('/invoices', Invoices::class)->name('invoices.index');

  // Reports
  Route::get('/reports', Reports::class)->name('reports.index');


  // for settings group routes
  Route::prefix('/settings')->name('settings.')->group(function () {
    Route::get('/site', SiteSettings::class)->name('site');
    Route::get('/password', PasswordSettings::class)->name('password');
  });
});
