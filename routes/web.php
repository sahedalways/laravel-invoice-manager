<?php

use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// login route
Route::get('/', [Login::class, '__invoke'])->name('login');


// imported external routes below
require __DIR__ . '/admin.php';


Route::get('/clear', function () {
    Artisan::call('route:cache');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return 'Routes cache has been cleared';
});
