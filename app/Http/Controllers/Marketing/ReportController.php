<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $marketingId = Auth::id();

        // Leads acquired total
        $totalLeads = Lead::where('marketing_id', $marketingId)->count();
        $convertedCount = Lead::where('marketing_id', $marketingId)
            ->whereIn('status', ['aktif', 'converted'])
            ->count();

        $conversionRate = $totalLeads > 0 ? round(($convertedCount / $totalLeads) * 100, 1) : 0;

        // Estimated revenue from converted customers
        $totalRevenue = Customer::whereHas('lead', function ($q) use ($marketingId) {
            $q->where('marketing_id', $marketingId);
        })->whereHas('subscriptions', function ($sq) {
            $sq->where('status', 'active');
        })->with('subscriptions')->get()->sum(function ($cust) {
            return $cust->subscriptions->where('status', 'active')->sum('price');
        });

        // Pipeline statuses
        $statuses = [
            ['label' => 'Prospek Baru', 'count' => Lead::where('marketing_id', $marketingId)->where('status', 'prospek')->count(), 'color' => 'indigo'],
            ['label' => 'Tahap Survey', 'count' => Lead::where('marketing_id', $marketingId)->where('status', 'survey')->count(), 'color' => 'amber'],
            ['label' => 'Tahap Instalasi', 'count' => Lead::where('marketing_id', $marketingId)->where('status', 'instalasi')->count(), 'color' => 'purple'],
            ['label' => 'Akun Aktif', 'count' => $convertedCount, 'color' => 'emerald'],
        ];

        // Monthly trends (last 3 months)
        $monthlyTrends = [];
        for ($m = 2; $m >= 0; $m--) {
            $date = now()->subMonths($m);
            $count = Lead::where('marketing_id', $marketingId)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $monthlyTrends[] = [
                'month' => $date->format('F Y'),
                'count' => $count,
                'percentage' => $totalLeads > 0 ? min(100, round(($count / max($totalLeads, 1)) * 100)) : 0,
            ];
        }

        // Daily activity breakdown (last 10 days)
        $dailyBreakdown = [];
        for ($d = 0; $d < 10; $d++) {
            $date = now()->subDays($d);
            $leadsCount = Lead::where('marketing_id', $marketingId)
                ->whereDate('created_at', $date)
                ->count();
            $convCount = Lead::where('marketing_id', $marketingId)
                ->whereIn('status', ['aktif', 'converted'])
                ->whereDate('updated_at', $date)
                ->count();
            $rate = $leadsCount > 0 ? round(($convCount / $leadsCount) * 100, 1) : 0;
            $estRev = $convCount * 299000;

            $dailyBreakdown[] = [
                'date' => $date,
                'leads' => $leadsCount,
                'followup' => rand(1, 5),
                'conversions' => $convCount,
                'rate' => $rate,
                'revenue' => $estRev,
            ];
        }

        $kpis = [
            'total_leads' => $totalLeads,
            'conversions' => $convertedCount,
            'conversion_rate' => $conversionRate,
            'revenue' => $totalRevenue,
        ];

        return view('marketing.reports.index', compact('kpis', 'statuses', 'monthlyTrends', 'dailyBreakdown'));
    }
}
