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
        'whatsapp_message',
        'email',
        'enquiry_email',
        'address',
        'maps_url',
        'map_latitude',
        'map_longitude',
        'office_hours',
        'facebook_url',
        'linkedin_url',
        'x_url',
        'youtube_url',
        'instagram_url',
        'seo_home_title',
        'seo_home_description',
        'privacy_policy',
        'privacy_published',
    ];

    public const SOCIAL_KEYS = [
        'facebook' => 'facebook_url',
        'linkedin' => 'linkedin_url',
        'x' => 'x_url',
        'youtube' => 'youtube_url',
        'instagram' => 'instagram_url',
    ];

    /** Page hero pictures, each stored as JSON: {url, width, height}. */
    public const IMAGE_KEYS = ['hero_home', 'hero_services', 'hero_about'];

    protected $table = 'site_settings';

    protected $fillable = ['key', 'value'];

    /**
     * @return array<string, string|null>
     */
    public static function values(): array
    {
        return array_merge(array_fill_keys(self::KEYS, null), array_intersect_key(self::stored(), array_flip(self::KEYS)));
    }

    /**
     * @return array<string, string|null>
     */
    private static function stored(): array
    {
        return Cache::rememberForever(
            self::CACHE_KEY,
            fn () => self::query()->pluck('value', 'key')->all(),
        );
    }

    /**
     * @return array{url: string, width: int|null, height: int|null}|null
     */
    public static function image(string $key): ?array
    {
        $image = json_decode(self::stored()[$key] ?? '', true);

        return is_array($image) && ! empty($image['url']) ? $image : null;
    }

    /**
     * @param  array{url: string, width: int|null, height: int|null}|null  $image
     */
    public static function putImage(string $key, ?array $image): void
    {
        if (! in_array($key, self::IMAGE_KEYS, true)) {
            return;
        }

        self::query()->updateOrCreate(['key' => $key], ['value' => $image ? json_encode($image) : null]);

        Cache::forget(self::CACHE_KEY);
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
            // whatsapp_url opens a chat with the template message already typed;
            // whatsapp_base_url is for callers that supply their own text.
            'whatsapp_url' => $s['whatsapp']
                ? 'https://wa.me/'.$digits($s['whatsapp']).($s['whatsapp_message'] ? '?text='.rawurlencode($s['whatsapp_message']) : '')
                : null,
            'whatsapp_base_url' => $s['whatsapp'] ? 'https://wa.me/'.$digits($s['whatsapp']) : null,
            'email' => $s['email'],
            'address' => $s['address'],
            'maps_url' => $s['maps_url'],
            'map_embed_url' => self::mapEmbedUrl($s),
            // Keep "7:00 PM" together so the AM/PM never wraps onto its own line.
            'office_hours' => $s['office_hours']
                ? preg_replace('/(\d)\s+(AM|PM)\b/i', "$1\u{00A0}$2", $s['office_hours'])
                : null,
            'privacy_published' => self::privacyPublished(),
            'socials' => $socials,
        ];
    }

    /**
     * The privacy policy is only public once it has text and someone has
     * confirmed in the admin that it has been reviewed.
     */
    public static function privacyPublished(): bool
    {
        $s = self::values();

        return $s['privacy_published'] === '1' && filled($s['privacy_policy']);
    }

    /**
     * Address of the embeddable Google map: the exact coordinates when they
     * are set, otherwise a search for the office address.
     *
     * @param  array<string, string|null>  $s
     */
    private static function mapEmbedUrl(array $s): ?string
    {
        $query = filled($s['map_latitude']) && filled($s['map_longitude'])
            ? $s['map_latitude'].','.$s['map_longitude']
            : $s['address'];

        return filled($query)
            ? 'https://www.google.com/maps?q='.rawurlencode($query).'&z=16&output=embed'
            : null;
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
            'geo' => filled($s['map_latitude']) && filled($s['map_longitude']) ? [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $s['map_latitude'],
                'longitude' => (float) $s['map_longitude'],
            ] : null,
            'sameAs' => array_values(array_filter(array_map(fn ($key) => $s[$key], self::SOCIAL_KEYS))) ?: null,
        ]);
    }
}
