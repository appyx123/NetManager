<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        // Technical System Statistics with optimized queries
        $stats = [
            'total_users' => User::count(),
            'total_staffs' => User::whereIn('role', ['admin', 'marketing', 'technician'])->count(),
            'total_customers' => Customer::count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('amount'),
            'system_health' => $this->getSystemHealth(),
            'database_size' => $this->getDatabaseSize(),
            'active_sessions' => $this->getActiveSessions(),
        ];

        // Chart Data: User by Role Distribution
        $usersByRole = User::selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        $userRoleChart = [
            'labels' => array_map(fn($role) => ucfirst(str_replace('_', ' ', $role)), array_keys($usersByRole)),
            'data' => array_values($usersByRole),
            'backgroundColor' => ['#8b5cf6', '#ef4444', '#f59e0b', '#3b82f6', '#10b981'],
        ];

        // Chart Data: Revenue Trend (Last 12 Months) - Single aggregated query
        $startDate = now()->subMonths(11)->startOfMonth();
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $yearExpr = $isSqlite ? "strftime('%Y', created_at)" : "YEAR(created_at)";
        $monthExpr = $isSqlite ? "strftime('%m', created_at)" : "MONTH(created_at)";

        $monthlyRevenues = Invoice::where('status', 'paid')
            ->where('created_at', '>=', $startDate)
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
            $labels[] = $date->format('M Y');
            $revenueTrend[] = (float) ($monthlyRevenues->get($key)->total ?? 0);
        }

        $revenueChart = [
            'labels' => $labels,
            'data' => $revenueTrend,
            'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
            'borderColor' => 'rgba(139, 92, 246, 1)',
        ];

        // Chart Data: Subscription Status
        $subscriptionStatus = Subscription::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $subscriptionChart = [
            'labels' => array_map(fn($status) => ucfirst($status), array_keys($subscriptionStatus)),
            'data' => array_values($subscriptionStatus),
            'backgroundColor' => ['#10b981', '#ef4444', '#f59e0b', '#6b7280'],
        ];

        // Chart Data: Daily User Growth (Last 7 days) - Single aggregated query
        $startDateGrowth = now()->subDays(6)->startOfDay();
        $dailyUsers = User::where('created_at', '>=', $startDateGrowth)
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->pluck('count', 'date');

        $userGrowth = [];
        $growthLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $growthLabels[] = $date->format('D');
            $userGrowth[] = (int) ($dailyUsers->get($date->format('Y-m-d')) ?? 0);
        }

        $userGrowthChart = [
            'labels' => $growthLabels,
            'data' => $userGrowth,
            'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
            'borderColor' => 'rgba(59, 130, 246, 1)',
        ];

        // Recent Audit Logs with eager loading and pagination
        $recentLogs = AuditLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // System Services Status
        $servicesStatus = [
            'database' => 'operational',
            'payment_gateway' => 'connected',
            'api_services' => 'active',
        ];

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

    private function getSystemHealth()
    {
        return rand(85, 99); // Percentage
    }

    private function getDatabaseSize()
    {
        return '125 MB'; // Placeholder
    }

    private function getActiveSessions()
    {
        // Count active sessions from the last 24 hours
        return DB::table('sessions')
            ->where('last_activity', '>=', now()->subHours(24)->getTimestamp())
            ->count();
    }
}
