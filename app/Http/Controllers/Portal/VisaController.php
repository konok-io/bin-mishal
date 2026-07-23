<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\VisaApplication;
use App\Models\VisaType;
use Illuminate\Http\Request;

class VisaController extends Controller
{
    public function index()
    {
        $applications = auth()->user()->customer->visaApplications()
            ->with('visaType')
            ->latest()
            ->paginate(10);

        return view('portal.visas.index', compact('applications'));
    }

    public function create(Request $request)
    {
        $visaType = $request->type ? VisaType::find($request->type) : null;

        $visaTypes = VisaType::where('status', 'active')->get();

        return view('portal.visas.create', compact('visaTypes', 'visaType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visa_type_id' => 'required|exists:visa_types,id',
            'applicant_name' => 'required|string|max:255',
            'passport_no' => 'required|string|max:50',
            'iqama_no' => 'nullable|string|max:50',
            'sponsor_name' => 'nullable|string|max:255',
            'travel_date' => 'nullable|date',
            'return_date' => 'nullable|date|after:travel_date',
            'purpose' => 'nullable|string',
        ]);

        $application = auth()->user()->customer->visaApplications()->create([
            'application_no' => VisaApplication::generateNo(),
            'visa_type_id' => $validated['visa_type_id'],
            'applicant_name' => $validated['applicant_name'],
            'passport_no' => $validated['passport_no'],
            'iqama_no' => $validated['iqama_no'] ?? null,
            'sponsor_name' => $validated['sponsor_name'] ?? null,
            'travel_date' => $validated['travel_date'] ?? null,
            'return_date' => $validated['return_date'] ?? null,
            'purpose' => $validated['purpose'] ?? null,
            'status' => 'draft',
        ]);

        return redirect()->route('portal.visas.show', $application)
            ->with('success', 'Application created. Please upload required documents.');
    }

    public function show(int $id)
    {
        $application = VisaApplication::where('customer_id', auth()->user()->customer->id)
            ->with(['visaType', 'documents', 'statusLogs'])
            ->findOrFail($id);

        return view('portal.visas.show', compact('application'));
    }
}
