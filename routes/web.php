<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InformationController;

Route::get('/', [InformationController::class, 'index'])->name('home');

Route::get('/map', function () {
    return view('map');
})->name('map');


Route::get('/guide', function () {
    return view('guide');
})->name('guide');
