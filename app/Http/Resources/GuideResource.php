<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'tldr' => $this->tldr,
            'content' => $this->content,
            'difficulty' => $this->difficulty,
            'estimated_minutes' => $this->estimated_minutes,
            'os_tags' => $this->os_tags,
            'status' => $this->status,
            'visibility' => $this->visibility,
            'view_count' => $this->view_count,
            'share_count' => $this->share_count,
            'prerequisites' => $this->prerequisites,
            'troubleshooting' => $this->troubleshooting,
            'published_at' => $this->published_at?->toIso8601String(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => $this->whenLoaded('author', function () {
                return [
                    'id' => $this->author?->id,
                    'name' => $this->author?->name,
                    'initials' => $this->author?->initials(),
                ];
            }),
            'stats' => [
                'reactions' => [
                    'helpful' => $this->reactionCount('helpful'),
                    'saved_me' => $this->reactionCount('saved_me'),
                    'outdated' => $this->reactionCount('outdated'),
                    'needs_update' => $this->reactionCount('needs_update'),
                ],
                'saves' => $this->savedCount(),
            ],
            'links' => [
                'web' => route('guides.show', $this->resource, absolute: false),
                'api' => route('api.guides.show', $this->resource, absolute: false),
            ],
        ];
    }
}
