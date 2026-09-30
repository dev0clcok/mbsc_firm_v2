<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class PrivacyPolicyController extends Controller implements HasMiddleware
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
        return Inertia::render('admin/PrivacyPolicy/Edit', [
            'policy' => SiteSetting::get('privacy_policy') ?? '',
            'published' => SiteSetting::get('privacy_published') === '1',
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'policy' => ['nullable', 'string', 'max:30000', 'required_if_accepted:published'],
            'published' => ['boolean'],
        ], [
            'policy.required_if_accepted' => 'Write the policy before publishing it.',
        ]);

        SiteSetting::put([
            'privacy_policy' => $validated['policy'] ?? null,
            'privacy_published' => ($validated['published'] ?? false) ? '1' : '0',
        ]);

        return redirect()->route('admin.privacy-policy.edit')
            ->with('success', ($validated['published'] ?? false)
                ? 'Privacy policy saved and published.'
                : 'Privacy policy saved. It is not shown on the website.');
    }
}
