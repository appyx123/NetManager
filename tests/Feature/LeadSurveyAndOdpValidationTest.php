<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\NetworkAsset;
use App\Models\Package;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadSurveyAndOdpValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $marketing;
    private User $technician;
    private Package $package;
    private NetworkAsset $odp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->marketing = User::factory()->create(['role' => 'marketing', 'is_active' => true]);
        $this->technician = User::factory()->create(['role' => 'technician', 'is_active' => true]);
        $this->package = Package::create([
            'name' => 'Paket Fiber 20M',
            'speed_mbps' => 20,
            'price' => 200000,
            'installation_fee' => 50000,
        ]);
        $this->odp = NetworkAsset::create([
            'name' => 'ODP-MKS-01',
            'type' => 'ODP',
            'location' => 'Jl. Pengayoman Blok A',
            'brand' => 'Fiberhome',
            'is_active' => true,
            'port_capacity' => 8,
            'odp_available_ports' => 4,
        ]);
    }

    public function test_marketing_can_request_survey_creates_survey_ticket_without_customer_or_invoice(): void
    {
        $lead = Lead::create([
            'marketing_id' => $this->marketing->id,
            'package_id' => $this->package->id,
            'name' => 'Calon Pelanggan Survey',
            'phone' => '081234567890',
            'address' => 'Jl. Lokasi Survey No. 10',
            'status' => 'prospek',
        ]);

        $response = $this->actingAs($this->marketing)
            ->post(route('marketing.leads.survey', $lead));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Status prospek menjadi survey
        $this->assertEquals('survey', $lead->fresh()->status);

        // Tiket survey berhasil dibuat
        $this->assertDatabaseHas('tickets', [
            'lead_id' => $lead->id,
            'customer_id' => null,
            'type' => 'survey',
            'status' => 'open',
        ]);

        // Tidak ada Customer dan Invoice yang dibuat
        $this->assertDatabaseMissing('customers', [
            'lead_id' => $lead->id,
        ]);
        $this->assertEquals(0, \App\Models\Invoice::count());
    }

    public function test_convert_fails_when_odp_is_full(): void
    {
        $fullOdp = NetworkAsset::create([
            'name' => 'ODP-FULL-01',
            'type' => 'ODP',
            'location' => 'Jl. Penuh No. 1',
            'brand' => 'Fiberhome',
            'is_active' => true,
            'port_capacity' => 8,
            'odp_available_ports' => 0,
        ]);

        $lead = Lead::create([
            'marketing_id' => $this->marketing->id,
            'package_id' => $this->package->id,
            'name' => 'Calon Pelanggan Full ODP',
            'phone' => '081234567891',
            'address' => 'Jl. Penuh No. 1',
            'status' => 'prospek',
        ]);

        $response = $this->actingAs($this->marketing)
            ->post(route('marketing.leads.convert', $lead), [
                'odp_id' => $fullOdp->id,
                'odp_port' => 'Port 1',
            ]);

        $response->assertSessionHas('error', 'Kapasitas ODP target sudah penuh. Silakan pilih ODP lain atau lakukan ekspansi jaringan.');

        // Kuota tidak berubah
        $this->assertEquals(0, $fullOdp->fresh()->odp_available_ports);

        // Tidak ada customer atau invoice baru
        $this->assertDatabaseMissing('customers', [
            'lead_id' => $lead->id,
        ]);
        $this->assertEquals('prospek', $lead->fresh()->status);
    }

    public function test_convert_succeeds_decrements_odp_available_ports_and_creates_records(): void
    {
        $lead = Lead::create([
            'marketing_id' => $this->marketing->id,
            'package_id' => $this->package->id,
            'name' => 'Pelanggan Baru Siap Pasang',
            'phone' => '081234567892',
            'address' => 'Jl. Sukses No. 5',
            'status' => 'prospek',
        ]);

        $initialPorts = $this->odp->odp_available_ports;

        $response = $this->actingAs($this->marketing)
            ->post(route('marketing.leads.convert', $lead), [
                'odp_id' => $this->odp->id,
                'odp_port' => 'Port 3',
            ]);

        $response->assertRedirect(route('marketing.leads.index'));
        $response->assertSessionHas('success');

        // Kuota berkurang 1
        $this->assertEquals($initialPorts - 1, $this->odp->fresh()->odp_available_ports);

        // Data lead berstatus aktif
        $lead->refresh();
        $this->assertEquals('aktif', $lead->status);
        $this->assertEquals($this->odp->id, $lead->odp_id);
        $this->assertEquals('Port 3', $lead->odp_port);

        // Tiket instalasi tercipta dengan ODP terikat
        $this->assertDatabaseHas('tickets', [
            'lead_id' => $lead->id,
            'odp_id' => $this->odp->id,
            'odp_port' => 'Port 3',
            'type' => 'installation',
            'status' => 'open',
        ]);

        // Customer & Invoice perdana terbit
        $this->assertDatabaseHas('customers', [
            'lead_id' => $lead->id,
        ]);
        $this->assertDatabaseHas('invoices', [
            'amount' => 250000, // 200.000 + 50.000
            'status' => 'unpaid',
        ]);
    }

    public function test_technician_survey_updates_odp_recommendation_on_lead(): void
    {
        $lead = Lead::create([
            'marketing_id' => $this->marketing->id,
            'package_id' => $this->package->id,
            'name' => 'Survey Candidate',
            'phone' => '081234567893',
            'address' => 'Jl. Lokasi No. 7',
            'status' => 'survey',
        ]);

        $ticket = Ticket::create([
            'lead_id' => $lead->id,
            'customer_id' => null,
            'technician_id' => $this->technician->id,
            'type' => 'survey',
            'status' => 'in_progress',
            'subject' => 'Survey Lokasi: ' . $lead->name,
        ]);

        $response = $this->actingAs($this->technician)
            ->put(route('technician.process.update', $ticket), [
                'survey_status' => 'layak',
                'survey_notes' => 'Jalur FO aman, port 4 kosong',
                'odp_id' => $this->odp->id,
                'odp_port' => 'Port 4',
            ]);

        $response->assertRedirect(route('technician.process.index'));

        // Rekomendasi ODP tersimpan di Lead
        $lead->refresh();
        $this->assertEquals($this->odp->id, $lead->odp_id);
        $this->assertEquals('Port 4', $lead->odp_port);
    }

    public function test_failed_installation_releases_odp_available_ports(): void
    {
        $lead = Lead::create([
            'marketing_id' => $this->marketing->id,
            'package_id' => $this->package->id,
            'name' => 'Batal Pasang',
            'phone' => '081234567894',
            'address' => 'Jl. Batal No. 9',
            'status' => 'aktif',
        ]);

        $user = User::factory()->create(['role' => 'customer', 'is_active' => true]);
        $customer = Customer::create([
            'user_id' => $user->id,
            'lead_id' => $lead->id,
            'customer_code' => 'CUST-BATAL',
            'phone_number' => $lead->phone,
            'address_installation' => $lead->address,
        ]);

        // Simulasikan tiket instalasi yang telah memesan port ODP (kuota sudah berkurang)
        $this->odp->update(['odp_available_ports' => 3]);

        $ticket = Ticket::create([
            'customer_id' => $customer->id,
            'lead_id' => $lead->id,
            'technician_id' => $this->technician->id,
            'odp_id' => $this->odp->id,
            'odp_port' => 'Port 2',
            'odp_released' => false,
            'type' => 'installation',
            'status' => 'in_progress',
            'subject' => 'Pasang Baru',
        ]);

        // Teknisi melaporkan instalasi gagal/batal
        $response = $this->actingAs($this->technician)
            ->put(route('technician.process.update', $ticket), [
                'installation_status' => 'gagal',
                'technical_notes' => 'Pelanggan membatalkan pemasangan karena pindah rumah',
            ]);

        $response->assertRedirect(route('technician.process.index'));

        // Kuota ODP dikembalikan (+1 menjadi 4)
        $this->assertEquals(4, $this->odp->fresh()->odp_available_ports);
        $this->assertTrue($ticket->fresh()->odp_released);
    }
}
