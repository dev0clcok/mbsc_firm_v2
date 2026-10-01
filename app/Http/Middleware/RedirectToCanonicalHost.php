<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToCanonicalHost
{
    /**
     * Send visitors who arrive on the other spelling of the site's host name
     * (with or without "www.") to the one set in APP_URL, so that search
     * engines index a single address for each page.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $canonicalUrl = rtrim((string) config('app.url'), '/');
        $canonicalHost = (string) parse_url($canonicalUrl, PHP_URL_HOST);

        if ($request->isMethodSafe() && $this->isOtherSpellingOf($canonicalHost, $request->getHost())) {
            return redirect()->to($canonicalUrl.$request->getRequestUri(), 301);
        }

        return $next($request);
    }

    protected function isOtherSpellingOf(string $canonicalHost, string $requestHost): bool
    {
        $requestHost = strtolower($requestHost);
        $canonicalHost = strtolower($canonicalHost);

        return $canonicalHost !== ''
            && $requestHost !== $canonicalHost
            && preg_replace('/^www\./', '', $requestHost) === preg_replace('/^www\./', '', $canonicalHost);
    }
}
