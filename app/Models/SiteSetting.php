<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use Auditable;

    public const CACHE_KEY = 'site_settings';

    /**
     * Every setting the admin can edit. Social keys end in "_url" and are
     * listed in SOCIAL_KEYS so the public site can hide the empty ones.
     */
    public const KEYS = [
        'phone',
        'whatsapp',
        'email',
        'enquiry_email',
        'address',
        'maps_url',
        'office_hours',
        'response_time',
        'facebook_url',
        'linkedin_url',
        'x_url',
        'youtube_url',
        'instagram_url',
    ];

    public const SOCIAL_KEYS = [
        'facebook' => 'facebook_url',
        'linkedin' => 'linkedin_url',
        'x' => 'x_url',
        'youtube' => 'youtube_url',
        'instagram' => 'instagram_url',
    ];

    protected $table = 'site_settings';

    protected $fillable = ['key', 'value'];

    /**
     * @return array<string, string|null>
     */
    public static function values(): array
    {
        $stored = Cache::rememberForever(
            self::CACHE_KEY,
            fn () => self::query()->pluck('value', 'key')->all(),
        );

        return array_merge(array_fill_keys(self::KEYS, null), array_intersect_key($stored, array_flip(self::KEYS)));
    }

    public static function get(string $key): ?string
    {
        return self::values()[$key] ?? null;
    }

    /**
     * @param  array<string, string|null>  $values
     */
    public static function put(array $values): void
    {
        foreach (array_intersect_key($values, array_flip(self::KEYS)) as $key => $value) {
            $value = is_string($value) ? trim($value) : $value;

            self::query()->updateOrCreate(['key' => $key], ['value' => $value === '' ? null : $value]);
        }

        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Settings shaped for the public site: link targets are derived here so
     * the frontend never builds tel:, wa.me or mailto: URLs by hand.
     *
     * @return array<string, mixed>
     */
    public static function forPublic(): array
    {
        $s = self::values();
        $digits = fn (?string $number) => $number ? preg_replace('/\D+/', '', $number) : null;

        $socials = [];
        foreach (self::SOCIAL_KEYS as $platform => $key) {
            if (! empty($s[$key])) {
                $socials[] = ['platform' => $platform, 'url' => $s[$key]];
            }
        }

        return [
            'phone' => $s['phone'],
            'phone_href' => $s['phone'] ? 'tel:+'.$digits($s['phone']) : null,
            'whatsapp_url' => $s['whatsapp'] ? 'https://wa.me/'.$digits($s['whatsapp']) : null,
            'email' => $s['email'],
            'address' => $s['address'],
            'maps_url' => $s['maps_url'],
            // Keep "7:00 PM" together so the AM/PM never wraps onto its own line.
            'office_hours' => $s['office_hours']
                ? preg_replace('/(\d)\s+(AM|PM)\b/i', "$1\u{00A0}$2", $s['office_hours'])
                : null,
            'response_time' => $s['response_time'],
            'socials' => $socials,
        ];
    }

    /**
     * schema.org description of the firm for search engines. Only facts
     * held in settings are included.
     *
     * @return array<string, mixed>
     */
    public static function structuredData(): array
    {
        $s = self::values();
        $digits = fn (?string $number) => $number ? preg_replace('/\D+/', '', $number) : null;

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            'name' => config('app.name'),
            'url' => url('/'),
            'logo' => asset('images/icon-192.png'),
            'image' => asset('images/og.png'),
            'description' => 'RJSC company registration, income tax, VAT and audit support services in Chattogram, Bangladesh.',
            'telephone' => $s['phone'] ? '+'.$digits($s['phone']) : null,
            'email' => $s['email'],
            'address' => $s['address'] ? [
                '@type' => 'PostalAddress',
                'streetAddress' => $s['address'],
                'addressLocality' => 'Chattogram',
                'addressCountry' => 'BD',
            ] : null,
            'areaServed' => ['@type' => 'Country', 'name' => 'Bangladesh'],
            'hasMap' => $s['maps_url'],
            'sameAs' => array_values(array_filter(array_map(fn ($key) => $s[$key], self::SOCIAL_KEYS))) ?: null,
        ]);
    }
}
