<?php

use Illuminate\Support\Facades\Route;

Route::get('/languages', fn() => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales());
Route::post('/languages', [\Crayon\NovaLanguageEditor\Http\Controllers\LanguageController::class, 'store']);
