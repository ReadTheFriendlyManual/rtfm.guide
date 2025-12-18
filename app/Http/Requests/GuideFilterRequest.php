<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class GuideFilterRequest extends FormRequest
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
            'q' => ['nullable', 'string', 'max:150'],
            'difficulty' => ['nullable', 'in:beginner,intermediate,advanced'],
            'category' => ['nullable', 'string', 'exists:categories,slug'],
            'os' => ['nullable', 'string', 'max:50'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }

    /**
     * @return array{
     *     search: ?string,
     *     difficulty: ?string,
     *     category_slug: ?string,
     *     os: ?string,
     *     per_page: int
     * }
     */
    public function filters(): array
    {
        $validated = $this->validated();

        return [
            'search' => $validated['q'] ?? null,
            'difficulty' => $validated['difficulty'] ?? null,
            'category_slug' => $validated['category'] ?? null,
            'os' => $validated['os'] ?? null,
            'per_page' => (int) ($validated['per_page'] ?? 10),
        ];
    }

    public function resolvedCategory(): ?Category
    {
        $slug = $this->filters()['category_slug'] ?? null;

        if ($slug === null) {
            return null;
        }

        return Category::query()->where('slug', $slug)->first();
    }
}
