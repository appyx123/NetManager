<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MasterArea;
use App\Models\Package;
use App\Models\NetworkAsset;
use App\Models\Lead;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 0. DATA MASTER (AREA, PAKET, ASET JARINGAN)
        // ==========================================
        $areaMakassar = MasterArea::firstOrCreate(
            ['code' => 'MKS-01'],
            ['name' => 'Makassar Pusat']
        );

        $paketBasic = Package::firstOrCreate(
            ['name' => 'Home Basic 20 Mbps'],
            [
                'speed_mbps' => 20,
                'price' => 150000,
                'installation_fee' => 100000,
                'description' => 'Paket hemat cocok untuk 3-5 perangkat. Kecepatan unduh hingga 20 Mbps tanpa FUP.',
                'is_active' => true,
            ]
        );

        $paketPro = Package::firstOrCreate(
            ['name' => 'Home Pro 50 Mbps'],
            [
                'speed_mbps' => 50,
                'price' => 250000,
                'installation_fee' => 100000,
                'description' => 'Paket cepat untuk keluarga besar. Streaming 4K lancar dan bermain game tanpa lag.',
                'is_active' => true,
            ]
        );

        $routerUtama = NetworkAsset::firstOrCreate(
            ['name' => 'Router Utama (Core MKS)'],
            [
                'type' => 'Router',
                'ip_address' => '192.168.88.1',
                'location' => 'Data Center Gedung A',
                'is_active' => true,
            ]
        );

        // ==========================================
        // 2. DATA PENGGUNA (PEGAWAI)
        // ==========================================
        $superAdmin = User::updateOrCreate(
            ['email' => 'owner@netmanager.local'],
            [
                'name' => 'justrifkyy (Super Admin)',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'area_id' => $areaMakassar->id,
                'phone_number' => '08111222333',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@netmanager.local'],
            [
                'name' => 'Admin Operasional',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'area_id' => $areaMakassar->id,
                'phone_number' => '08222333444',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $marketing = User::updateOrCreate(
            ['email' => 'marketing@netmanager.local'],
            [
                'name' => 'Staf Marketing',
                'password' => Hash::make('password'),
                'role' => 'marketing',
                'area_id' => $areaMakassar->id,
                'phone_number' => '08333444555',
                'marketing_code' => 'PROMO2026',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $teknisi = User::updateOrCreate(
            ['email' => 'teknisi@netmanager.local'],
            [
                'name' => 'Teknisi Lapangan',
                'password' => Hash::make('password'),
                'role' => 'technician',
                'area_id' => $areaMakassar->id,
                'phone_number' => '08444555666',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        if (app()->environment(['local', 'testing'])) {
            // Akun contoh default untuk setiap role (hanya di lingkungan dev/test)
            foreach ([
                ['name' => 'Super Admin Demo', 'email' => 'superadmin@gmail.com', 'role' => 'super_admin', 'phone_number' => '08511111111'],
                ['name' => 'Admin Demo', 'email' => 'admin@gmail.com', 'role' => 'admin', 'phone_number' => '08522222222'],
                ['name' => 'Marketing Demo', 'email' => 'marketing@gmail.com', 'role' => 'marketing', 'phone_number' => '08533333333', 'marketing_code' => 'DEMO2026'],
                ['name' => 'Teknisi Demo', 'email' => 'teknisi@gmail.com', 'role' => 'technician', 'phone_number' => '08544444444'],
                ['name' => 'Customer Demo', 'email' => 'customer@gmail.com', 'role' => 'customer', 'phone_number' => '08555555555', 'is_active' => false],
            ] as $demoUser) {
                User::updateOrCreate(
                    ['email' => $demoUser['email']],
                    array_merge($demoUser, [
                        'password' => Hash::make('password'),
                        'area_id' => $areaMakassar->id,
                        'email_verified_at' => now(),
                        'is_active' => $demoUser['is_active'] ?? true,
                    ])
                );
            }

            // ==========================================
            // 3. WORKFLOW: PELANGGAN AKTIF (SUDAH INSTALASI)
            // ==========================================

            // A. Data Lead (Prospek Awal)
            $leadAktif = Lead::updateOrCreate(
                ['email' => 'budisantoso@example.com'],
                [
                    'marketing_id' => $marketing->id,
                    'package_id' => $paketPro->id,
                    'name' => 'Budi Santoso',
                    'phone' => '087730353029',
                    'email' => 'budisantoso@example.com',
                    'customer_type' => 'personal',
                    'address' => 'Jl. Perintis Kemerdekaan No. 10',
                    'address_installation' => 'Jl. Perintis Kemerdekaan No. 10',
                    'district' => 'Tamalanrea',
                    'city' => 'Makassar',
                    'status' => 'aktif',
                    'registered_date' => now()->subMonths(2),
                    'created_at' => now()->subMonths(2),
                ]
            );

            // B. Akun & Profil Customer
            $userCustomer = User::updateOrCreate(
                ['email' => 'budi@netmanager.local'],
                [
                    'name' => 'Budi Santoso',
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone_number' => '087730353029',
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );

            $customer = Customer::updateOrCreate(
                ['user_id' => $userCustomer->id],
                [
                    'lead_id' => $leadAktif->id,
                    'customer_code' => 'CUST-001',
                    'phone_number' => '087730353029',
                    'address_installation' => 'Jl. Perintis Kemerdekaan No. 10',
                ]
            );

            // C. Data Instalasi & Jaringan (Tersimpan di Tabel Tiket)
            Ticket::firstOrCreate(
                ['customer_id' => $customer->id, 'subject' => 'Instalasi Jaringan - Budi Santoso'],
                [
                    'technician_id' => $teknisi->id,
                    'type' => 'installation',
                    'status' => 'closed',
                    'installation_date' => now()->subMonths(2)->addDays(3),
                    'connection_type' => 'fiber',
                    'cable_length' => '45 Meter',
                    'device_type' => 'ONU ZTE',
                    'device_brand' => 'ZTE F609',
                    'device_mac' => '00:1A:2B:3C:4D:5E',
                    'router_id' => $routerUtama->id,
                    'vlan_id' => '100',
                    'odp_port' => 'Port 3',
                    'completed_at' => now()->subMonths(2)->addDays(3),
                    'created_at' => now()->subMonths(2)->addDays(2),
                ]
            );

            Ticket::updateOrCreate(
                ['customer_id' => $customer->id, 'subject' => 'Instalasi Tambahan - Budi Santoso'],
                [
                    'technician_id' => null,
                    'type' => 'installation',
                    'status' => 'open',
                    'description' => 'Instalasi tambahan untuk pengujian alur kerja teknisi.',
                    'created_at' => now(),
                ]
            );

            // D. Data Langganan & Tagihan
            $subscription = Subscription::firstOrCreate(
                ['customer_id' => $customer->id],
                [
                    'package_id' => $paketPro->id,
                    'pppoe_username' => 'budi@net',
                    'pppoe_password' => '123456',
                    'ip_address' => '10.10.10.2',
                    'installation_date' => now()->subMonths(2)->addDays(3),
                    'status' => 'active',
                ]
            );

            Invoice::updateOrCreate(
                ['invoice_number' => 'INV-' . now()->format('Ymd') . '-001'],
                [
                    'subscription_id' => $subscription->id,
                    'amount' => $paketPro->price,
                    'status' => 'unpaid',
                    'due_date' => now()->addDays(5),
                    'payment_method' => null,
                ]
            );

            Invoice::updateOrCreate(
                ['invoice_number' => 'INV-' . now()->format('Ymd') . '-002'],
                [
                    'subscription_id' => $subscription->id,
                    'amount' => $paketPro->price,
                    'status' => 'unpaid',
                    'due_date' => now()->addDays(12),
                    'payment_method' => null,
                ]
            );

            // ==========================================
            // 4. WORKFLOW: TIKET GANGGUAN (REPAIR) BARU
            // ==========================================
            Ticket::firstOrCreate(
                ['customer_id' => $customer->id, 'subject' => 'Gangguan LOS Merah - CUST-001'],
                [
                    'technician_id' => null,
                    'type' => 'repair',
                    'status' => 'open',
                    'description' => 'Lampu indikator modem LOS berkedip merah. Internet terputus total sejak kemarin sore.',
                    'created_at' => now(),
                ]
            );

            // ==========================================
            // 5. WORKFLOW: LEAD BARU (PROSPEK)
            // ==========================================
            Lead::updateOrCreate(
                ['phone' => '089876543210'],
                [
                    'marketing_id' => $marketing->id,
                    'package_id' => $paketBasic->id,
                    'name' => 'Siti Aminah',
                    'customer_type' => 'personal',
                    'address' => 'Jl. Urip Sumoharjo No. 45',
                    'district' => 'Panakkukang',
                    'city' => 'Makassar',
                    'status' => 'prospek',
                    'source' => 'Website Register',
                    'registered_date' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
