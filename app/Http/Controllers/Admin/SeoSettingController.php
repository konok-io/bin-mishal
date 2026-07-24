<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    public function index(Request $request)
    {
        $query = SeoSetting::latest();

        if ($request->filled('page')) {
            $query->where('page', $request->page);
        }

        if ($request->filled('locale')) {
            $query->where('locale', $request->locale);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $settings = $query->paginate(25);

        return view('admin.seo-settings.index', compact('settings'));
    }

    public function create()
    {
        $pages = SeoSetting::PAGES;
        $locales = ['en', 'bn', 'ar'];
        
        return view('admin.seo-settings.create', compact('pages', 'locales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page' => 'required|string',
            'locale' => 'required|in:en,bn,ar',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'robots' => 'nullable|string',
            'schema_markup' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        SeoSetting::create($validated);

        return redirect()->route('admin.seo-settings.index')
            ->with('success', 'SEO Setting created successfully.');
    }

    public function edit($id)
    {
        $setting = SeoSetting::findOrFail($id);
        $pages = SeoSetting::PAGES;
        $locales = ['en', 'bn', 'ar'];

        return view('admin.seo-settings.edit', compact('setting', 'pages', 'locales'));
    }

    public function update(Request $request, $id)
    {
        $setting = SeoSetting::findOrFail($id);

        $validated = $request->validate([
            'page' => 'required|string',
            'locale' => 'required|in:en,bn,ar',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'robots' => 'nullable|string',
            'schema_markup' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $setting->update($validated);

        return redirect()->route('admin.seo-settings.index')
            ->with('success', 'SEO Setting updated successfully.');
    }

    public function destroy($id)
    {
        $setting = SeoSetting::findOrFail($id);
        $setting->delete();

        return redirect()->route('admin.seo-settings.index')
            ->with('success', 'SEO Setting deleted successfully.');
    }
}
