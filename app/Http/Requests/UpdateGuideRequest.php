<?php

namespace App\Http\Requests;

use App\Enums\GuideDifficulty;
use App\Enums\GuideStatus;
use App\Enums\GuideVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateGuideRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow all authenticated users to submit edits (will go through moderation unless trusted)
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('slug') && $this->has('title')) {
            $this->merge([
                'slug' => Str::slug($this->title),
            ]);
        }
    }

    public function rules(): array
    {
        $guideId = $this->route('guide')->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('guides', 'slug')->ignore($guideId)],
            'tldr' => ['required', 'string', 'max:1000'],
            'tldr_nsfw' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'content_nsfw' => ['nullable', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'difficulty' => ['required', Rule::enum(GuideDifficulty::class)],
            'estimated_minutes' => ['nullable', 'integer', 'min:1', 'max:999'],
            'os_tags' => ['nullable', 'array'],
            'os_tags.*' => ['string', 'max:50'],
            'status' => ['nullable', Rule::enum(GuideStatus::class)],
            'visibility' => ['nullable', Rule::enum(GuideVisibility::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your guide.',
            'title.max' => 'The title must not exceed 255 characters.',
            'slug.unique' => 'A guide with this URL slug already exists. Please choose a different title.',
            'tldr.required' => 'Please provide a quick summary (TL;DR) of your guide.',
            'tldr.max' => 'The TL;DR must not exceed 1000 characters.',
            'content.required' => 'Please provide the guide content.',
            'category_id.required' => 'Please select a category for your guide.',
            'category_id.exists' => 'The selected category is invalid.',
            'difficulty.required' => 'Please select a difficulty level.',
            'estimated_minutes.min' => 'The estimated time must be at least 1 minute.',
            'estimated_minutes.max' => 'The estimated time must not exceed 999 minutes.',
        ];
    }
}
