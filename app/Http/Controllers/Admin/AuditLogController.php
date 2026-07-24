<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(25);
        $users = \App\Models\User::orderBy('name')->get();

        return view('admin.audit-logs.index', compact('logs', 'users'));
    }

    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);
        return view('admin.audit-logs.show', compact('log'));
    }

    public function destroy($id)
    {
        $log = AuditLog::findOrFail($id);
        $log->delete();

        return redirect()->route('admin.audit-logs.index')
            ->with('success', 'Audit log deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['ID', 'User', 'Action', 'Model Type', 'Model ID', 'IP Address', 'User Agent', 'Created At']);

        foreach ($logs as $log) {
            $csv->insertOne([
                $log->id,
                $log->user ? $log->user->name : 'System',
                $log->action,
                $log->model_type,
                $log->model_id,
                $log->ip_address,
                $log->user_agent,
                $log->created_at->toDateTimeString(),
            ]);
        }

        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit-logs.csv"',
        ]);
    }
}
