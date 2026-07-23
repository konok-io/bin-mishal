<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Lead::with(['assignedTo', 'convertedCustomer']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('source')) {
            $query->where('source', $request->source);
        }

        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('due_today')) {
            $query->dueToday();
        }

        $leads = $query->latest()->paginate($this->perPage($request));

        return $this->paginate($leads);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'service_interest' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:100',
            'follow_up_date' => 'nullable|date|after_or_equal:today',
            'conversion_probability' => 'nullable|integer|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {
            $lead = Lead::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp,
                'email' => $request->email,
                'service_interest' => $request->service_interest,
                'source' => $request->source,
                'status' => 'new',
                'follow_up_date' => $request->follow_up_date,
                'conversion_probability' => $request->conversion_probability ?? 50,
                'assigned_to' => $request->user()->id,
            ]);

            DB::commit();

            return $this->success($lead->load('assignedTo'), 'Lead created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to create lead: ' . $e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $lead = Lead::with(['assignedTo', 'convertedCustomer', 'activities'])->findOrFail($id);

        return $this->success($lead);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $lead = Lead::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'service_interest' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:100',
            'status' => 'nullable|in:new,contacted,qualified,converted,lost',
            'assigned_to' => 'nullable|exists:users,id',
            'follow_up_date' => 'nullable|date',
            'conversion_probability' => 'nullable|integer|min:0|max:100',
        ]);

        $lead->update($request->only([
            'name', 'phone', 'whatsapp', 'email', 'service_interest',
            'source', 'status', 'assigned_to', 'follow_up_date', 'conversion_probability'
        ]));

        return $this->success($lead->load('assignedTo'), 'Lead updated successfully');
    }

    public function convert(Request $request, int $id): JsonResponse
    {
        $lead = Lead::findOrFail($id);

        if ($lead->status === 'converted') {
            return $this->error('Lead is already converted');
        }

        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $customer = $lead->convertToCustomer([
                'company_name' => $request->company_name,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return $this->success(
                ['lead' => $lead->fresh('convertedCustomer'), 'customer' => $customer],
                'Lead converted to customer successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to convert lead: ' . $e->getMessage(), 500);
        }
    }

    public function markAsLost(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $lead = Lead::findOrFail($id);
        $lead->markAsLost($request->reason);

        return $this->success($lead, 'Lead marked as lost');
    }

    public function addActivity(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'activity_type' => 'required|string|max:100',
            'description' => 'required|string',
            'outcome' => 'nullable|string',
            'next_action' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
        ]);

        $lead = Lead::findOrFail($id);

        $activity = $lead->addActivity([
            'employee_id' => $request->user()->id,
            'activity_type' => $request->activity_type,
            'description' => $request->description,
            'outcome' => $request->outcome,
            'next_action' => $request->next_action,
            'scheduled_at' => $request->scheduled_at,
        ]);

        return $this->success($activity, 'Activity added successfully');
    }

    public function activities(int $id): JsonResponse
    {
        $lead = Lead::findOrFail($id);
        $activities = $lead->activities()->with('employee')->get();

        return $this->success($activities);
    }

    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
            'lost' => Lead::where('status', 'lost')->count(),
            'due_today' => Lead::dueToday()->count(),
            'conversion_rate' => Lead::count() > 0 
                ? round(Lead::where('status', 'converted')->count() / Lead::count() * 100, 2) 
                : 0,
        ];

        return $this->success($stats);
    }
}
