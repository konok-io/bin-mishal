<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = auth()->user()->customer->documents()
            ->latest()
            ->paginate(10);

        return view('portal.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('portal.documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'document_number' => 'nullable|string|max:50',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:today',
            'issuing_country' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $document = auth()->user()->customer->documents()->create($validated);

        if ($request->hasFile('file')) {
            $document->addMedia($request->file('file'))->toMediaCollection('documents');
        }

        return redirect()->route('portal.documents.show', $document)
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(int $id)
    {
        $document = Document::where('customer_id', auth()->user()->customer->id)
            ->findOrFail($id);

        return view('portal.documents.show', compact('document'));
    }
}
