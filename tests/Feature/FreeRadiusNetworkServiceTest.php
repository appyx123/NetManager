<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Package;
use App\Models\RadAcct;
use App\Models\RadCheck;
use App\Models\RadReply;
use App\Models\Subscription;
use App\Models\Ticket;
use App\Models\User;
use App\Services\NetworkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FreeRadiusNetworkServiceTest extends TestCase
{
    use RefreshDatabase;

    private NetworkService $networkService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->networkService = new NetworkService();
    }

    private function createCustomerHelper(string $code = 'CUST-001', bool $isIsolated = false): array
    {
        $user = User::factory()->create(['role' => 'customer']);
        $package = Package::create([
            'name' => 'Fast Fiber 25M',
            'speed_mbps' => 25,
            'price' => 250000,
        ]);
        $lead = Lead::create([
            'package_id' => $package->id,
            'name' => 'Test Customer',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'status' => 'aktif',
        ]);
        $customer = Customer::create([
            'user_id' => $user->id,
            'lead_id' => $lead->id,
            'customer_code' => $code,
            'phone_number' => '081234567890',
            'address_installation' => 'Jl. Test No. 1',
            'is_isolated' => $isIsolated,
        ]);

        return [$user, $package, $lead, $customer];
    }

    public function test_add_customer_inserts_into_radcheck_and_radreply(): void
    {
        [$user, $package, $lead, $customer] = $this->createCustomerHelper('CUST-001', true);

        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'pppoe_username' => 'testuser1@net',
            'pppoe_password' => 'secret123',
            'status' => 'isolated',
            'ip_address' => '10.10.10.55/32',
        ]);
        $ticket = Ticket::create([
            'customer_id' => $customer->id,
            'type' => 'installation',
            'status' => 'resolved',
            'subject' => 'Pasang Baru',
            'device_mac' => 'AA:BB:CC:DD:EE:FF',
        ]);

        $result = $this->networkService->addCustomer($subscription, $ticket);

        $this->assertTrue($result);

        // Assert radcheck password
        $this->assertDatabaseHas('radcheck', [
            'username' => 'testuser1@net',
            'attribute' => 'Cleartext-Password',
            'op' => ':=',
            'value' => 'secret123',
        ]);

        // Assert radcheck MAC binding (Calling-Station-Id)
        $this->assertDatabaseHas('radcheck', [
            'username' => 'testuser1@net',
            'attribute' => 'Calling-Station-Id',
            'op' => '==',
            'value' => 'AA:BB:CC:DD:EE:FF',
        ]);

        // Assert radreply Mikrotik-Rate-Limit
        $this->assertDatabaseHas('radreply', [
            'username' => 'testuser1@net',
            'attribute' => 'Mikrotik-Rate-Limit',
            'op' => ':=',
            'value' => '25M/25M',
        ]);

        // Assert radreply Framed-IP-Address
        $this->assertDatabaseHas('radreply', [
            'username' => 'testuser1@net',
            'attribute' => 'Framed-IP-Address',
            'op' => ':=',
            'value' => '10.10.10.55',
        ]);

        // Assert radreply Mikrotik-Address-List ISOLIR for initial isolated state
        $this->assertDatabaseHas('radreply', [
            'username' => 'testuser1@net',
            'attribute' => 'Mikrotik-Address-List',
            'op' => ':=',
            'value' => 'ISOLIR',
        ]);
    }

    public function test_disable_customer_sets_address_list_isolir_in_radreply(): void
    {
        [$user, $package, $lead, $customer] = $this->createCustomerHelper('CUST-002', false);

        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'pppoe_username' => 'client2@net',
            'pppoe_password' => 'pass456',
            'status' => 'active',
        ]);

        $result = $this->networkService->disableCustomer($subscription);

        $this->assertTrue($result);

        $subscription->refresh();
        $customer->refresh();
        $this->assertEquals('isolated', $subscription->status);
        $this->assertTrue($customer->is_isolated);

        $this->assertDatabaseHas('radreply', [
            'username' => 'client2@net',
            'attribute' => 'Mikrotik-Address-List',
            'op' => ':=',
            'value' => 'ISOLIR',
        ]);
    }

    public function test_enable_customer_removes_address_list_isolir_from_radreply(): void
    {
        [$user, $package, $lead, $customer] = $this->createCustomerHelper('CUST-003', true);

        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'pppoe_username' => 'client3@net',
            'pppoe_password' => 'pass789',
            'status' => 'isolated',
        ]);

        // Pre-populate ISOLIR in radreply
        RadReply::create([
            'username' => 'client3@net',
            'attribute' => 'Mikrotik-Address-List',
            'op' => ':=',
            'value' => 'ISOLIR',
        ]);

        $result = $this->networkService->enableCustomer($subscription);

        $this->assertTrue($result);

        $subscription->refresh();
        $customer->refresh();
        $this->assertEquals('active', $subscription->status);
        $this->assertFalse($customer->is_isolated);

        $this->assertDatabaseMissing('radreply', [
            'username' => 'client3@net',
            'attribute' => 'Mikrotik-Address-List',
        ]);
    }

    public function test_check_status_reads_active_session_from_radacct(): void
    {
        RadAcct::create([
            'acctsessionid' => 'SESS-12345',
            'acctuniqueid' => 'UNIQ-12345',
            'username' => 'activeuser@net',
            'nasipaddress' => '10.0.0.1',
            'acctstarttime' => now()->subHours(2),
            'acctsessiontime' => 7200,
            'framedipaddress' => '10.10.10.99',
            'callingstationid' => '11:22:33:44:55:66',
        ]);

        $status = $this->networkService->checkStatus('activeuser@net');

        $this->assertEquals('online', $status['status']);
        $this->assertEquals('10.10.10.99', $status['ip_address']);
        $this->assertEquals('11:22:33:44:55:66', $status['caller_id']);
    }
}
