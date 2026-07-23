<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['user', 'assignedTo']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'user_type' => 'customer',
            'status' => 'active',
        ]);

        $user->assignRole('customer');

        Customer::create([
            'user_id' => $user->id,
            'customer_code' => Customer::generateCode(),
            'assigned_to' => auth()->id(),
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully');
    }

    public function show(int $id)
    {
        $customer = Customer::with(['user', 'assignedTo', 'bookings', 'visaApplications'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(int $id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $customer->user_id,
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
        ]);

        $customer->user->update($request->only(['name', 'email', 'phone']));
        $customer->update($request->only(['company_name']));

        return redirect()->route('admin.customers.show', $id)->with('success', 'Customer updated successfully');
    }

    public function destroy(int $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->user->delete();
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully');
    }
}
