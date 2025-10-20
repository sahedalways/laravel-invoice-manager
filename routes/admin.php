<?php

use App\Livewire\Admin\Pos\Pos;
use App\Livewire\Admin\Customers\Customers;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Invoices\Invoices;
use App\Livewire\Admin\Products\Products;
use App\Livewire\Admin\Reports\Reports;
use App\Livewire\Admin\Settings\PasswordSettings;
use App\Livewire\Admin\Settings\SiteSettings;
use App\Livewire\Admin\Stocks\Stocks;
use Illuminate\Support\Facades\Route;



// for admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
  /* Admin Dashboard */
  Route::get('dashboard', Dashboard::class)->name('dashboard');

  // pos
  Route::get('/pos', Pos::class)->name('pos.index');


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
