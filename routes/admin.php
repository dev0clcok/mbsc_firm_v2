<?php

use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\PrivacyPolicyController;
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

    Route::resource('team-members', TeamMemberController::class)->except(['show']);

    Route::resource('testimonials', TestimonialController::class)->except(['show']);

    Route::resource('services', ServiceController::class)->except(['show']);

    Route::get('enquiries/export', [EnquiryController::class, 'export'])->name('enquiries.export');
    Route::post('enquiries/{enquiry}/notes', [EnquiryController::class, 'storeNote'])->name('enquiries.notes.store');
    Route::resource('enquiries', EnquiryController::class)->only(['index', 'show', 'update', 'destroy']);

    Route::get('site-settings', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
    Route::put('site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');

    Route::get('privacy-policy', [PrivacyPolicyController::class, 'edit'])->name('privacy-policy.edit');
    Route::put('privacy-policy', [PrivacyPolicyController::class, 'update'])->name('privacy-policy.update');

    Route::resource('roles', RoleController::class)->except(['show']);

    Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});
