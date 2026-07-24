<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryItem::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->paginate(25);

        return view('admin.gallery.index', compact('items'));
    }

    public function create()
    {
        $types = GalleryItem::TYPES;
        return view('admin.gallery.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:photo,video',
            'title' => 'required|array',
            'title.en' => 'required|string',
            'title.bn' => 'nullable|string',
            'title.ar' => 'nullable|string',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.bn' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'image' => 'nullable|string',
            'video_url' => 'nullable|url|required_if:type,video',
            'thumbnail' => 'nullable|string',
            'category' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'status' => 'boolean',
        ]);

        GalleryItem::create($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery item created successfully.');
    }

    public function edit($id)
    {
        $item = GalleryItem::findOrFail($id);
        $types = GalleryItem::TYPES;

        return view('admin.gallery.edit', compact('item', 'types'));
    }

    public function update(Request $request, $id)
    {
        $item = GalleryItem::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:photo,video',
            'title' => 'required|array',
            'title.en' => 'required|string',
            'title.bn' => 'nullable|string',
            'title.ar' => 'nullable|string',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.bn' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'image' => 'nullable|string',
            'video_url' => 'nullable|url|required_if:type,video',
            'thumbnail' => 'nullable|string',
            'category' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'status' => 'boolean',
        ]);

        $item->update($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery item updated successfully.');
    }

    public function destroy($id)
    {
        $item = GalleryItem::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery item deleted successfully.');
    }
}
