<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CMS\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        $query = Page::with(['creator', 'parent'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('template')) {
            $query->where('template', $request->template);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
            });
        }

        $pages = $query->paginate(25);

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $templates = Page::TEMPLATES;
        $parentPages = Page::whereNull('parent_id')->orderBy('title')->get();
        
        return view('admin.pages.create', compact('templates', 'parentPages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|array',
            'title.en' => 'required|string',
            'title.bn' => 'nullable|string',
            'title.ar' => 'nullable|string',
            'slug' => 'nullable|array',
            'slug.en' => 'nullable|string',
            'slug.bn' => 'nullable|string',
            'slug.ar' => 'nullable|string',
            'parent_id' => 'nullable|exists:pages,id',
            'template' => 'required|string',
            'is_homepage' => 'boolean',
            'show_header' => 'boolean',
            'show_footer' => 'boolean',
            'show_breadcrumb' => 'boolean',
            'order' => 'nullable|integer',
            'status' => 'required|string',
            'published_at' => 'nullable|date',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $templates = Page::TEMPLATES;
        $heroTypes = Page::HERO_TYPES;
        $parentPages = Page::whereNull('parent_id')->where('id', '!=', $id)->orderBy('title')->get();

        return view('admin.pages.edit', compact('page', 'templates', 'heroTypes', 'parentPages'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|array',
            'title.en' => 'required|string',
            'title.bn' => 'nullable|string',
            'title.ar' => 'nullable|string',
            'slug' => 'nullable|array',
            'slug.en' => 'nullable|string',
            'slug.bn' => 'nullable|string',
            'slug.ar' => 'nullable|string',
            'parent_id' => 'nullable|exists:pages,id',
            'template' => 'required|string',
            'hero_type' => 'nullable|string',
            'hero_image' => 'nullable|string',
            'hero_title' => 'nullable|array',
            'hero_subtitle' => 'nullable|array',
            'meta_title' => 'nullable|array',
            'meta_description' => 'nullable|array',
            'is_homepage' => 'boolean',
            'show_header' => 'boolean',
            'show_footer' => 'boolean',
            'show_breadcrumb' => 'boolean',
            'noindex' => 'boolean',
            'order' => 'nullable|integer',
            'status' => 'required|string',
            'published_at' => 'nullable|date',
        ]);

        $validated['updated_by'] = Auth::id();
        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        if ($page->is_system) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Cannot delete system pages.');
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }
}
