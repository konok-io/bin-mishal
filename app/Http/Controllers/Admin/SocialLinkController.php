<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index(Request $request)
    {
        $query = SocialLink::latest();

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        if ($request->filled('status')) {
            $query->where('is_visible', $request->status === 'visible');
        }

        $links = $query->paginate(25);

        return view('admin.social-links.index', compact('links'));
    }

    public function create()
    {
        $platforms = array_keys(SocialLink::PLATFORMS);
        return view('admin.social-links.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string',
            'name' => 'required|array',
            'name.en' => 'required|string',
            'name.bn' => 'nullable|string',
            'name.ar' => 'nullable|string',
            'icon' => 'nullable|string',
            'url' => 'required|url',
            'color' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_visible' => 'boolean',
        ]);

        SocialLink::create($validated);

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Social Link created successfully.');
    }

    public function edit($id)
    {
        $link = SocialLink::findOrFail($id);
        $platforms = array_keys(SocialLink::PLATFORMS);

        return view('admin.social-links.edit', compact('link', 'platforms'));
    }

    public function update(Request $request, $id)
    {
        $link = SocialLink::findOrFail($id);

        $validated = $request->validate([
            'platform' => 'required|string',
            'name' => 'required|array',
            'name.en' => 'required|string',
            'name.bn' => 'nullable|string',
            'name.ar' => 'nullable|string',
            'icon' => 'nullable|string',
            'url' => 'required|url',
            'color' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_visible' => 'boolean',
        ]);

        $link->update($validated);

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Social Link updated successfully.');
    }

    public function destroy($id)
    {
        $link = SocialLink::findOrFail($id);
        $link->delete();

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Social Link deleted successfully.');
    }
}
