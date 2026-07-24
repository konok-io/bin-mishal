<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class EmployeeControllerAdmin extends Controller
{
    public function index()
    {
        $employees = User::role('employee')
            ->with(['branch', 'employee'])
            ->paginate(20);
        $branches = Branch::where('status', 'active')->get();
        return view('admin.employees.index', compact('employees', 'branches'));
    }

    public function create()
    {
        $branches = Branch::where('status', 'active')->get();
        return view('admin.employees.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
            'employee_code' => 'required|unique:employees,employee_code',
            'designation' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'salary_type' => 'required|in:hourly,monthly',
            'salary' => 'required_if:salary_type,monthly|numeric|min:0',
            'hourly_rate' => 'required_if:salary_type,hourly|numeric|min:0',
            'joining_date' => 'nullable|date',
            'iqama_no' => 'nullable|string|max:50',
            'passport_no' => 'nullable|string|max:50',
            'branch_id' => 'nullable|exists:branches,id',
            'status' => 'required|in:active,inactive,terminated',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => $request->status === 'active' ? 'active' : 'inactive',
            'branch_id' => $request->branch_id,
        ]);

        $employeeRole = Role::firstOrCreate(['name' => 'employee'], ['guard_name' => 'web']);
        $user->assignRole($employeeRole);

        Employee::create([
            'user_id' => $user->id,
            'employee_code' => $request->employee_code,
            'designation' => $request->designation,
            'department' => $request->department,
            'joining_date' => $request->joining_date,
            'salary' => $request->salary_type === 'monthly' ? $request->salary : 0,
            'salary_type' => $request->salary_type,
            'hourly_rate' => $request->salary_type === 'hourly' ? $request->hourly_rate : null,
            'iqama_no' => $request->iqama_no,
            'passport_no' => $request->passport_no,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully!');
    }

    public function edit($id)
    {
        $employee = User::role('employee')->with('employee')->findOrFail($id);
        $branches = Branch::where('status', 'active')->get();
        return view('admin.employees.edit', compact('employee', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $user = User::role('employee')->findOrFail($id);
        $employee = $user->employee;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
            'employee_code' => 'required|unique:employees,employee_code,' . $employee->id,
            'designation' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'salary_type' => 'required|in:hourly,monthly',
            'salary' => 'required_if:salary_type,monthly|numeric|min:0',
            'hourly_rate' => 'required_if:salary_type,hourly|numeric|min:0',
            'joining_date' => 'nullable|date',
            'iqama_no' => 'nullable|string|max:50',
            'passport_no' => 'nullable|string|max:50',
            'branch_id' => 'nullable|exists:branches,id',
            'status' => 'required|in:active,inactive,terminated',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status === 'active' ? 'active' : 'inactive',
            'branch_id' => $request->branch_id,
        ];

        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        $employee->update([
            'employee_code' => $request->employee_code,
            'designation' => $request->designation,
            'department' => $request->department,
            'joining_date' => $request->joining_date,
            'salary' => $request->salary_type === 'monthly' ? $request->salary : 0,
            'salary_type' => $request->salary_type,
            'hourly_rate' => $request->salary_type === 'hourly' ? $request->hourly_rate : null,
            'iqama_no' => $request->iqama_no,
            'passport_no' => $request->passport_no,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::role('employee')->findOrFail($id);
        $user->employee->delete();
        $user->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully!');
    }
}
