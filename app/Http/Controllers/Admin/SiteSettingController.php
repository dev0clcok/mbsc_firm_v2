<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings.view', only: ['edit']),
            new Middleware('permission:settings.update', only: ['update']),
        ];
    }

    public function edit(): Response
    {
        return Inertia::render('admin/SiteSettings/Edit', [
            'settings' => SiteSetting::values(),
        ]);
    }

    public function update(UpdateSiteSettingsRequest $request): RedirectResponse
    {
        SiteSetting::put($request->validated());

        return redirect()->route('admin.site-settings.edit')
            ->with('success', 'Site settings updated successfully.');
    }
}
