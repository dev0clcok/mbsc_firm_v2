<?php
use App\Http\Controllers\Public\HomeController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Services page
Route::get('/services', [HomeController::class, 'services'])->name('services');

// About page
Route::get('/about', [HomeController::class, 'about'])->name('about');
 //contact page
 Route::get('/contact', [HomeController::class, 'contact'])->name('contact');