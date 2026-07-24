<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $query = Notice::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('content', 'like', '%' . $request->search . '%');
            });
        }

        $notices = $query->paginate(25);

        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        $types = Notice::TYPES;
        return view('admin.notices.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|array',
            'content.en' => 'required|string',
            'content.bn' => 'nullable|string',
            'content.ar' => 'nullable|string',
            'type' => 'required|string',
            'link_url' => 'nullable|url',
            'link_text' => 'nullable|array',
            'link_text.en' => 'nullable|string',
            'link_text.bn' => 'nullable|string',
            'link_text.ar' => 'nullable|string',
            'priority' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'visibility' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        Notice::create($validated);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice created successfully.');
    }

    public function edit($id)
    {
        $notice = Notice::findOrFail($id);
        $types = Notice::TYPES;

        return view('admin.notices.edit', compact('notice', 'types'));
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|array',
            'content.en' => 'required|string',
            'content.bn' => 'nullable|string',
            'content.ar' => 'nullable|string',
            'type' => 'required|string',
            'link_url' => 'nullable|url',
            'link_text' => 'nullable|array',
            'link_text.en' => 'nullable|string',
            'link_text.bn' => 'nullable|string',
            'link_text.ar' => 'nullable|string',
            'priority' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'visibility' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $notice->update($validated);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice updated successfully.');
    }

    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }
}
