<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['author', 'category'])->latest();

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->paginate(25);
        $categories = PostCategory::orderBy('name')->get();

        return view('admin.blog-posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = PostCategory::orderBy('name')->get();
        return view('admin.blog-posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string',
            'excerpt_bn' => 'nullable|string',
            'excerpt_ar' => 'nullable|string',
            'content' => 'required|string',
            'content_bn' => 'nullable|string',
            'content_ar' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'category_id' => 'nullable|exists:post_categories,id',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['author_id'] = Auth::id();
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);

        Post::create($validated);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = PostCategory::orderBy('name')->get();

        return view('admin.blog-posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $id,
            'excerpt' => 'nullable|string',
            'excerpt_bn' => 'nullable|string',
            'excerpt_ar' => 'nullable|string',
            'content' => 'required|string',
            'content_bn' => 'nullable|string',
            'content_ar' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'category_id' => 'nullable|exists:post_categories,id',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $post->update($validated);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post deleted successfully.');
    }
}
