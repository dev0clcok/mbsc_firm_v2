<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Http\Services\ImageStore;
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
            'images' => collect(SiteSetting::IMAGE_KEYS)
                ->mapWithKeys(fn (string $key) => [$key => SiteSetting::image($key)])
                ->all(),
        ]);
    }

    public function update(UpdateSiteSettingsRequest $request, ImageStore $images): RedirectResponse
    {
        SiteSetting::put($request->validated());

        foreach (SiteSetting::IMAGE_KEYS as $key) {
            $current = SiteSetting::image($key);

            $alt = $request->input("{$key}_alt");

            if ($request->hasFile($key)) {
                $images->delete($current['url'] ?? null);
                SiteSetting::putImage($key, [...$images->store($request->file($key), 'heroes'), 'alt' => $alt]);
            } elseif ($request->boolean("remove_{$key}")) {
                $images->delete($current['url'] ?? null);
                SiteSetting::putImage($key, null);
            } elseif ($current && $request->has("{$key}_alt") && $alt !== ($current['alt'] ?? null)) {
                SiteSetting::putImage($key, [...$current, 'alt' => $alt]);
            }
        }

        return redirect()->route('admin.site-settings.edit')
            ->with('success', 'Site settings updated successfully.');
    }
}
