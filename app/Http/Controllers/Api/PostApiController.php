<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostApiController extends Controller
{
    /**
     * Get all published posts
     */
    public function index(Request $request): JsonResponse
    {
        $query = Post::with(['category', 'tags'])
            ->where('is_published', true);

        // Filter by category
        if ($request->has('category')) {
            $category = PostCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('excerpt', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        $posts = $query->orderBy('published_at', 'desc')
            ->paginate($request->get('per_page', 10));

        return response()->json($posts);
    }

    /**
     * Get featured posts
     */
    public function featured(): JsonResponse
    {
        $posts = Post::with(['category', 'tags'])
            ->where('is_published', true)
            ->where('is_featured', true)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($posts);
    }

    /**
     * Get single post by slug
     */
    public function show(string $slug): JsonResponse
    {
        $post = Post::with(['category', 'tags', 'comments'])
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $post->increment('view_count');

        return response()->json($post);
    }

    /**
     * Get related posts
     */
    public function related(Post $post): JsonResponse
    {
        $related = Post::with(['category'])
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return response()->json($related);
    }

    /**
     * Get all categories
     */
    public function categories(): JsonResponse
    {
        $categories = PostCategory::withCount('posts')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json($categories);
    }

    /**
     * Store a new comment
     */
    public function comment(Request $request, Post $post): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:1000',
        ]);

        $post->comments()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'comment' => $validated['comment'],
            'is_approved' => false, // Requires admin approval
        ]);

        return response()->json([
            'message' => 'Comment submitted successfully. It will be visible after approval.',
        ], 201);
    }
}
