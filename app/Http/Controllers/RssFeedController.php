<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;
use Carbon\Carbon;

class RssFeedController extends Controller
{
    /**
     * Generate RSS feed
     */
    public function index(): Response
    {
        $posts = Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit(50)
            ->get();

        $settings = app(\App\Models\Setting::class)::getMultiple([
            'site_name',
            'site_tagline',
            'site_description',
            'site_url',
            'logo_url',
        ]);

        $siteName = $settings['site_name'] ?? config('app.name');
        $siteUrl = $settings['site_url'] ?? url('/');
        $siteDescription = $settings['site_description'] ?? 'Travel news, tips and updates';

        $content = view('feed.rss', compact('posts', 'siteName', 'siteUrl', 'siteDescription'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/rss+xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Generate Atom feed
     */
    public function atom(): Response
    {
        $posts = Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit(50)
            ->get();

        $settings = app(\App\Models\Setting::class)::getMultiple([
            'site_name',
            'site_url',
        ]);

        $siteName = $settings['site_name'] ?? config('app.name');
        $siteUrl = $settings['site_url'] ?? url('/');
        $updatedAt = $posts->first()?->updated_at ?? now();

        $content = view('feed.atom', compact('posts', 'siteName', 'siteUrl', 'updatedAt'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/atom+xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
