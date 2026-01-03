<?php

namespace App\Http\Requests;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class NewsletterSubscribeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Normalize email for case-insensitive comparison
                    $normalizedEmail = strtolower(trim($value));

                    // Check if a verified subscriber already exists (case-insensitive)
                    $existingSubscriber = NewsletterSubscriber::whereRaw('LOWER(email) = ?', [$normalizedEmail])
                        ->whereNotNull('email_verified_at')
                        ->first();

                    if ($existingSubscriber) {
                        $fail('This email is already subscribed to our newsletter.');

                        return;
                    }

                    // Check if a registered user is already subscribed (case-insensitive)
                    $user = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])
                        ->where('newsletter_subscribed', true)
                        ->first();

                    if ($user) {
                        $fail('This email is already subscribed to our newsletter.');
                    }
                },
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email address must not exceed 255 characters.',
        ];
    }
}
