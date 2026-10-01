<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'required_without:email', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{6,}$/'],
            'email' => ['nullable', 'required_without:phone', 'email', 'max:255'],
            // The form offers the titles of the services shown on the site.
            'service' => ['nullable', 'string', 'max:255', Rule::exists('services', 'title')->where('is_active', true)],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            // Honeypot: real visitors never see or fill this field.
            'website' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Enter your name.',
            'phone.required_without' => 'Enter a phone number or an email address so we can reply.',
            'phone.regex' => 'Enter a valid phone number.',
            'email.required_without' => 'Enter a phone number or an email address so we can reply.',
            'email.email' => 'Enter a valid email address.',
            'service.exists' => 'Choose a service from the list, or leave it as "Not sure yet".',
            'message.required' => 'Tell us what you need help with.',
            'message.min' => 'Add a little more detail, at least 10 characters.',
        ];
    }

    public function isSpam(): bool
    {
        return $this->filled('website');
    }
}
