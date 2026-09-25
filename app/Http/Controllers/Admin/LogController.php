<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

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

        return view('admin.logs.index', compact('logs'));
    }

    public function show(AuditLog $log)
    {
        $log->load('user');
        return view('admin.logs.show', compact('log'));
    }

    public function export(Request $request)
    {
        $logs = AuditLog::with('user');

        if ($request->filled('date_from')) {
            $logs->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $logs->where('created_at', '<=', $request->date_to);
        }

        $logsData = $logs->get();

        // Generate CSV
        $csv = "ID,User,Action,Description,Created At\n";
        foreach ($logsData as $log) {
            $userName = $log->user?->name ?? 'Deleted User';
            $csv .= "{$log->id},\"{$userName}\",{$log->action},\"{$log->description}\",{$log->created_at}\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'logs-' . now()->format('Y-m-d-H-i-s') . '.csv');
    }
}
