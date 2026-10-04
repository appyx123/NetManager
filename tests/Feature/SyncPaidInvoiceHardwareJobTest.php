<?php

namespace Tests\Feature;

use App\Jobs\SyncPaidInvoiceHardwareJob;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use App\Services\NetworkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SyncPaidInvoiceHardwareJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_paid_invoice_hardware_job_is_dispatched_via_webhook(): void
    {
        Queue::fake();

        config(['services.midtrans.server_key' => 'test-server-key']);

        $user = User::factory()->create(['role' => 'customer']);
        $package = Package::create([
            'name' => 'Test Pack 10M',
            'speed_mbps' => 10,
            'price' => 100000,
        ]);
        $lead = \App\Models\Lead::create([
            'package_id' => $package->id,
            'name' => 'Test Customer',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'status' => 'aktif',
        ]);
        $customer = Customer::create([
            'user_id' => $user->id,
            'lead_id' => $lead->id,
            'customer_code' => 'CUST-TEST1',
            'phone_number' => '081234567890',
            'address_installation' => 'Jl. Test No. 1',
        ]);
        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'status' => 'isolated',
        ]);
        $invoice = Invoice::create([
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-TEST-001',
            'amount' => 100000,
            'status' => 'unpaid',
            'due_date' => now()->addDays(7),
        ]);

        $orderId = $invoice->invoice_number;
        $statusCode = '200';
        $grossAmount = '100000.00';
        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . 'test-server-key');

        $response = $this->postJson(route('midtrans.notification'), [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(200);

        Queue::assertPushed(SyncPaidInvoiceHardwareJob::class, function ($job) use ($invoice) {
            return $job->invoice->id === $invoice->id;
        });

        $this->assertEquals('paid', $invoice->fresh()->status);
    }
}
