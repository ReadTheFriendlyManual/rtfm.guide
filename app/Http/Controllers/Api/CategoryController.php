<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->with(['children' => fn ($query) => $query->orderBy('display_order')->withCount('guides')])
            ->withCount('guides')
            ->orderBy('display_order')
            ->get();

        return CategoryResource::collection($categories);
    }

    public function show(Category $category): CategoryResource
    {
        $category->load([
            'children' => fn ($query) => $query->orderBy('display_order')->withCount('guides'),
            'parent',
        ])->loadCount('guides');

        return CategoryResource::make($category);
    }
}
