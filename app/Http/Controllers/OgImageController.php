<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Guide;
use App\Models\User;
use SimonHamp\TheOg\Image;
use SimonHamp\TheOg\Layout\Layouts\GitHubBasic;
use SimonHamp\TheOg\Theme;
use Symfony\Component\HttpFoundation\Response;

class OgImageController extends Controller
{
    public function guide(Guide $guide): Response
    {
        // Only allow OG images for published and public guides
        if ($guide->status !== \App\Enums\GuideStatus::Published || $guide->visibility !== \App\Enums\GuideVisibility::Public) {
            abort(404);
        }

        $image = (new Image)
            ->layout(new GitHubBasic)
            ->theme(Theme::Dark)
            ->title($guide->title)
            ->description(str($guide->tldr ?? $guide->content)->limit(100)->stripTags()->toString())
            ->url(parse_url(config('app.url'), PHP_URL_HOST));

        return response($image->toString(), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function category(Category $category): Response
    {
        // Load guides count if not already loaded
        if (!isset($category->guides_count)) {
            $category->loadCount('guides');
        }
        
        $guideCount = $category->guides_count;

        $image = (new Image)
            ->layout(new GitHubBasic)
            ->theme(Theme::Dark)
            ->title($category->name)
            ->description("{$guideCount} ".str('guide')->plural($guideCount).' available')
            ->url(parse_url(config('app.url'), PHP_URL_HOST));

        return response($image->toString(), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function user(User $user): Response
    {
        $image = (new Image)
            ->layout(new GitHubBasic)
            ->theme(Theme::Dark)
            ->title($user->name)
            ->description($user->bio ?? 'RTFM Community Member')
            ->url(parse_url(config('app.url'), PHP_URL_HOST));

        return response($image->toString(), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
