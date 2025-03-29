<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Dashboard\CountriesController;
use App\Http\Controllers\Dashboard\CurrenciesController;
use App\Http\Controllers\Dashboard\ProductsController;

Route::get('/', function () {
    return view('website.index');
})->name('home');

// Define  for guests 
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('website.login');
    })->name('login.form');

    Route::post('/login', LoginController::class)->name('login.submit');
});

//  routes for authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard.home');

    Route::resource('/dashboard/countries', CountriesController::class)->except([
        'create', 'show', 'edit',
    ])->names('dashboard.countries');

    Route::resource('/dashboard/currencies', CurrenciesController::class)->except([
        'create', 'show', 'edit',
    ])->names('dashboard.currencies');

    Route::resource('/dashboard/products', ProductsController::class)->except([
        'create', 'show', 'edit',
    ])->names('dashboard.products');
    
    
    Route::post('/logout', LogoutController::class)->name('dashboard.logout');
});
