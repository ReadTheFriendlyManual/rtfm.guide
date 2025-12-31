<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportGuidesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:json', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Please upload a JSON file containing guides to import.',
            'file.mimes' => 'The file must be a valid JSON file.',
            'file.max' => 'The file size must not exceed 10MB.',
        ];
    }
}
