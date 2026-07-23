<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\VisaApplication;
use App\Models\VisaType;
use App\Models\VisaApplicationDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisaController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = VisaApplication::with(['customer.user', 'visaType', 'assignedTo']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('visa_type_id')) {
            $query->where('visa_type_id', $request->visa_type_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $applications = $query->latest()->paginate($this->perPage($request));

        return $this->paginate($applications);
    }

    public function types(): JsonResponse
    {
        $types = VisaType::active()->get();

        return $this->success($types);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'visa_type_id' => 'required|exists:visa_types,id',
            'applicant_name' => 'nullable|string|max:255',
            'passport_no' => 'nullable|string|max:50',
            'iqama_no' => 'nullable|string|max:50',
            'sponsor_name' => 'nullable|string|max:255',
            'sponsor_id' => 'nullable|string|max:50',
            'travel_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'purpose' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $visaType = VisaType::findOrFail($request->visa_type_id);

            $application = VisaApplication::create([
                'application_no' => VisaApplication::generateNo(),
                'customer_id' => $request->customer_id,
                'visa_type_id' => $request->visa_type_id,
                'applicant_name' => $request->applicant_name,
                'passport_no' => $request->passport_no,
                'iqama_no' => $request->iqama_no,
                'sponsor_name' => $request->sponsor_name,
                'sponsor_id' => $request->sponsor_id,
                'travel_date' => $request->travel_date,
                'return_date' => $request->return_date,
                'purpose' => $request->purpose,
                'government_fee' => $visaType->government_fee,
                'service_fee' => $visaType->service_fee,
                'total_amount' => $visaType->total_fee,
                'paid_amount' => 0,
                'status' => 'draft',
            ]);

            DB::commit();

            return $this->success(
                $application->load('customer.user', 'visaType'),
                'Visa application created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to create visa application: ' . $e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $application = VisaApplication::with([
            'customer.user',
            'visaType',
            'assignedTo',
            'documents',
            'statusLogs'
        ])->findOrFail($id);

        return $this->success($application);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $application = VisaApplication::findOrFail($id);

        $request->validate([
            'applicant_name' => 'nullable|string|max:255',
            'passport_no' => 'nullable|string|max:50',
            'iqama_no' => 'nullable|string|max:50',
            'sponsor_name' => 'nullable|string|max:255',
            'sponsor_id' => 'nullable|string|max:50',
            'travel_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'purpose' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $application->update($request->only([
            'applicant_name', 'passport_no', 'iqama_no', 'sponsor_name',
            'sponsor_id', 'travel_date', 'return_date', 'purpose', 'assigned_to'
        ]));

        return $this->success($application->load('customer.user', 'visaType'), 'Application updated successfully');
    }

    public function submit(int $id): JsonResponse
    {
        $application = VisaApplication::findOrFail($id);

        if ($application->status !== 'draft') {
            return $this->error('Only draft applications can be submitted');
        }

        $application->submit();

        return $this->success($application, 'Application submitted successfully');
    }

    public function approve(int $id): JsonResponse
    {
        $application = VisaApplication::findOrFail($id);

        if ($application->status !== 'government_processing') {
            return $this->error('Application is not in processing stage');
        }

        $application->approve();

        return $this->success($application, 'Application approved successfully');
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $application = VisaApplication::findOrFail($id);

        $application->reject($request->reason);

        return $this->success($application, 'Application rejected');
    }

    public function deliver(int $id): JsonResponse
    {
        $application = VisaApplication::findOrFail($id);

        if ($application->status !== 'approved') {
            return $this->error('Application is not approved yet');
        }

        $application->deliver();

        return $this->success($application, 'Application delivered successfully');
    }

    public function addDocument(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'document_type' => 'required|string|max:100',
            'file_path' => 'required|string|max:500',
            'file_name' => 'nullable|string|max:255',
            'file_size' => 'nullable|integer',
            'mime_type' => 'nullable|string|max:100',
        ]);

        $application = VisaApplication::findOrFail($id);

        $document = $application->documents()->create($request->only([
            'document_type', 'file_path', 'file_name', 'file_size', 'mime_type'
        ]));

        return $this->success($document, 'Document added successfully', 201);
    }

    public function documents(int $id): JsonResponse
    {
        $application = VisaApplication::findOrFail($id);
        $documents = $application->documents;

        return $this->success($documents);
    }

    public function verifyDocument(int $id, int $documentId): JsonResponse
    {
        $document = VisaApplicationDocument::where('visa_application_id', $id)->findOrFail($documentId);
        $document->verify();

        return $this->success($document, 'Document verified');
    }

    public function statusHistory(int $id): JsonResponse
    {
        $application = VisaApplication::findOrFail($id);
        $logs = $application->statusLogs()->with('changedBy')->get();

        return $this->success($logs);
    }

    public function stats(): JsonResponse
    {
        $stats = [
            'total' => VisaApplication::count(),
            'draft' => VisaApplication::where('status', 'draft')->count(),
            'submitted' => VisaApplication::where('status', 'submitted')->count(),
            'pending' => VisaApplication::pending()->count(),
            'approved' => VisaApplication::where('status', 'approved')->count(),
            'rejected' => VisaApplication::where('status', 'rejected')->count(),
            'total_revenue' => VisaApplication::where('status', 'approved')->sum('total_amount'),
        ];

        return $this->success($stats);
    }
}
