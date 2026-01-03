<?php

namespace App\Http\Requests;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                    // Check if a verified subscriber already exists
                    $existingSubscriber = NewsletterSubscriber::where('email', $value)
                        ->whereNotNull('email_verified_at')
                        ->first();

                    if ($existingSubscriber) {
                        $fail('This email is already subscribed to our newsletter.');

                        return;
                    }

                    // Check if a registered user is already subscribed
                    $user = User::where('email', $value)
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
