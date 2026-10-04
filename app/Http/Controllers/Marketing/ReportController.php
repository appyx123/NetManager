<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private function resolveDateRange(string $period): array
    {
        return match ($period) {
            'this_month'    => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month'    => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'last_3_months' => [now()->subMonths(2)->startOfMonth(), now()->endOfMonth()],
            'this_year'     => [now()->startOfYear(), now()->endOfYear()],
            default         => [now()->subYears(10)->startOfDay(), now()->endOfDay()], // 'all'
        };
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $isMarketingOnly = ($user->role === 'marketing');

        $period = $request->input('period', 'all');
        $dateRange = $this->resolveDateRange($period);

        $periodLabels = [
            'all'           => 'Semua Waktu',
            'this_month'    => 'Bulan Ini',
            'last_month'    => 'Bulan Lalu',
            'last_3_months' => '3 Bulan Terakhir',
            'this_year'     => 'Tahun Ini',
        ];
        $currentPeriodLabel = $periodLabels[$period] ?? 'Semua Waktu';

        // Query dasar leads sesuai role
        $baseLeads = Lead::query();
        if ($isMarketingOnly) {
            $baseLeads->where('marketing_id', $user->id);
        }

        // Leads acquired pada periode ini
        $leadsQuery = (clone $baseLeads);
        if ($period !== 'all') {
            $leadsQuery->whereBetween('created_at', $dateRange);
        }
        $totalLeads = $leadsQuery->count();

        // Converted customer count
        $convertedQuery = (clone $baseLeads)->where('status', 'aktif');
        if ($period !== 'all') {
            $convertedQuery->whereBetween('updated_at', $dateRange);
        }
        $convertedCount = $convertedQuery->count();

        $conversionRate = $totalLeads > 0 ? round(($convertedCount / $totalLeads) * 100, 1) : 0;

        // Estimated revenue from converted customers
        $customerRevenueQuery = Customer::whereHas('lead', function ($q) use ($user, $isMarketingOnly, $period, $dateRange) {
            if ($isMarketingOnly) {
                $q->where('marketing_id', $user->id);
            }
            if ($period !== 'all') {
                $q->whereBetween('created_at', $dateRange);
            }
        })->whereHas('subscriptions', function ($sq) {
            $sq->where('status', 'active');
        })->with(['subscriptions.package', 'lead.package']);

        $totalRevenue = $customerRevenueQuery->get()->sum(function ($cust) {
            $subPrice = $cust->subscriptions->where('status', 'active')->sum(function($sub) {
                return $sub->package?->price ?? 0;
            });
            return $subPrice > 0 ? $subPrice : ($cust->lead?->package?->price ?? 0);
        });

        // Pipeline statuses (Live Funnel - real-time status seluruh prospek aktif)
        $statuses = [
            ['label' => 'Prospek Baru', 'count' => (clone $baseLeads)->where('status', 'prospek')->count(), 'color' => 'indigo'],
            ['label' => 'Tahap Survey', 'count' => (clone $baseLeads)->where('status', 'survey')->count(), 'color' => 'amber'],
            ['label' => 'Tahap Instalasi', 'count' => (clone $baseLeads)->where('status', 'instalasi')->count(), 'color' => 'purple'],
            ['label' => 'Akun Aktif', 'count' => (clone $baseLeads)->where('status', 'aktif')->count(), 'color' => 'emerald'],
        ];

        // Monthly trends (3 bulan terakhir - persentase relatif terhadap volume tertinggi)
        $monthlyTrends = [];
        $maxTrendCount = 1;
        for ($m = 2; $m >= 0; $m--) {
            $date = now()->subMonths($m);
            $count = (clone $baseLeads)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            if ($count > $maxTrendCount) {
                $maxTrendCount = $count;
            }
            $monthlyTrends[] = [
                'month' => $date->translatedFormat('F Y'),
                'count' => $count,
            ];
        }
        foreach ($monthlyTrends as &$trend) {
            $trend['percentage'] = $trend['count'] > 0 ? min(100, round(($trend['count'] / $maxTrendCount) * 100)) : 0;
        }
        unset($trend);

        // Daily activity breakdown (30 hari terakhir dari akhir periode)
        $dailyBreakdown = [];
        $endDate = $dateRange[1]->isFuture() ? now() : $dateRange[1];
        $startDate = (clone $endDate)->subDays(29)->startOfDay();

        $leadsByDate = (clone $baseLeads)
            ->whereBetween('created_at', [$startDate, (clone $endDate)->endOfDay()])
            ->selectRaw('DATE(created_at) as log_date, COUNT(*) as count')
            ->groupBy('log_date')
            ->pluck('count', 'log_date');

        $convByDate = (clone $baseLeads)
            ->where('status', 'aktif')
            ->whereBetween('updated_at', [$startDate, (clone $endDate)->endOfDay()])
            ->selectRaw('DATE(updated_at) as log_date, COUNT(*) as count')
            ->groupBy('log_date')
            ->pluck('count', 'log_date');

        for ($d = 0; $d < 30; $d++) {
            $date = (clone $endDate)->subDays($d);
            $dateStr = $date->format('Y-m-d');
            $leadsCount = (int) ($leadsByDate[$dateStr] ?? 0);
            $convCount = (int) ($convByDate[$dateStr] ?? 0);
            $rate = $leadsCount > 0 ? round(($convCount / $leadsCount) * 100, 1) : 0;
            $estRev = $convCount * 150000;

            $dailyBreakdown[] = [
                'date' => $date,
                'leads' => $leadsCount,
                'followup' => max(0, $leadsCount),
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

        return view('marketing.reports.index', compact('kpis', 'statuses', 'monthlyTrends', 'dailyBreakdown', 'period', 'currentPeriodLabel', 'periodLabels'));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $isMarketingOnly = ($user->role === 'marketing');

        $period = $request->input('period', 'all');
        $dateRange = $this->resolveDateRange($period);

        $periodLabels = [
            'all'           => 'Semua Waktu',
            'this_month'    => 'Bulan Ini',
            'last_month'    => 'Bulan Lalu',
            'last_3_months' => '3 Bulan Terakhir',
            'this_year'     => 'Tahun Ini',
        ];
        $periodLabel = $periodLabels[$period] ?? 'Semua Waktu';

        // Ambil data leads pada periode
        $leadsQuery = Lead::with('package');
        if ($isMarketingOnly) {
            $leadsQuery->where('marketing_id', $user->id);
        }
        if ($period !== 'all') {
            $leadsQuery->whereBetween('created_at', $dateRange);
        }
        $leads = $leadsQuery->latest()->get();

        $totalLeads = $leads->count();
        $convertedCount = $leads->where('status', 'aktif')->count();
        $conversionRate = $totalLeads > 0 ? round(($convertedCount / $totalLeads) * 100, 1) : 0;

        $fileName = 'laporan-kinerja-marketing-' . $period . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($leads, $periodLabel, $totalLeads, $convertedCount, $conversionRate) {
            $output = fopen('php://output', 'w');
            
            // UTF-8 BOM untuk Microsoft Excel
            fputs($output, "\xEF\xBB\xBF");

            // Header Laporan
            fputcsv($output, ['LAPORAN KINERJA MARKETING - NETMANAGER']);
            fputcsv($output, ['Periode', $periodLabel]);
            fputcsv($output, ['Tanggal Unduh', now()->format('d-m-Y H:i:s')]);
            fputcsv($output, []);

            // Ringkasan KPI
            fputcsv($output, ['RINGKASAN KPI']);
            fputcsv($output, ['Total Prospek (Leads)', $totalLeads]);
            fputcsv($output, ['Total Konversi (Akun Aktif)', $convertedCount]);
            fputcsv($output, ['Success Rate (%)', $conversionRate . '%']);
            fputcsv($output, []);

            // Tabel Data Leads
            fputcsv($output, ['DATA PROSPEK & PELANGGAN']);
            fputcsv($output, ['No', 'Nama Lengkap', 'Nomor Telepon', 'Email', 'Paket Layanan', 'Tipe', 'Alamat', 'Status Pipeline', 'Tanggal Terdaftar']);

            foreach ($leads as $index => $lead) {
                fputcsv($output, [
                    $index + 1,
                    $lead->name,
                    "'" . $lead->phone,
                    $lead->email ?? '-',
                    $lead->package?->name ?? '-',
                    strtoupper($lead->customer_type ?? 'Personal'),
                    $lead->address ?? '-',
                    ucfirst($lead->status ?? 'Prospek'),
                    $lead->created_at ? $lead->created_at->format('d-m-Y H:i') : '-',
                ]);
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
