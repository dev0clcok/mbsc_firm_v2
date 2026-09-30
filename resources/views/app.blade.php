@php
    use App\Models\SiteSetting;

    $isPublic = in_array($page['component'], ['Welcome', 'Services', 'Service', 'About', 'Contact'], true);
    $seo = $seo ?? null;
    $siteName = config('app.name', 'MBSC Firm');
    $title = $seo ? $seo['title'].' | '.$siteName : $siteName;
@endphp
{{-- Render the SSR head first so the fallback <title> below is only used when SSR is unavailable. --}}
@php ob_start(); @endphp
@inertiaHead
@php $inertiaHead = ob_get_clean(); @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ! $isPublic && ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @unless ($isPublic)
            {{-- Inline script to detect system dark mode preference and apply it immediately --}}
            <script>
                (function() {
                    const appearance = '{{ $appearance ?? "system" }}';

                    if (appearance === 'system') {
                        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                        if (prefersDark) {
                            document.documentElement.classList.add('dark');
                        }
                    }
                })();
            </script>

            {{-- Inline style to set the HTML background color based on our theme in app.css --}}
            <style>
                html {
                    background-color: oklch(1 0 0);
                }

                html.dark {
                    background-color: oklch(0.145 0 0);
                }
            </style>
        @endunless

        @unless (str_contains($inertiaHead, '<title'))
            <title inertia>{{ $title }}</title>
        @endunless

        @if ($isPublic && $seo)
            <meta name="description" content="{{ $seo['description'] }}">
            <link rel="canonical" href="{{ url()->current() }}">
            <meta property="og:type" content="website">
            <meta property="og:site_name" content="{{ $siteName }}">
            <meta property="og:title" content="{{ $title }}">
            <meta property="og:description" content="{{ $seo['description'] }}">
            <meta property="og:url" content="{{ url()->current() }}">
            <meta property="og:image" content="{{ asset('images/og.png') }}">
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
            <meta property="og:locale" content="en_GB">
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="{{ $title }}">
            <meta name="twitter:description" content="{{ $seo['description'] }}">
            <meta name="twitter:image" content="{{ asset('images/og.png') }}">
            <meta name="theme-color" content="#1e2230">
            <script type="application/ld+json">{!! json_encode(SiteSetting::structuredData(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
        @else
            <meta name="robots" content="noindex">
        @endif

        <link rel="icon" href="/favicon.ico" sizes="48x48">
        <link rel="icon" href="/images/icon-192.png" type="image/png" sizes="192x192">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @unless ($isPublic)
            <link rel="preconnect" href="https://fonts.bunny.net">
            <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @endunless

        @vite([$isPublic ? 'resources/css/public.css' : 'resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        {!! $inertiaHead !!}
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
