<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with(['employee', 'processor']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('month') && $request->month) {
            $query->whereMonth('period_start', date('m', strtotime($request->month)))
                  ->whereYear('period_start', date('Y', strtotime($request->month)));
        }

        $payrolls = $query->orderBy('period_start', 'desc')->paginate(20);
        $employees = Employee::where('is_active', true)->orderBy('employee_id')->get();
        $statuses = Payroll::STATUSES;

        return view('admin.payroll.index', compact('payrolls', 'employees', 'statuses'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)->orderBy('employee_id')->get();
        $statuses = Payroll::STATUSES;
        return view('admin.payroll.create', compact('employees', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'basic_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'late_days' => 'nullable|integer|min:0',
            'late_deduction' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,processed,approved,paid',
        ]);

        $allowancesJson = $request->input('allowances_json', '{}');
        $deductionsJson = $request->input('deductions_json', '{}');
        $allowances = json_decode($allowancesJson, true) ?? [];
        $deductions = json_decode($deductionsJson, true) ?? [];
        $basicSalary = $validated['basic_salary'];
        $bonus = $validated['bonus'] ?? 0;
        $lateDeduction = $validated['late_deduction'] ?? 0;

        $totalEarnings = $basicSalary + array_sum($allowances) + $bonus;
        $totalDeductions = array_sum($deductions) + $lateDeduction;
        $netSalary = $totalEarnings - $totalDeductions;

        $payroll = Payroll::create([
            'employee_id' => $validated['employee_id'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'basic_salary' => $basicSalary,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'bonus' => $bonus,
            'late_days' => $validated['late_days'] ?? 0,
            'late_deduction' => $lateDeduction,
            'net_salary' => $netSalary,
            'status' => $validated['status'],
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.payroll.index')->with('success', 'Payroll created successfully.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load(['employee', 'processor']);
        return view('admin.payroll.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        $employees = Employee::where('is_active', true)->orderBy('employee_id')->get();
        $statuses = Payroll::STATUSES;
        return view('admin.payroll.edit', compact('payroll', 'employees', 'statuses'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'basic_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'late_days' => 'nullable|integer|min:0',
            'late_deduction' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,processed,approved,paid',
        ]);

        $allowancesJson = $request->input('allowances_json', '{}');
        $deductionsJson = $request->input('deductions_json', '{}');
        $allowances = json_decode($allowancesJson, true) ?? [];
        $deductions = json_decode($deductionsJson, true) ?? [];
        $basicSalary = $validated['basic_salary'];
        $bonus = $validated['bonus'] ?? 0;
        $lateDeduction = $validated['late_deduction'] ?? 0;

        $totalEarnings = $basicSalary + array_sum($allowances) + $bonus;
        $totalDeductions = array_sum($deductions) + $lateDeduction;
        $netSalary = $totalEarnings - $totalDeductions;

        $payroll->update([
            'employee_id' => $validated['employee_id'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'basic_salary' => $basicSalary,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'bonus' => $bonus,
            'late_days' => $validated['late_days'] ?? 0,
            'late_deduction' => $lateDeduction,
            'net_salary' => $netSalary,
            'status' => $validated['status'],
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.payroll.index')->with('success', 'Payroll updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return redirect()->route('admin.payroll.index')->with('error', 'Cannot delete a paid payroll record.');
        }
        
        $payroll->delete();
        return redirect()->route('admin.payroll.index')->with('success', 'Payroll deleted successfully.');
    }
}
