<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Page;
use App\Models\Service;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap
     */
    public function index(): Response
    {
        $posts = Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = PostCategory::where('is_active', true)->get();
        $pages = Page::where('is_active', true)->get();
        $jobs = Job::where('status', 'published')->get();

        $content = view('sitemap', compact('posts', 'categories', 'pages', 'jobs'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
            'charset' => 'utf-8',
        ]);
    }

    /**
     * Generate posts sitemap
     */
    public function posts(): Response
    {
        $posts = Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->get();

        $content = view('sitemap-posts', compact('posts'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
            'charset' => 'utf-8',
        ]);
    }

    /**
     * Generate categories sitemap
     */
    public function categories(): Response
    {
        $categories = PostCategory::where('is_active', true)->get();

        $content = view('sitemap-categories', compact('categories'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
            'charset' => 'utf-8',
        ]);
    }
}
