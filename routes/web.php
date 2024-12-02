<?php

use App\Http\Controllers\Admin\{ReplySupportController, SupportController};
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

Route::get('/', function () {
    return redirect()->route('weather.index');
});

Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::post('/weather/search', [WeatherController::class, 'search'])->name('weather.search');
Route::post('/weather/save', [WeatherController::class, 'save'])->name('weather.save');
Route::get('/weather/compare', [WeatherController::class, 'compare'])->name('weather.compare');
Route::get('/weather/history', [WeatherController::class, 'history'])->name('weather.history');


