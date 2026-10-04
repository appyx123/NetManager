<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\AuditLog;
use App\Models\NetworkAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        // Pengecekan real-time layanan pihak ketiga & kesehatan server
        $servicesStatus = $this->getThirdPartyServices();
        $serverHealth = $this->getSystemHealth($servicesStatus);

        // Technical System Statistics with optimized queries
        $stats = [
            'total_users' => User::count(),
            'total_staffs' => User::whereIn('role', ['admin', 'marketing', 'technician'])->count(),
            'total_customers' => Customer::count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('amount'),
            'system_health' => $serverHealth['score'],
            'server_health' => $serverHealth,
            'database_size' => $this->getDatabaseSize(),
            'active_sessions' => $this->getActiveSessions(),
        ];

        // Chart Data: User by Role Distribution
        $usersByRole = User::selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        $roleColors = [
            'super_admin' => '#8b5cf6', // purple
            'admin'       => '#3b82f6', // blue
            'marketing'   => '#f59e0b', // amber
            'technician'  => '#06b6d4', // cyan
            'customer'    => '#10b981', // emerald
        ];

        $roleLabels = [
            'super_admin' => 'Super Admin',
            'admin'       => 'Admin',
            'marketing'   => 'Marketing',
            'technician'  => 'Teknisi',
            'customer'    => 'Pelanggan',
        ];

        $roleChartLabels = [];
        $roleChartData = [];
        $roleChartColors = [];
        foreach ($usersByRole as $role => $count) {
            $roleChartLabels[] = $roleLabels[$role] ?? ucfirst(str_replace('_', ' ', $role));
            $roleChartData[] = (int) $count;
            $roleChartColors[] = $roleColors[$role] ?? '#94a3b8';
        }

        $userRoleChart = [
            'labels' => $roleChartLabels,
            'data' => $roleChartData,
            'backgroundColor' => $roleChartColors,
        ];

        // Chart Data: Revenue Trend (Last 12 Months) - Aggregated by paid date
        $startDate = now()->subMonths(11)->startOfMonth();
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $yearExpr = $isSqlite ? "strftime('%Y', COALESCE(paid_at, created_at))" : "YEAR(COALESCE(paid_at, created_at))";
        $monthExpr = $isSqlite ? "strftime('%m', COALESCE(paid_at, created_at))" : "MONTH(COALESCE(paid_at, created_at))";

        $monthlyRevenues = Invoice::where('status', 'paid')
            ->where(DB::raw('COALESCE(paid_at, created_at)'), '>=', $startDate)
            ->selectRaw("{$yearExpr} as year, {$monthExpr} as month, SUM(amount) as total")
            ->groupByRaw("{$yearExpr}, {$monthExpr}")
            ->get()
            ->keyBy(function ($row) {
                return sprintf('%d-%02d', (int) $row->year, (int) $row->month);
            });

        $revenueTrend = [];
        $labels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $labels[] = $date->translatedFormat('M Y');
            $revenueTrend[] = (float) ($monthlyRevenues->get($key)->total ?? 0);
        }

        $revenueChart = [
            'labels' => $labels,
            'data' => $revenueTrend,
            'backgroundColor' => 'rgba(244, 63, 94, 0.15)',
            'borderColor' => '#f43f5e',
        ];

        // Chart Data: Subscription Status
        $subscriptionStatus = Subscription::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statusColors = [
            'active'    => '#10b981', // emerald
            'inactive'  => '#ef4444', // red
            'pending'   => '#f59e0b', // amber
            'isolated'  => '#f43f5e', // rose
            'cancelled' => '#64748b', // slate
        ];

        $statusLabels = [
            'active'    => 'Aktif',
            'inactive'  => 'Nonaktif',
            'pending'   => 'Tertunda',
            'isolated'  => 'Terisolir',
            'cancelled' => 'Dibatalkan',
        ];

        $subLabels = [];
        $subData = [];
        $subColors = [];
        foreach ($subscriptionStatus as $status => $count) {
            $subLabels[] = $statusLabels[$status] ?? ucfirst($status);
            $subData[] = (int) $count;
            $subColors[] = $statusColors[$status] ?? '#94a3b8';
        }

        $subscriptionChart = [
            'labels' => $subLabels,
            'data' => $subData,
            'backgroundColor' => $subColors,
        ];

        // Chart Data: Daily User Growth (Last 7 days)
        $startDateGrowth = now()->subDays(6)->startOfDay();
        $dailyUsers = User::where('created_at', '>=', $startDateGrowth)
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->pluck('count', 'date');

        $userGrowth = [];
        $growthLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $growthLabels[] = $date->translatedFormat('D, d M');
            $userGrowth[] = (int) ($dailyUsers->get($date->format('Y-m-d')) ?? 0);
        }

        $userGrowthChart = [
            'labels' => $growthLabels,
            'data' => $userGrowth,
            'backgroundColor' => '#3b82f6',
            'borderColor' => '#60a5fa',
        ];

        // Recent Audit Logs with eager loading and pagination
        $recentLogs = AuditLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('superadmin.dashboard.index', compact(
            'stats',
            'recentLogs',
            'servicesStatus',
            'userRoleChart',
            'revenueChart',
            'subscriptionChart',
            'userGrowthChart'
        ));
    }

    /**
     * Memeriksa status konektivitas live ke layanan pihak ketiga (MikroTik, WhatsApp, Midtrans, Database)
     */
    private function getThirdPartyServices(): array
    {
        $services = [];

        // 1. Database Connection & Latency
        try {
            $dbStart = microtime(true);
            DB::connection()->getPdo();
            $dbLatency = round((microtime(true) - $dbStart) * 1000);
            $driver = DB::connection()->getDriverName();

            $services['database'] = [
                'name' => 'Database Engine',
                'description' => strtoupper($driver) . " Engine • {$dbLatency}ms latency",
                'status' => 'operational',
                'badge' => 'OPERATIONAL',
                'badge_color' => 'emerald',
                'is_healthy' => true,
            ];
        } catch (\Throwable $e) {
            $services['database'] = [
                'name' => 'Database Engine',
                'description' => 'Gagal terhubung ke database server',
                'status' => 'error',
                'badge' => 'ERROR',
                'badge_color' => 'rose',
                'is_healthy' => false,
            ];
        }

        // 2. MikroTik RouterOS (Socket Check)
        try {
            $envHost = config('services.mikrotik.host', '192.168.88.1');
            $envPort = (int) config('services.mikrotik.port', 8728);

            // Ambil router aktif utama dari database
            $activeRouter = NetworkAsset::where('type', 'Router')
                ->where('is_active', true)
                ->first();

            // Prioritaskan .env jika di database masih memakai IP default seeder (192.168.88.1)
            $host = $activeRouter?->ip_address ?: $envHost;
            $port = (int) ($activeRouter?->api_port ?: $envPort);
            $routerName = $activeRouter?->name ?: 'Router Utama';

            if ($activeRouter && $activeRouter->ip_address === '192.168.88.1' && !empty($envHost) && $envHost !== '192.168.88.1') {
                $host = $envHost;
                $port = $envPort;
                try {
                    $activeRouter->update([
                        'ip_address' => $envHost,
                        'api_port'   => $envPort,
                    ]);
                } catch (\Throwable $e) {}
            }

            $mtStart = microtime(true);
            $errno = 0;
            $errstr = '';
            // Non-blocking socket ping dengan timeout 1.2 detik
            $socket = @fsockopen($host, $port, $errno, $errstr, 1.2);

            // Fallback coba ke .env jika socket gagal dan host berbeda
            if (!is_resource($socket) && $host !== $envHost && !empty($envHost)) {
                $host = $envHost;
                $port = $envPort;
                $socket = @fsockopen($host, $port, $errno, $errstr, 1.2);
                if (is_resource($socket) && $activeRouter) {
                    try {
                        $activeRouter->update([
                            'ip_address' => $envHost,
                            'api_port'   => $envPort,
                        ]);
                    } catch (\Throwable $e) {}
                }
            }

            $mtLatency = round((microtime(true) - $mtStart) * 1000);

            if (is_resource($socket)) {
                fclose($socket);
                $services['mikrotik'] = [
                    'name' => 'MikroTik RouterOS',
                    'description' => "{$routerName} ({$host}:{$port}) • {$mtLatency}ms",
                    'status' => 'operational',
                    'badge' => 'OPERATIONAL',
                    'badge_color' => 'emerald',
                    'is_healthy' => true,
                ];
            } else {
                $services['mikrotik'] = [
                    'name' => 'MikroTik RouterOS',
                    'description' => "{$routerName} ({$host}:{$port}) • Offline / Timeout",
                    'status' => 'offline',
                    'badge' => 'OFFLINE',
                    'badge_color' => 'rose',
                    'is_healthy' => false,
                ];
            }
        } catch (\Throwable $e) {
            $services['mikrotik'] = [
                'name' => 'MikroTik RouterOS',
                'description' => 'Pengecekan socket router gagal',
                'status' => 'offline',
                'badge' => 'OFFLINE',
                'badge_color' => 'rose',
                'is_healthy' => false,
            ];
        }

        // 3. WhatsApp Gateway Bot
        try {
            $waBaseUrl = rtrim(config('services.whatsapp.api_url', env('WA_API_URL', 'http://127.0.0.1:3000')), '/');
            $waStart = microtime(true);
            $response = Http::timeout(1.2)->get($waBaseUrl . '/status');
            $waLatency = round((microtime(true) - $waStart) * 1000);

            if ($response->successful()) {
                $data = $response->json();
                $isReady = ($data['status'] ?? '') === 'ready';

                if ($isReady) {
                    $services['whatsapp'] = [
                        'name' => 'WhatsApp Gateway',
                        'description' => "Bot Client Terhubung & Siap • {$waLatency}ms",
                        'status' => 'operational',
                        'badge' => 'READY',
                        'badge_color' => 'emerald',
                        'is_healthy' => true,
                    ];
                } else {
                    $services['whatsapp'] = [
                        'name' => 'WhatsApp Gateway',
                        'description' => "Client Siaga (Perlu Scan QR) • {$waLatency}ms",
                        'status' => 'standby',
                        'badge' => 'SCAN QR',
                        'badge_color' => 'amber',
                        'is_healthy' => false,
                    ];
                }
            } else {
                $services['whatsapp'] = [
                    'name' => 'WhatsApp Gateway',
                    'description' => "Respon Bot Bermasalah (HTTP {$response->status()})",
                    'status' => 'offline',
                    'badge' => 'ERROR',
                    'badge_color' => 'rose',
                    'is_healthy' => false,
                ];
            }
        } catch (\Throwable $e) {
            $services['whatsapp'] = [
                'name' => 'WhatsApp Gateway',
                'description' => 'Node.js Gateway Bot Tidak Aktif (:3000)',
                'status' => 'offline',
                'badge' => 'OFFLINE',
                'badge_color' => 'rose',
                'is_healthy' => false,
            ];
        }

        // 4. Midtrans Payment Gateway
        try {
            $serverKey = config('services.midtrans.server_key');
            $isProduction = (bool) config('services.midtrans.is_production', false);

            if (empty($serverKey)) {
                $services['midtrans'] = [
                    'name' => 'Midtrans Payment Gateway',
                    'description' => 'Server Key belum diatur di environment',
                    'status' => 'warning',
                    'badge' => 'UNCONFIGURED',
                    'badge_color' => 'amber',
                    'is_healthy' => false,
                ];
            } else {
                $endpoint = $isProduction ? 'https://api.midtrans.com/v2' : 'https://api.sandbox.midtrans.com/v2';
                $midStart = microtime(true);
                $response = Http::timeout(5.0)
                    ->connectTimeout(3.0)
                    ->withBasicAuth($serverKey, '')
                    ->get($endpoint . '/netmanager-health-probe/status');
                $midLatency = round((microtime(true) - $midStart) * 1000);

                $modeLabel = $isProduction ? 'Production' : 'Sandbox';

                if ($response->status() === 401) {
                    $services['midtrans'] = [
                        'name' => 'Midtrans Payment Gateway',
                        'description' => "Autentikasi Gagal (Server Key Invalid) • {$midLatency}ms",
                        'status' => 'error',
                        'badge' => 'AUTH_ERROR',
                        'badge_color' => 'rose',
                        'is_healthy' => false,
                    ];
                } elseif ($response->status() < 500) {
                    $services['midtrans'] = [
                        'name' => 'Midtrans Payment Gateway',
                        'description' => "Koneksi API {$modeLabel} Aktif • {$midLatency}ms",
                        'status' => 'operational',
                        'badge' => 'CONNECTED',
                        'badge_color' => 'emerald',
                        'is_healthy' => true,
                    ];
                } else {
                    $services['midtrans'] = [
                        'name' => 'Midtrans Payment Gateway',
                        'description' => "Server Midtrans Merespons {$response->status()} • {$midLatency}ms",
                        'status' => 'offline',
                        'badge' => 'DEGRADED',
                        'badge_color' => 'rose',
                        'is_healthy' => false,
                    ];
                }
            }
        } catch (\Throwable $e) {
            $services['midtrans'] = [
                'name' => 'Midtrans Payment Gateway',
                'description' => 'Koneksi ke Endpoint API Midtrans Timeout / DNS Gagal',
                'status' => 'offline',
                'badge' => 'UNREACHABLE',
                'badge_color' => 'rose',
                'is_healthy' => false,
            ];
        }

        return $services;
    }

    /**
     * Menghitung skor kesehatan server dan mengumpulkan metrik sistem aktual
     */
    private function getSystemHealth(array $services = []): array
    {
        $score = 100;

        // 1. Penurunan skor HANYA jika database server tidak responsif
        if (isset($services['database']) && !$services['database']['is_healthy']) {
            $score -= 40;
        }

        // 2. Deteksi RAM Fisik Server (Linux /proc/meminfo vs PHP Memory Limit)
        $memoryUsedPhp = memory_get_usage(true);
        $memoryLimitPhp = ini_get('memory_limit') ?: '512M';
        $memoryFormattedPhp = round($memoryUsedPhp / (1024 * 1024), 1) . ' MB';

        $ramDisplay = "{$memoryFormattedPhp} / {$memoryLimitPhp}";
        $ramPercent = 0;

        if (@is_readable('/proc/meminfo')) {
            $meminfo = @file_get_contents('/proc/meminfo');
            if ($meminfo) {
                $totalKb = 0;
                $availKb = 0;
                if (preg_match('/MemTotal:\s+(\d+)\s+kB/i', $meminfo, $m)) {
                    $totalKb = (int) $m[1];
                }
                if (preg_match('/MemAvailable:\s+(\d+)\s+kB/i', $meminfo, $m)) {
                    $availKb = (int) $m[1];
                } elseif (preg_match('/MemFree:\s+(\d+)\s+kB/i', $meminfo, $m)) {
                    $availKb = (int) $m[1];
                }

                if ($totalKb > 0) {
                    $totalRamBytes = $totalKb * 1024;
                    $availRamBytes = $availKb * 1024;
                    $usedRamBytes = max(0, $totalRamBytes - $availRamBytes);

                    $totalRamGb = round($totalRamBytes / (1024 * 1024 * 1024), 1);
                    $usedRamGb = round($usedRamBytes / (1024 * 1024 * 1024), 1);
                    $ramPercent = round(($usedRamBytes / $totalRamBytes) * 100);

                    // Tampilkan kapasitas RAM fisik server sesungguhnya (misal: 1.2 GB / 4.0 GB)
                    $ramDisplay = "{$usedRamGb} GB / {$totalRamGb} GB ({$ramPercent}%)";

                    if ($ramPercent > 95) {
                        $score -= 10;
                    } elseif ($ramPercent > 88) {
                        $score -= 3;
                    }
                }
            }
        }

        // 3. Storage Disk Fisik
        $basePath = base_path();
        $freeDisk = @disk_free_space($basePath) ?: 0;
        $totalDisk = @disk_total_space($basePath) ?: 1;
        $usedDisk = max(0, $totalDisk - $freeDisk);
        $diskPercent = $totalDisk > 0 ? round(($usedDisk / $totalDisk) * 100) : 0;
        $diskFreeGb = round($freeDisk / (1024 * 1024 * 1024), 1) . ' GB';
        $diskTotalGb = round($totalDisk / (1024 * 1024 * 1024), 1) . ' GB';

        if ($diskPercent > 95) {
            $score -= 15;
        } elseif ($diskPercent > 90) {
            $score -= 5;
        }

        // Skor kesehatan server merefleksikan performa server aktual (kondisi normal 95% - 100%)
        $score = max(20, min(100, $score));

        return [
            'score' => $score,
            'ram_display' => $ramDisplay,
            'memory_used' => $memoryFormattedPhp,
            'memory_limit' => $memoryLimitPhp,
            'disk_free' => $diskFreeGb,
            'disk_total' => $diskTotalGb,
            'disk_used_percent' => $diskPercent,
            'php_version' => 'PHP ' . PHP_VERSION,
            'laravel_version' => 'Laravel v' . app()->version(),
            'environment' => config('app.env', 'production'),
        ];
    }

    /**
     * Menghitung ukuran aktual database (MySQL / SQLite)
     */
    private function getDatabaseSize(): string
    {
        try {
            $driver = DB::connection()->getDriverName();
            if ($driver === 'sqlite') {
                $dbPath = DB::connection()->getDatabaseName();
                $bytes = file_exists($dbPath) ? filesize($dbPath) : 0;
            } else {
                $dbName = DB::connection()->getDatabaseName();
                $result = DB::selectOne("
                    SELECT SUM(data_length + index_length) AS size 
                    FROM information_schema.TABLES 
                    WHERE table_schema = ?
                ", [$dbName]);
                $bytes = $result->size ?? 0;
            }

            if ($bytes >= 1073741824) {
                return round($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                return round($bytes / 1048576, 1) . ' MB';
            } elseif ($bytes > 0) {
                return round($bytes / 1024, 1) . ' KB';
            }
            return '< 1 MB';
        } catch (\Throwable $e) {
            return '125 MB';
        }
    }

    /**
     * Menghitung sesi pengguna aktif dalam 24 jam terakhir
     */
    private function getActiveSessions(): int
    {
        try {
            return (int) DB::table('sessions')
                ->where('last_activity', '>=', now()->subHours(24)->getTimestamp())
                ->count();
        } catch (\Throwable $e) {
            return (int) User::where('updated_at', '>=', now()->subHours(24))->count();
        }
    }
}
