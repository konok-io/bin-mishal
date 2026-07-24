<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CMS\Menu;
use App\Models\CMS\MenuItem;
use App\Models\CMS\Page;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::latest();

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        $menus = $query->paginate(25);

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $locations = Menu::LOCATIONS;
        return view('admin.menus.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug',
            'location' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        Menu::create($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu created successfully.');
    }

    public function edit($id)
    {
        $menu = Menu::with('items')->findOrFail($id);
        $locations = Menu::LOCATIONS;
        $pages = Page::published()->get();

        return view('admin.menus.edit', compact('menu', 'locations', 'pages'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug,' . $id,
            'location' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $menu->update($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu updated successfully.');
    }

    public function destroy($id)
    {
        $menu = Menu::with('items')->findOrFail($id);
        $menu->items()->delete();
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu deleted successfully.');
    }

    public function items(Request $request, $menuId)
    {
        $menu = Menu::findOrFail($menuId);
        
        $query = $menu->items()->whereNull('parent_id')->with('children');

        if ($request->filled('search')) {
            $query->where('label', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('order')->paginate(50);

        return view('admin.menus.items', compact('menu', 'items'));
    }

    public function storeItem(Request $request, $menuId)
    {
        $menu = Menu::findOrFail($menuId);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'label_bn' => 'nullable|string|max:255',
            'label_ar' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:500',
            'page_id' => 'nullable|exists:pages,id',
            'icon' => 'nullable|string',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'nullable|integer',
            'status' => 'boolean',
        ]);

        $validated['menu_id'] = $menu->id;
        MenuItem::create($validated);

        return redirect()->back()
            ->with('success', 'Menu item created successfully.');
    }

    public function updateItem(Request $request, $menuId, $itemId)
    {
        $item = MenuItem::findOrFail($itemId);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'label_bn' => 'nullable|string|max:255',
            'label_ar' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:500',
            'page_id' => 'nullable|exists:pages,id',
            'icon' => 'nullable|string',
            'parent_id' => 'nullable',
            'order' => 'nullable|integer',
            'status' => 'boolean',
        ]);

        $item->update($validated);

        return redirect()->back()
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroyItem($menuId, $itemId)
    {
        $item = MenuItem::with('children')->findOrFail($itemId);
        
        // Delete children first
        $item->children()->delete();
        $item->delete();

        return redirect()->back()
            ->with('success', 'Menu item deleted successfully.');
    }
}
