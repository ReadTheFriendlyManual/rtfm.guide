<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'display_order' => $this->display_order,
            'guide_count' => $this->when(isset($this->guides_count), (int) $this->guides_count),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
            'links' => [
                'api' => route('api.categories.show', $this->resource, absolute: false),
            ],
        ];
    }
}
