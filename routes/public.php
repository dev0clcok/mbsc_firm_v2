<?php

use App\Http\Controllers\Public\EnquiryController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/services/{slug}', [HomeController::class, 'service'])->name('services.show');
Route::get('/faqs', [HomeController::class, 'faqs'])->name('faqs');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');

Route::post('/enquiries', [EnquiryController::class, 'store'])
    ->middleware('throttle:enquiries')
    ->name('enquiries.store');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
