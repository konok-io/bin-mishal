<?php

namespace App\Http\Controllers\Admin;

use App\Models\VisaApplication;
use App\Models\VisaType;
use App\Models\Customer;
use Illuminate\Http\Request;

class VisaController extends Controller
{
    public function index(Request $request)
    {
        $query = VisaApplication::with(['customer.user', 'visaType']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(15);
        return view('admin.visas.index', compact('applications'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        $visaTypes = VisaType::active()->get();
        return view('admin.visas.create', compact('customers', 'visaTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'visa_type_id' => 'required|exists:visa_types,id',
            'applicant_name' => 'nullable|string|max:255',
            'passport_no' => 'nullable|string|max:50',
        ]);

        $visaType = VisaType::findOrFail($request->visa_type_id);

        VisaApplication::create([
            'application_no' => VisaApplication::generateNo(),
            'customer_id' => $request->customer_id,
            'visa_type_id' => $request->visa_type_id,
            'applicant_name' => $request->applicant_name,
            'passport_no' => $request->passport_no,
            'government_fee' => $visaType->government_fee,
            'service_fee' => $visaType->service_fee,
            'total_amount' => $visaType->total_fee,
            'paid_amount' => 0,
            'status' => 'draft',
        ]);

        return redirect()->route('admin.visas.index')->with('success', 'Visa application created');
    }

    public function show(int $id)
    {
        $application = VisaApplication::with([
            'customer.user',
            'visaType',
            'assignedTo',
            'documents',
            'statusLogs'
        ])->findOrFail($id);

        return view('admin.visas.show', compact('application'));
    }

    public function submit(int $id)
    {
        $application = VisaApplication::findOrFail($id);
        $application->submit();
        return redirect()->back()->with('success', 'Application submitted');
    }

    public function approve(int $id)
    {
        $application = VisaApplication::findOrFail($id);
        $application->approve();
        return redirect()->back()->with('success', 'Application approved');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        $application = VisaApplication::findOrFail($id);
        $application->reject($request->reason);
        return redirect()->back()->with('success', 'Application rejected');
    }

    public function deliver(int $id)
    {
        $application = VisaApplication::findOrFail($id);
        $application->deliver();
        return redirect()->back()->with('success', 'Application delivered');
    }
}
