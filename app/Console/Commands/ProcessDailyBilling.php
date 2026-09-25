<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\NetworkService;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessDailyBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:process-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process daily billing: send WA reminders and isolate overdue customers.';

    /**
     * Execute the console command.
     */
    public function handle(NetworkService $networkService, WhatsappService $whatsappService): int
    {
        $today = Carbon::today();
        $this->info("Starting daily billing process for {$today->toDateString()}...");

        // -----------------------------------------------------------------
        // 1. H-3 Reminders
        // -----------------------------------------------------------------
        $h3Date = $today->copy()->addDays(3)->toDateString();
        $invoicesH3 = Invoice::with(['subscription.customer.user', 'subscription.package'])
            ->where('status', 'unpaid')
            ->whereDate('due_date', $h3Date)
            ->get();

        $this->info("Found {$invoicesH3->count()} unpaid invoices due in 3 days (H-3).");
        foreach ($invoicesH3 as $invoice) {
            try {
                $whatsappService->sendBillingReminder($invoice, 3);
                $this->line("Sent H-3 reminder for Invoice #{$invoice->invoice_number}");
            } catch (Throwable $e) {
                Log::error("Error sending H-3 reminder for Invoice #{$invoice->id}: " . $e->getMessage());
                $this->error("Failed sending H-3 reminder for Invoice #{$invoice->invoice_number}: " . $e->getMessage());
            }
        }

        // -----------------------------------------------------------------
        // 2. H-1 Reminders
        // -----------------------------------------------------------------
        $h1Date = $today->copy()->addDay()->toDateString();
        $invoicesH1 = Invoice::with(['subscription.customer.user', 'subscription.package'])
            ->where('status', 'unpaid')
            ->whereDate('due_date', $h1Date)
            ->get();

        $this->info("Found {$invoicesH1->count()} unpaid invoices due in 1 day (H-1).");
        foreach ($invoicesH1 as $invoice) {
            try {
                $whatsappService->sendBillingReminder($invoice, 1);
                $this->line("Sent H-1 reminder for Invoice #{$invoice->invoice_number}");
            } catch (Throwable $e) {
                Log::error("Error sending H-1 reminder for Invoice #{$invoice->id}: " . $e->getMessage());
                $this->error("Failed sending H-1 reminder for Invoice #{$invoice->invoice_number}: " . $e->getMessage());
            }
        }

        // -----------------------------------------------------------------
        // 2b. H-0 (Hari-H Jatuh Tempo) Reminders
        // -----------------------------------------------------------------
        $todayDate = $today->toDateString();
        $invoicesH0 = Invoice::with(['subscription.customer.user', 'subscription.package'])
            ->where('status', 'unpaid')
            ->whereDate('due_date', $todayDate)
            ->get();

        $this->info("Found {$invoicesH0->count()} unpaid invoices due today (H-0).");
        foreach ($invoicesH0 as $invoice) {
            try {
                $whatsappService->sendBillingReminder($invoice, 0);
                $this->line("Sent H-0 reminder for Invoice #{$invoice->invoice_number}");
            } catch (Throwable $e) {
                Log::error("Error sending H-0 reminder for Invoice #{$invoice->id}: " . $e->getMessage());
                $this->error("Failed sending H-0 reminder for Invoice #{$invoice->invoice_number}: " . $e->getMessage());
            }
        }

        // -----------------------------------------------------------------
        // 3. Overdue Invoices & Automated Isolation
        // -----------------------------------------------------------------
        $overdueInvoices = Invoice::with(['subscription.customer.user', 'subscription.package'])
            ->where('status', 'unpaid')
            ->whereDate('due_date', '<', $today->toDateString())
            ->whereHas('subscription.customer', function ($query) {
                $query->where('is_isolated', false);
            })
            ->get();

        $this->info("Found {$overdueInvoices->count()} overdue unpaid invoices requiring isolation.");
        foreach ($overdueInvoices as $invoice) {
            $subscription = $invoice->subscription;
            $customer = $subscription?->customer;

            if (!$subscription || !$customer) {
                continue;
            }

            try {
                // Database updates wrapped in transaction
                DB::transaction(function () use ($customer, $subscription) {
                    $customer->update(['is_isolated' => true]);
                    $subscription->update(['status' => 'isolated']);
                });

                // Disable access on MikroTik router
                $networkService->disableCustomer($subscription);

                // Send isolation notice via WhatsApp
                $whatsappService->sendIsolationNotice($invoice);

                $this->line("Isolated Customer #{$customer->id} ({$customer->customer_code}) for overdue Invoice #{$invoice->invoice_number}");
            } catch (Throwable $e) {
                Log::error("Error isolating customer for Invoice #{$invoice->id}: " . $e->getMessage());
                $this->error("Failed isolating Customer #{$customer->id} for Invoice #{$invoice->invoice_number}: " . $e->getMessage());
            }
        }

        $this->info('Daily billing process completed successfully.');
        return self::SUCCESS;
    }
}
