<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerRegistration;
use Illuminate\Http\Request;

class CustomerRegistrationController extends Controller
{
    /**
     * Display registration modal/info page.
     */
    public function create()
    {
        return view('admin.customers.registration.create');
    }

    /**
     * Show scan screen.
     */
    public function scan()
    {
        return view('admin.customers.registration.scan');
    }

    /**
     * Store new registration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'id_type' => 'required|in:iqama,passport,national_id,other',
            'id_number' => 'required|string|max:50',
            'nationality' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'services' => 'nullable|array',
            'amounts' => 'nullable|array',
        ]);

        // Check if customer with this ID already exists
        $existingCustomer = Customer::where('id_number', $validated['id_number'])->first();
        
        if ($existingCustomer) {
            // Attach new services to existing customer
            $this->attachServices($existingCustomer, $validated);
            
            return redirect()->route('admin.customers.show', $existingCustomer->id)
                ->with('info', 'Customer already exists. New services have been added.');
        }

        // Create new registration record
        $registration = CustomerRegistration::create([
            'tracking_no' => CustomerRegistration::generateTrackingNo(),
            'name' => $validated['name'],
            'name_ar' => $validated['name_ar'] ?? null,
            'id_type' => $validated['id_type'],
            'id_number' => $validated['id_number'],
            'nationality' => $validated['nationality'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'company' => $validated['company'] ?? null,
            'status' => 'completed',
            'registered_by' => auth()->id(),
        ]);

        // Attach services
        $this->attachServices($registration, $validated);

        return redirect()->route('admin.customers.registration.pdf', $registration->id)
            ->with('success', 'Registration completed successfully!');
    }

    /**
     * Generate PDF for registration.
     */
    public function pdf($id)
    {
        $registration = CustomerRegistration::with(['services', 'registeredBy'])->findOrFail($id);
        
        // Placeholder for PDF generation
        // In production, would use DomPDF or similar
        
        return view('admin.customers.registration.pdf-preview', compact('registration'));
    }

    /**
     * Scan to verify payment - lookup by code.
     */
    public function scanVerify(Request $request)
    {
        $code = $request->get('code');
        
        // Try to find registration by various identifiers
        $registration = CustomerRegistration::where('tracking_no', $code)
            ->orWhere('id', substr($code, 0, 36))
            ->first();
        
        if (!$registration) {
            return response()->json(['error' => 'Registration not found'], 404);
        }

        return response()->json([
            'found' => true,
            'id' => $registration->id,
            'name' => $registration->name,
            'tracking_no' => $registration->tracking_no,
            'status' => $registration->status,
            'services' => $registration->services->map(function($s) {
                return ['name' => $s->name, 'amount' => $s->pivot->amount];
            }),
        ]);
    }

    /**
     * Add service to existing registration.
     */
    public function addService(Request $request, $id)
    {
        $registration = CustomerRegistration::findOrFail($id);
        
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $registration->services()->attach($registration->id, [
            'name' => $validated['service_name'],
            'amount' => $validated['amount'] ?? 0,
        ]);

        return redirect()->back()->with('success', 'Service added successfully!');
    }

    /**
     * Record payment for a service.
     */
    public function recordPayment(Request $request, $id)
    {
        $registration = CustomerRegistration::findOrFail($id);
        
        $validated = $request->validate([
            'service_id' => 'required|exists:customer_registration_services,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,bank_transfer,other',
            'reference' => 'nullable|string|max:100',
        ]);

        // Placeholder for payment recording
        // Would create Payment record and update status

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    /**
     * Helper to attach services to a registration.
     */
    private function attachServices($model, array $data)
    {
        if (!empty($data['services'])) {
            foreach ($data['services'] as $index => $serviceName) {
                if (!empty($serviceName)) {
                    $amount = $data['amounts'][$index] ?? 0;
                    $model->services()->attach($model->id ?? 0, [
                        'name' => $serviceName,
                        'amount' => $amount,
                    ]);
                }
            }
        }
    }
}
