<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['assignedTo']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leads = $query->latest()->paginate(15);
        return view('admin.leads.index', compact('leads'));
    }

    public function create()
    {
        return view('admin.leads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'service_interest' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:100',
            'follow_up_date' => 'nullable|date',
        ]);

        Lead::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'service_interest' => $request->service_interest,
            'source' => $request->source,
            'status' => 'new',
            'follow_up_date' => $request->follow_up_date,
            'conversion_probability' => 50,
            'assigned_to' => auth()->id(),
        ]);

        return redirect()->route('admin.leads.index')->with('success', 'Lead created successfully');
    }

    public function show(int $id)
    {
        $lead = Lead::with(['assignedTo', 'convertedCustomer', 'activities'])->findOrFail($id);
        return view('admin.leads.show', compact('lead'));
    }

    public function update(Request $request, int $id)
    {
        $lead = Lead::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|in:new,contacted,qualified,converted,lost',
            'follow_up_date' => 'nullable|date',
        ]);

        $lead->update($request->only(['name', 'phone', 'email', 'status', 'follow_up_date']));

        return redirect()->back()->with('success', 'Lead updated successfully');
    }

    public function convert(Request $request, int $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->convertToCustomer([]);
        return redirect()->back()->with('success', 'Lead converted to customer');
    }

    public function addActivity(Request $request, int $id)
    {
        $request->validate([
            'activity_type' => 'required|string|max:100',
            'description' => 'required|string',
        ]);

        $lead = Lead::findOrFail($id);
        $lead->addActivity([
            'employee_id' => auth()->id(),
            'activity_type' => $request->activity_type,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Activity added');
    }
}
