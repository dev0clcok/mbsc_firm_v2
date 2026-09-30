<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return (bool) ($user?->isSuperAdmin() || $user?->can('settings.update'));
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $phone = ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/'];
        $url = ['nullable', 'url:http,https', 'max:500'];

        $image = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:8192'];

        return [
            'hero_home' => $image,
            'hero_services' => $image,
            'hero_about' => $image,
            'remove_hero_home' => ['nullable', 'boolean'],
            'remove_hero_services' => ['nullable', 'boolean'],
            'remove_hero_about' => ['nullable', 'boolean'],
            'phone' => $phone,
            'whatsapp' => $phone,
            'whatsapp_message' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
            'enquiry_email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'maps_url' => $url,
            'map_latitude' => ['nullable', 'numeric', 'between:-90,90', 'required_with:map_longitude'],
            'map_longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:map_latitude'],
            'office_hours' => ['nullable', 'string', 'max:255'],
            'response_time' => ['nullable', 'string', 'max:255'],
            'facebook_url' => $url,
            'linkedin_url' => $url,
            'x_url' => $url,
            'youtube_url' => $url,
            'instagram_url' => $url,
        ];
    }
}
