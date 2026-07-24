<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee.user');

        if ($request->date) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', Carbon::today());
        }

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(30);
        $employees = Employee::with('user')->where('status', 'active')->get();

        return view('admin.attendance.index', compact('attendances', 'employees'));
    }

    public function create()
    {
        $employees = Employee::with('user')->where('status', 'active')->get();
        return view('admin.attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:present,absent,late,half_day,leave',
            'notes' => 'nullable|string',
        ]);

        Attendance::create($validated);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance recorded successfully!');
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:present,absent,late,half_day,leave',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return redirect()->back()->with('success', 'Attendance updated successfully!');
    }
}
