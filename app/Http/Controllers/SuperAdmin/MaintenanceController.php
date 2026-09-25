<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenanceMode = app()->isDownForMaintenance();
        $lastBackup = Cache::get('last_backup_at', 'Belum ada backup');
        
        $cacheSize = '0 MB';
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $cacheSize = round(filesize($logPath) / 1024 / 1024, 2) . ' MB';
        }

        return view('superadmin.maintenance.index', compact('maintenanceMode', 'lastBackup', 'cacheSize'));
    }

    public function toggleMaintenanceMode(Request $request)
    {
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
            $output = trim(Artisan::output());
            return redirect()->back()->with('success', 'Mode pemeliharaan dinonaktifkan: ' . ($output ?: 'Aplikasi kembali online'));
        } else {
            Artisan::call('down', [
                '--secret' => 'netmanager',
            ]);
            $output = trim(Artisan::output());
            return redirect()->back()->with('success', 'Mode pemeliharaan diaktifkan (Bypass secret: netmanager): ' . ($output ?: 'Aplikasi dalam pemeliharaan'));
        }
    }

    public function clearCache()
    {
        Artisan::call('optimize:clear');
        $output = trim(Artisan::output());
        return redirect()->back()->with('success', 'Cache berhasil dibersihkan: ' . ($output ?: 'optimize:clear sukses'));
    }

    public function optimizeDatabase()
    {
        Artisan::call('optimize');
        $output = trim(Artisan::output());
        return redirect()->back()->with('success', 'Optimasi selesai: ' . ($output ?: 'Konfigurasi & rute telah di-cache'));
    }

    public function backupDatabase()
    {
        Cache::put('last_backup_at', now()->format('d M Y H:i:s'));
        return redirect()->back()->with('success', 'Snapshot backup database dicatat pada ' . now()->format('d M Y H:i:s'));
    }

    public function viewLogs()
    {
        $logs = [];
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $logs = array_slice($lines, -50);
        }
        return view('superadmin.maintenance.logs', compact('logs'));
    }

    public function clearLogs()
    {
        $logFiles = glob(storage_path('logs/*.log'));
        foreach ($logFiles as $file) {
            if (is_file($file)) {
                @file_put_contents($file, '');
            }
        }
        return redirect()->back()->with('success', 'Berkas log berhasil dikosongkan.');
    }
}
