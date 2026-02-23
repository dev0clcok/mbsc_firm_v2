<?php

use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'permission:admin.access'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    Route::resource('faqs', FAQController::class)->except(['show']);

    Route::resource('testimonials', TestimonialController::class)->except(['show']);

    Route::resource('services', ServiceController::class)->except(['show']);

    Route::resource('roles', RoleController::class)->except(['show']);

    Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});
