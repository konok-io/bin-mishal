<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseClaim;
use App\Models\User;
use Illuminate\Http\Request;

class ExpenseClaimController extends Controller
{
    public function index(Request $request)
    {
        $query = ExpenseClaim::with('user');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('claim_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $claims = $query->orderBy('created_at', 'desc')->paginate(25);
        $users = User::orderBy('name')->get();
        $statuses = ExpenseClaim::STATUSES;

        return view('admin.expense-claims.index', compact('claims', 'users', 'statuses'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $statuses = ExpenseClaim::STATUSES;
        $categories = ExpenseClaim::CATEGORIES;
        return view('admin.expense-claims.create', compact('users', 'statuses', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string',
            'expense_date' => 'required|date',
            'receipt_path' => 'nullable|string|max:500',
            'status' => 'required|in:pending,approved,rejected,reimbursed',
            'notes' => 'nullable|string',
        ]);

        $validated['claim_number'] = 'EC-' . date('Ymd') . '-' . str_pad(ExpenseClaim::count() + 1, 4, '0', STR_PAD_LEFT);

        ExpenseClaim::create($validated);

        return redirect()->route('admin.expense-claims.index')
            ->with('success', 'Expense claim created successfully.');
    }

    public function show(ExpenseClaim $expenseClaim)
    {
        $expenseClaim->load('user');
        return view('admin.expense-claims.show', compact('expenseClaim'));
    }

    public function edit(ExpenseClaim $expenseClaim)
    {
        $users = User::orderBy('name')->get();
        $statuses = ExpenseClaim::STATUSES;
        $categories = ExpenseClaim::CATEGORIES;
        return view('admin.expense-claims.edit', compact('expenseClaim', 'users', 'statuses', 'categories'));
    }

    public function update(Request $request, ExpenseClaim $expenseClaim)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string',
            'expense_date' => 'required|date',
            'receipt_path' => 'nullable|string|max:500',
            'status' => 'required|in:pending,approved,rejected,reimbursed',
            'notes' => 'nullable|string',
        ]);

        $expenseClaim->update($validated);

        return redirect()->route('admin.expense-claims.index')
            ->with('success', 'Expense claim updated successfully.');
    }

    public function destroy(ExpenseClaim $expenseClaim)
    {
        if ($expenseClaim->status === 'reimbursed') {
            return redirect()->route('admin.expense-claims.index')
                ->with('error', 'Cannot delete a reimbursed expense claim.');
        }

        $expenseClaim->delete();

        return redirect()->route('admin.expense-claims.index')
            ->with('success', 'Expense claim deleted successfully.');
    }
}
