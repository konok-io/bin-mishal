<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = ChartOfAccount::with('parent');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        $accounts = $query->orderBy('code')->paginate(25);
        $types = ChartOfAccount::TYPES;
        $categories = ChartOfAccount::CATEGORIES;

        return view('admin.chart-of-accounts.index', compact('accounts', 'types', 'categories'));
    }

    public function create()
    {
        $types = ChartOfAccount::TYPES;
        $categories = ChartOfAccount::CATEGORIES;
        $normalBalances = ChartOfAccount::NORMAL_BALANCES;
        $parents = ChartOfAccount::whereNull('parent_id')->orderBy('code')->get();
        return view('admin.chart-of-accounts.create', compact('types', 'categories', 'normalBalances', 'parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:chart_of_accounts,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'category' => 'nullable|string',
            'normal_balance' => 'required|in:debit,credit',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;

        ChartOfAccount::create($validated);

        return redirect()->route('admin.chart-of-accounts.index')
            ->with('success', 'Chart of Account created successfully.');
    }

    public function show(ChartOfAccount $chartOfAccount)
    {
        $chartOfAccount->load('parent', 'children');
        return view('admin.chart-of-accounts.show', compact('chartOfAccount'));
    }

    public function edit(ChartOfAccount $chartOfAccount)
    {
        $types = ChartOfAccount::TYPES;
        $categories = ChartOfAccount::CATEGORIES;
        $normalBalances = ChartOfAccount::NORMAL_BALANCES;
        $parents = ChartOfAccount::whereNull('parent_id')
            ->where('id', '!=', $chartOfAccount->id)
            ->orderBy('code')->get();
        return view('admin.chart-of-accounts.edit', compact('chartOfAccount', 'types', 'categories', 'normalBalances', 'parents'));
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:chart_of_accounts,code,' . $chartOfAccount->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'category' => 'nullable|string',
            'normal_balance' => 'required|in:debit,credit',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $chartOfAccount->update($validated);

        return redirect()->route('admin.chart-of-accounts.index')
            ->with('success', 'Chart of Account updated successfully.');
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        if ($chartOfAccount->is_system) {
            return redirect()->route('admin.chart-of-accounts.index')
                ->with('error', 'Cannot delete a system account.');
        }

        if ($chartOfAccount->children()->count() > 0) {
            return redirect()->route('admin.chart-of-accounts.index')
                ->with('error', 'Cannot delete account with child accounts.');
        }

        if ($chartOfAccount->ledgerEntries()->count() > 0) {
            return redirect()->route('admin.chart-of-accounts.index')
                ->with('error', 'Cannot delete account with ledger entries.');
        }

        $chartOfAccount->delete();

        return redirect()->route('admin.chart-of-accounts.index')
            ->with('success', 'Chart of Account deleted successfully.');
    }

    public function initializeSystemAccounts()
    {
        $systemAccounts = ChartOfAccount::getSystemAccounts();

        foreach ($systemAccounts as $account) {
            ChartOfAccount::firstOrCreate(
                ['code' => $account['code']],
                $account + ['is_active' => true, 'is_system' => true, 'sort_order' => 0]
            );
        }

        return redirect()->route('admin.chart-of-accounts.index')
            ->with('success', 'System accounts initialized successfully.');
    }
}
