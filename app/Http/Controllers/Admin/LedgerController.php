<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LedgerEntry;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $query = LedgerEntry::with('account');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        if ($request->has('account_id') && $request->account_id) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->has('entry_type') && $request->entry_type) {
            $query->where('entry_type', $request->entry_type);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('entry_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('entry_date', '<=', $request->date_to);
        }

        $entries = $query->orderBy('entry_date', 'desc')->orderBy('created_at', 'desc')->paginate(30);
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code')->get();
        $entryTypes = LedgerEntry::ENTRY_TYPES;

        return view('admin.ledger-entries.index', compact('entries', 'accounts', 'entryTypes'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code')->get();
        $entryTypes = LedgerEntry::ENTRY_TYPES;
        return view('admin.ledger-entries.create', compact('accounts', 'entryTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:chart_of_accounts,id',
            'entry_type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'reference' => 'nullable|string|max:100',
            'sourceable_type' => 'nullable|string',
            'sourceable_id' => 'nullable|integer',
        ]);

        LedgerEntry::create($validated);

        return redirect()->route('ledger-entries.index')
            ->with('success', 'Ledger entry created successfully.');
    }

    public function show(LedgerEntry $ledgerEntry)
    {
        $ledgerEntry->load('account');
        return view('admin.ledger-entries.show', compact('ledgerEntry'));
    }

    public function edit(LedgerEntry $ledgerEntry)
    {
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code')->get();
        $entryTypes = LedgerEntry::ENTRY_TYPES;
        return view('admin.ledger-entries.edit', compact('ledgerEntry', 'accounts', 'entryTypes'));
    }

    public function update(Request $request, LedgerEntry $ledgerEntry)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:chart_of_accounts,id',
            'entry_type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'reference' => 'nullable|string|max:100',
        ]);

        $ledgerEntry->update($validated);

        return redirect()->route('ledger-entries.index')
            ->with('success', 'Ledger entry updated successfully.');
    }

    public function destroy(LedgerEntry $ledgerEntry)
    {
        if ($ledgerEntry->is_locked) {
            return redirect()->route('ledger-entries.index')
                ->with('error', 'Cannot delete a locked entry.');
        }

        $ledgerEntry->delete();

        return redirect()->route('ledger-entries.index')
            ->with('success', 'Ledger entry deleted successfully.');
    }
}
