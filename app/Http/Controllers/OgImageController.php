<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Guide;
use App\Models\User;
use SimonHamp\TheOg\BorderPosition;
use SimonHamp\TheOg\Image;
use SimonHamp\TheOg\Layout\GitHubBasic;
use SimonHamp\TheOg\Theme\Background as ThemeBackground;
use SimonHamp\TheOg\Theme\Theme;
use Symfony\Component\HttpFoundation\Response;

class OgImageController extends Controller
{
    public function guide(Guide $guide): Response
    {
        $image = (new Image)
            ->layout(new GitHubBasic)
            ->theme($this->rtfmTheme())
            ->title($guide->title)
            ->description(str($guide->tldr ?? $guide->content)->limit(100)->stripTags()->toString())
            ->url(parse_url(config('app.url'), PHP_URL_HOST))
            ->border(BorderPosition::Bottom, 20, '#3b82f6');

        return response($image->toString(), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function category(Category $category): Response
    {
        $guideCount = $category->guides()->count();

        $image = (new Image)
            ->layout(new GitHubBasic)
            ->theme($this->rtfmTheme())
            ->title($category->name)
            ->description("{$guideCount} ".str('guide')->plural($guideCount).' available')
            ->url(parse_url(config('app.url'), PHP_URL_HOST))
            ->border(BorderPosition::Bottom, 20, '#8b5cf6');

        return response($image->toString(), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function user(User $user): Response
    {
        $image = (new Image)
            ->layout(new GitHubBasic)
            ->theme($this->rtfmTheme())
            ->title($user->name)
            ->description($user->bio ?? 'RTFM Community Member')
            ->url(parse_url(config('app.url'), PHP_URL_HOST))
            ->border(BorderPosition::Bottom, 20, '#10b981');

        return response($image->toString(), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    protected function rtfmTheme(): Theme
    {
        return new Theme(
            accent: '#3b82f6',
            background: ThemeBackground::ViaPride,
            base: '#0f172a',
            baseText: '#f1f5f9',
            description: '#cbd5e1',
            tag: '#1e293b',
            tagText: '#e2e8f0',
            title: '#f8fafc',
            titleFont: 'Inter',
            descriptionFont: 'Inter',
            callToActionBackground: '#3b82f6',
            callToActionColor: '#ffffff',
        );
    }
}
