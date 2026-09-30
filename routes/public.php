<?php

use App\Http\Controllers\Public\EnquiryController;
use App\Http\Controllers\Public\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::post('/enquiries', [EnquiryController::class, 'store'])
    ->middleware('throttle:enquiries')
    ->name('enquiries.store');
