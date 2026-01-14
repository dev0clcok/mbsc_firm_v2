<?php

use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\ServiceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    Route::resource('services', ServiceController::class);
    Route::resource('faqs', FAQController::class);
});
