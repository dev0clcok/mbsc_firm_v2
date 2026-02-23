<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return (bool) ($user?->isSuperAdmin() || $user?->can('team_members.create'));
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'social_links' => ['nullable', 'array'],
            'social_links.*.platform' => ['required_with:social_links.*.url', 'string', 'in:facebook,linkedin,twitter,instagram,youtube,email'],
            'social_links.*.url' => [
                'required_with:social_links.*.platform',
                'string',
                'max:500',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $index = (int) preg_replace('/\D/', '', $attribute);
                    $platform = $this->input("social_links.{$index}.platform");
                    if ($platform === 'email' && $value && ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail(__('validation.email'));
                    }
                    if ($platform && $platform !== 'email' && $value && ! filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail(__('validation.url'));
                    }
                },
            ],
            'image' => ['nullable', 'file', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ];
    }
}
