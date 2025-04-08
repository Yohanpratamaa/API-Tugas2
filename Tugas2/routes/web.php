<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventoryController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/otp', function () {
    return view('auth.otp');
});

Route::get('/dashboard', function () {
    return view('features/dashboard');
});

Route::get('/inputBarang', function () {
    return view('features/Inventory/Input');
});

Route::get('/outBarang', function () {
    return view('features/Inventory/Output');
});

Route::get('/editBarang', function (Request $request) {
    return view('features/Inventory/Edit', ['id' => $request->query('id')]);
});
