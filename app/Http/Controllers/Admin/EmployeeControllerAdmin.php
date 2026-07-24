<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;

class EmployeeControllerAdmin extends Controller
{
    public function index()
    {
        $employees = User::role('employee')->with('branch')->paginate(20);
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
        // Store logic here
        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully!');
    }

    public function show($id)
    {
        $employee = User::role('employee')->findOrFail($id);
        return view('admin.employees.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = User::role('employee')->findOrFail($id);
        $branches = Branch::where('status', 'active')->get();
        return view('admin.employees.edit', compact('employee', 'branches'));
    }

    public function update(Request $request, $id)
    {
        // Update logic here
        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy($id)
    {
        User::role('employee')->findOrFail($id)->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully!');
    }
}
