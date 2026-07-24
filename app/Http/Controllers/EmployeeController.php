<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\ExpenseClaim;
use App\Models\ExpenseType;
use App\Services\ExpenseService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $employee = $user->employee;
        
        if (!$employee) {
            return redirect()->to(locale_route('home'));
        }

        // Today's attendance
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', today())
            ->first();

        // This month's summary
        $monthlyAttendance = Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();

        $monthlyStats = [
            'present' => $monthlyAttendance->where('status', 'present')->count(),
            'absent' => $monthlyAttendance->where('status', 'absent')->count(),
            'late' => $monthlyAttendance->where('is_late', true)->count(),
        ];

        // Latest payslip
        $latestPayslip = Payroll::where('employee_id', $employee->id)
            ->orderBy('period_end', 'desc')
            ->first();

        // Leave balance
        $leaveBalance = Leave::where('employee_id', $employee->id)
            ->whereIn('status', ['approved', 'pending'])
            ->where('end_date', '>=', now())
            ->get()
            ->groupBy('leave_type');

        $pendingLeaves = Leave::where('employee_id', $employee->id)
            ->where('status', 'pending')
            ->count();

        return view('employee.dashboard', compact(
            'user',
            'employee',
            'todayAttendance',
            'monthlyStats',
            'latestPayslip',
            'leaveBalance',
            'pendingLeaves'
        ));
    }

    public function payslips()
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->to(locale_route('home'));
        }

        $payslips = Payroll::where('employee_id', $employee->id)
            ->orderBy('period_end', 'desc')
            ->paginate(12);

        // YTD summary
        $ytdTotal = Payroll::where('employee_id', $employee->id)
            ->whereYear('period_end', now()->year)
            ->sum('net_salary');

        $ytdDeductions = Payroll::where('employee_id', $employee->id)
            ->whereYear('period_end', now()->year)
            ->get()
            ->sum(function ($p) {
                return array_sum($p->deductions ?? []);
            });

        return view('employee.payslips', compact('payslips', 'ytdTotal', 'ytdDeductions'));
    }

    public function attendance(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->to(locale_route('home'));
        }

        // Monthly summary
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        $stats = [
            'present' => $attendance->where('status', 'present')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'late' => $attendance->where('is_late', true)->count(),
        ];

        return view('employee.attendance', compact('employee', 'attendance', 'stats', 'month', 'year'));
    }

    public function leave(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->to(locale_route('home'));
        }

        $leaves = Leave::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate leave balances
        $leaveTypes = ['annual' => 21, 'sick' => 14, 'emergency' => 5, 'unpaid' => 0];
        
        $balance = [];
        foreach ($leaveTypes as $type => $total) {
            $used = Leave::where('employee_id', $employee->id)
                ->where('leave_type', $type)
                ->where('status', 'approved')
                ->whereYear('start_date', now()->year)
                ->sum('days');
            
            $balance[$type] = [
                'total' => $total,
                'used' => $used,
                'remaining' => max(0, $total - $used),
            ];
        }

        return view('employee.leave', compact('employee', 'leaves', 'balance'));
    }

    public function expenses(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->to(locale_route('home'));
        }

        // Get expense types
        $expenseTypes = ExpenseType::active()
            ->orderBy('sort_order')
            ->get();

        // Get employee's claims
        $claims = ExpenseClaim::where('employee_id', $employee->id)
            ->with('expenseType')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate stats
        $stats = [
            'approved' => ExpenseClaim::where('employee_id', $employee->id)
                ->where('status', 'approved')->sum('amount'),
            'approved_count' => ExpenseClaim::where('employee_id', $employee->id)
                ->where('status', 'approved')->count(),
            'pending' => ExpenseClaim::where('employee_id', $employee->id)
                ->whereIn('status', ['submitted', 'manager_review', 'hr_review'])->sum('amount'),
            'pending_count' => ExpenseClaim::where('employee_id', $employee->id)
                ->whereIn('status', ['submitted', 'manager_review', 'hr_review'])->count(),
            'applied' => ExpenseClaim::where('employee_id', $employee->id)
                ->where('status', 'applied_to_payroll')->sum('amount'),
            'applied_count' => ExpenseClaim::where('employee_id', $employee->id)
                ->where('status', 'applied_to_payroll')->count(),
            'rejected' => ExpenseClaim::where('employee_id', $employee->id)
                ->where('status', 'rejected')->sum('amount'),
            'rejected_count' => ExpenseClaim::where('employee_id', $employee->id)
                ->where('status', 'rejected')->count(),
        ];

        return view('employee.expenses', compact('employee', 'claims', 'stats', 'expenseTypes'));
    }

    public function storeExpense(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->to(locale_route('home'));
        }

        $validated = $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'expense_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|in:SAR,BDT,USD',
            'description' => 'required|string|max:1000',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $service = app(ExpenseService::class);

        $data = [
            'expense_type_id' => $validated['expense_type_id'],
            'expense_date' => $validated['expense_date'],
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'description' => $validated['description'],
        ];

        // Handle file upload
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $data['attachments'] = [[
                'path' => $file->store('expenses', 'public'),
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]];
        }

        $claim = $service->submitClaim($employee, $data);

        return redirect()->back()->with('success', 'Expense claim submitted successfully! Claim #: ' . $claim->claim_number);
    }

    public function downloadPayslip(Payroll $payroll)
    {
        $user = auth()->user();
        
        if ($payroll->employee_id !== $user->employee?->id) {
            abort(403);
        }

        $service = app(\App\Services\PayslipPdfService::class);
        
        return $service->generateAndDownload($payroll);
    }
}
