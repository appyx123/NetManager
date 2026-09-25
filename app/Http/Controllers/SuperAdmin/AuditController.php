<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);

        return view('superadmin.audits.index', compact('logs'));
    }

    public function show(AuditLog $log)
    {
        return view('superadmin.audits.show', compact('log'));
    }

    public function export(Request $request)
    {
        // Export audit logs to CSV
        $logs = AuditLog::with('user');

        if ($request->filled('date_from')) {
            $logs->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $logs->where('created_at', '<=', $request->date_to);
        }

        $logsData = $logs->get();

        $csv = "ID,User,Action,IP Address,Created At\n";
        foreach ($logsData as $log) {
            $userName = $log->user?->name ?? 'Deleted User';
            $csv .= "{$log->id},\"{$userName}\",{$log->action},\"{$log->ip_address}\",{$log->created_at}\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'audit-logs-' . now()->format('Y-m-d-H-i-s') . '.csv');
    }
}
