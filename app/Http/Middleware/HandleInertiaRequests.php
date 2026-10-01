<?php

namespace App\Http\Middleware;

use App\Models\Enquiry;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Only the public site is server-rendered; the admin panel gains nothing
     * from it and relies on browser-only state (theme, locale).
     */
    public function handle(Request $request, \Closure $next)
    {
        if (! $request->routeIs('home', 'services', 'services.show', 'faqs', 'about', 'contact', 'privacy')) {
            config(['inertia.ssr.enabled' => false]);
        }

        return parent::handle($request, $next);
    }

    /**
     * Contact details and the service list used by the public layout. Also
     * used by the error page, which renders outside this middleware.
     *
     * @return array<string, mixed>
     */
    public static function siteProps(): array
    {
        return [
            ...SiteSetting::forPublic(),
            'name' => config('app.name'),
            'services' => Service::query()->active()->orderBy('sort_order')->orderBy('id')
                ->get(['slug', 'title'])
                ->map(fn (Service $s) => ['slug' => $s->slug, 'title' => $s->title])
                ->all(),
        ];
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'site' => fn () => self::siteProps(),
            // Badge in the admin sidebar.
            'newEnquiries' => fn () => $request->is('admin*') && $user?->hasPermission('enquiries.list')
                ? Enquiry::query()->where('status', Enquiry::STATUS_NEW)->count()
                : null,
            'auth' => [
                'user' => $user,
                'roles' => fn () => $user ? $user->roles()->pluck('slug')->all() : [],
                'permissions' => fn () => $user ? $user->allPermissionSlugs() : [],
                'is_super_admin' => fn () => $user ? $user->isSuperAdmin() : false,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
