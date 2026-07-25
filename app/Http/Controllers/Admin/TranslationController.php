<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        $query = Translation::with('updatedByUser')->latest();

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('key', 'like', '%' . $request->search . '%')
                  ->orWhere('value_en', 'like', '%' . $request->search . '%')
                  ->orWhere('value_bn', 'like', '%' . $request->search . '%')
                  ->orWhere('value_ar', 'like', '%' . $request->search . '%');
            });
        }

        $translations = $query->paginate(50);

        // Get unique groups for filter
        $groups = Translation::distinct()->pluck('group')->filter()->sort()->values();

        return view('admin.translations.index', compact('translations', 'groups'));
    }

    public function create()
    {
        return view('admin.translations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group' => 'required|string|max:100',
            'key' => 'required|string|max:255|unique:translations,key',
            'value_en' => 'nullable|string',
            'value_bn' => 'nullable|string',
            'value_ar' => 'nullable|string',
            'source' => 'nullable|string|in:code,manual,imported',
        ]);

        // Set default source if not provided
        $validated['source'] = $validated['source'] ?? 'manual';

        $translation = Translation::create($validated);
        $translation->updateStatus();
        $translation->save();

        Translation::clearCache();

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation created successfully.');
    }

    public function edit($id)
    {
        $translation = Translation::findOrFail($id);
        return view('admin.translations.edit', compact('translation'));
    }

    public function update(Request $request, $id)
    {
        $translation = Translation::findOrFail($id);

        $validated = $request->validate([
            'group' => 'required|string|max:100',
            'key' => 'required|string|max:255|unique:translations,key,' . $id,
            'value_en' => 'nullable|string',
            'value_bn' => 'nullable|string',
            'value_ar' => 'nullable|string',
            'source' => 'nullable|string|in:code,manual,imported',
        ]);

        // Set default source if not provided
        $validated['source'] = $validated['source'] ?? 'manual';

        $translation->update($validated);
        $translation->updateStatus();
        $translation->updated_by = Auth::id();
        $translation->save();

        Translation::clearCache();

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation updated successfully.');
    }

    public function destroy($id)
    {
        $translation = Translation::findOrFail($id);
        $translation->delete();

        Translation::clearCache();

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation deleted successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $translations = $request->input('translations', []);

        foreach ($translations as $id => $values) {
            $translation = Translation::find($id);
            if ($translation) {
                $translation->value_bn = $values['value_bn'] ?? null;
                $translation->value_en = $values['value_en'] ?? null;
                $translation->value_ar = $values['value_ar'] ?? null;
                $translation->updateStatus();
                $translation->updated_by = Auth::id();
                $translation->save();
            }
        }

        Translation::clearCache();

        return redirect()->back()
            ->with('success', 'Translations updated successfully.');
    }

    public function export()
    {
        $translations = Translation::all();

        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['Group', 'Key', 'English', 'Bengali', 'Arabic']);

        foreach ($translations as $t) {
            $csv->insertOne([
                $t->group,
                $t->key,
                $t->value_en,
                $t->value_bn,
                $t->value_ar,
            ]);
        }

        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="translations.csv"',
        ]);
    }
}
