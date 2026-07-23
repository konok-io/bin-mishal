<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Customer::with(['user', 'assignedTo']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $customers = $query->latest()->paginate($this->perPage($request));

        return $this->paginate($customers);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp,
                'nationality' => $request->nationality,
                'city' => $request->city,
                'address' => $request->address,
                'user_type' => 'customer',
                'status' => 'active',
            ]);

            $user->assignRole('customer');

            $customer = Customer::create([
                'user_id' => $user->id,
                'customer_code' => Customer::generateCode(),
                'company_name' => $request->company_name,
                'source' => $request->source,
                'assigned_to' => $request->user()->id,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return $this->success($customer->load('user'), 'Customer created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to create customer: ' . $e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $customer = Customer::with(['user', 'assignedTo', 'bookings', 'visaApplications'])->findOrFail($id);

        return $this->success($customer);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($customer->user_id)],
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:100',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $customer->user->update($request->only([
                'name', 'email', 'phone', 'whatsapp', 'nationality', 'city', 'address'
            ]));

            $customer->update($request->only([
                'company_name', 'source', 'assigned_to', 'notes'
            ]));

            DB::commit();

            return $this->success($customer->load('user', 'assignedTo'), 'Customer updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to update customer: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        if ($customer->bookings()->exists()) {
            return $this->error('Cannot delete customer with existing bookings', 422);
        }

        DB::beginTransaction();

        try {
            $customer->user->delete();
            $customer->delete();

            DB::commit();

            return $this->success(null, 'Customer deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to delete customer', 500);
        }
    }

    public function documents(int $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        $documents = $customer->documents()->latest()->get();

        return $this->success($documents);
    }

    public function bookings(int $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        $bookings = $customer->bookings()->with(['issuedBy'])->latest()->paginate($this->perPage);

        return $this->paginate($bookings);
    }

    public function payments(int $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        $payments = $customer->payments()->latest()->paginate($this->perPage);

        return $this->paginate($payments);
    }
}
