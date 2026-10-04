<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Ticket;
use App\Models\NetworkAsset;
use App\Models\Package;
use App\Models\RadCheck;
use App\Models\RadReply;
use App\Models\RadAcct;
use Illuminate\Support\Facades\Log;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;
use Throwable;

class NetworkService
{
    /**
     * Membuat koneksi Client RouterOS MikroTik
     *
     * @param array $customConfig Override konfigurasi host/user/pass jika router spesifik
     * @return Client
     */
    public function getClient(array $customConfig = []): Client
    {
        $host = $customConfig['host'] ?? config('services.mikrotik.host', '192.168.88.1');
        $user = $customConfig['user'] ?? config('services.mikrotik.user', 'admin');
        $pass = $customConfig['pass'] ?? config('services.mikrotik.pass', '');
        $port = (int) ($customConfig['port'] ?? config('services.mikrotik.port', 8728));
        $timeout = (int) ($customConfig['timeout'] ?? config('services.mikrotik.timeout', 3));
        $attempts = (int) ($customConfig['attempts'] ?? config('services.mikrotik.attempts', 1));

        $config = new Config([
            'host'     => $host,
            'user'     => $user,
            'pass'     => $pass,
            'port'     => $port,
            'timeout'  => $timeout,
            'attempts' => $attempts,
        ]);

        return new Client($config);
    }

    /**
     * Mendapatkan konfigurasi koneksi router (IP, port, username, password)
     * Membaca dari objek router subscription/tiket dengan fallback ke .env
     */
    public function resolveRouterConfig(Subscription $subscription, ?Ticket $ticket = null): array
    {
        $config = [];
        $router = null;

        // 1. Prioritaskan relasi router langsung dari subscription
        if ($subscription->router_id) {
            $router = $subscription->router ?? NetworkAsset::find($subscription->router_id);
        }

        // 2. Jika belum ada di subscription, ambil dari tiket jika tersedia
        if (!$router && $ticket && $ticket->router_id) {
            $router = $ticket->router ?? NetworkAsset::find($ticket->router_id);
        }

        // 3. Fallback: Cari dari riwayat tiket instalasi customer
        if (!$router) {
            $customer = $subscription->customer;
            if ($customer) {
                $installationTicket = $customer->tickets()
                    ->whereNotNull('router_id')
                    ->latest()
                    ->with('router')
                    ->first();

                if ($installationTicket && $installationTicket->router) {
                    $router = $installationTicket->router;
                }
            }
        }

        // 4. Petakan kredensial router jika objek router ditemukan
        if ($router) {
            if (!empty($router->ip_address)) {
                $envHost = config('services.mikrotik.host');
                if ($router->ip_address === '192.168.88.1' && !empty($envHost) && $envHost !== '192.168.88.1') {
                    $config['host'] = $envHost;
                } else {
                    $config['host'] = $router->ip_address;
                }
            }
            if (!empty($router->api_username)) {
                $config['user'] = $router->api_username;
            }
            if (!empty($router->api_password)) {
                $config['pass'] = $router->api_password;
            }
            if (!empty($router->api_port)) {
                $config['port'] = (int) $router->api_port;
            }
        }

        // Fallback ke .env tetap otomatis berlaku di getClient() jika key tidak disetel
        return $config;
    }

    /**
     * Mendaftarkan akun pelanggan baru ke database FreeRADIUS (tabel radcheck & radreply)
     * Menggantikan pembuatan /ppp/secret dan /ppp/profile langsung di MikroTik.
     *
     * @param Subscription $subscription
     * @param Ticket $ticket
     * @return bool
     */
    public function addCustomer(Subscription $subscription, Ticket $ticket): bool
    {
        $username = $subscription->pppoe_username;
        $password = $subscription->pppoe_password;
        $macAddress = $ticket->device_mac ?? null;
        $package = $subscription->package ?? ($subscription->package_id ? Package::find($subscription->package_id) : null);
        $speed = $package?->speed_mbps;

        Log::info("FreeRADIUS: Memulai pendaftaran akun PPPoE untuk Subscription #{$subscription->id} ({$username})");

        if (empty($username) || empty($password)) {
            Log::warning("FreeRADIUS [addCustomer]: Gagal mendaftarkan akun, username atau password kosong.", [
                'subscription_id' => $subscription->id,
            ]);
            return false;
        }

        try {
            // 1. Simpan username & password pelanggan ke model RadCheck dengan atribut Cleartext-Password
            RadCheck::updateOrCreate(
                [
                    'username'  => $username,
                    'attribute' => 'Cleartext-Password',
                ],
                [
                    'op'    => ':=',
                    'value' => $password,
                ]
            );

            // 2. Simpan MAC Address ONT ke RadCheck dengan atribut Calling-Station-Id (fitur physical binding)
            if (!empty($macAddress)) {
                RadCheck::updateOrCreate(
                    [
                        'username'  => $username,
                        'attribute' => 'Calling-Station-Id',
                    ],
                    [
                        'op'    => '==',
                        'value' => $macAddress,
                    ]
                );
            }

            // 3. Simpan limitasi kecepatan ke model RadReply dengan atribut Mikrotik-Rate-Limit
            if (!empty($speed)) {
                RadReply::updateOrCreate(
                    [
                        'username'  => $username,
                        'attribute' => 'Mikrotik-Rate-Limit',
                    ],
                    [
                        'op'    => ':=',
                        'value' => "{$speed}M/{$speed}M",
                    ]
                );
            }

            // 4. Jika status pelanggan terisolir pada awal pendaftaran, pasang atribut Mikrotik-Address-List = ISOLIR
            if ($subscription->status === 'isolated' || ($subscription->customer && $subscription->customer->is_isolated)) {
                RadReply::updateOrCreate(
                    [
                        'username'  => $username,
                        'attribute' => 'Mikrotik-Address-List',
                    ],
                    [
                        'op'    => ':=',
                        'value' => 'ISOLIR',
                    ]
                );
            }

            // 5. Alokasi IP statis (Framed-IP-Address) jika subscription memiliki IP spesifik
            if (!empty($subscription->ip_address)) {
                $cleanIp = trim(explode('/', $subscription->ip_address)[0]);
                RadReply::updateOrCreate(
                    [
                        'username'  => $username,
                        'attribute' => 'Framed-IP-Address',
                    ],
                    [
                        'op'    => ':=',
                        'value' => $cleanIp,
                    ]
                );
            }

            Log::info("FreeRADIUS [addCustomer]: Akun PPPoE {$username} berhasil disimpan di database RADIUS (Rate-limit: " . ($speed ? "{$speed}M/{$speed}M" : "none") . ", MAC: " . ($macAddress ?? "none") . ").");
            return true;

        } catch (Throwable $e) {
            Log::error("FreeRADIUS Exception [addCustomer]: Gagal menyimpan akun ke database RADIUS: " . $e->getMessage(), [
                'subscription_id' => $subscription->id,
                'ticket_id'       => $ticket->id,
                'username'        => $username,
                'device_mac'      => $macAddress,
            ]);
            return false;
        }
    }

    /**
     * Mengaktifkan kembali akses PPPoE pelanggan (Proses Aktivasi/Lunas)
     * - Hapus baris Mikrotik-Address-List yang bernilai ISOLIR pada model RadReply
     * - Tendang sesi aktif di MikroTik (/ppp/active/remove) agar modem reconnect dan membaca status aktif
     *
     * @param Subscription $subscription
     * @return bool
     */
    public function enableCustomer(Subscription $subscription): bool
    {
        $username = $subscription->pppoe_username;

        Log::info("FreeRADIUS: Memulai aktivasi layanan untuk Subscription #{$subscription->id} ({$username})");

        if (empty($username)) {
            Log::warning("FreeRADIUS [enableCustomer]: Username PPPoE kosong untuk Subscription #{$subscription->id}.");
            return false;
        }

        try {
            // 1. Update status subscription dan customer di database utama
            $subscription->update(['status' => 'active']);
            if ($subscription->customer) {
                $subscription->customer->update(['is_isolated' => false]);
            }

            // 2. Hapus baris Mikrotik-Address-List (ISOLIR) pada model RadReply
            RadReply::where('username', $username)
                ->where('attribute', 'Mikrotik-Address-List')
                ->delete();

            Log::info("FreeRADIUS: Atribut Mikrotik-Address-List (ISOLIR) berhasil dihapus dari radreply untuk {$username}.");

            // 3. Mekanisme 'tendang' sesi aktif di MikroTik via /ppp/active/remove
            // Modem pelanggan akan disconnect dan reconnect seketika dengan otorisasi baru (bebas isolir).
            $this->kickActiveSession($username, $subscription);

            Log::info("FreeRADIUS: Aktivasi sukses untuk Subscription #{$subscription->id}.");
            return true;

        } catch (Throwable $e) {
            Log::error("FreeRADIUS Exception [enableCustomer]: Gagal memproses aktivasi: " . $e->getMessage(), [
                'subscription_id' => $subscription->id,
                'username'        => $username,
            ]);
            return false;
        }
    }

    /**
     * Menonaktifkan akses PPPoE pelanggan (Proses Isolir)
     * - Tambahkan/perbarui record di RadReply dengan atribut Mikrotik-Address-List dan nilai ISOLIR
     * - Eksekusi /ppp/active/remove di MikroTik untuk memutus sesi aktif & memaksa autentikasi ulang
     *
     * @param Subscription $subscription
     * @return bool
     */
    public function disableCustomer(Subscription $subscription): bool
    {
        $username = $subscription->pppoe_username;

        Log::info("FreeRADIUS: Memulai isolir/pemutusan untuk Subscription #{$subscription->id} ({$username})");

        if (empty($username)) {
            Log::warning("FreeRADIUS [disableCustomer]: Username PPPoE kosong untuk Subscription #{$subscription->id}.");
            return false;
        }

        try {
            // 1. Update status subscription dan customer di database utama
            $subscription->update(['status' => 'isolated']);
            if ($subscription->customer) {
                $subscription->customer->update(['is_isolated' => true]);
            }

            // 2. Tambahkan / perbarui record di RadReply dengan atribut Mikrotik-Address-List = ISOLIR
            RadReply::updateOrCreate(
                [
                    'username'  => $username,
                    'attribute' => 'Mikrotik-Address-List',
                ],
                [
                    'op'    => ':=',
                    'value' => 'ISOLIR',
                ]
            );

            Log::info("FreeRADIUS: Atribut Mikrotik-Address-List=ISOLIR berhasil disimpan di radreply untuk {$username}.");

            // 3. Mekanisme 'tendang' sesi aktif di MikroTik via /ppp/active/remove
            // Memaksa router memutuskan sesi koneksi yang sedang berjalan sehingga modem reconnect
            // dan MikroTik memasukkan IP pelanggan ke address-list ISOLIR sesuai respon RADIUS.
            $this->kickActiveSession($username, $subscription);

            Log::info("FreeRADIUS: Isolir sukses untuk Subscription #{$subscription->id}.");
            return true;

        } catch (Throwable $e) {
            Log::error("FreeRADIUS Exception [disableCustomer]: Gagal memproses isolir: " . $e->getMessage(), [
                'subscription_id' => $subscription->id,
                'username'        => $username,
            ]);
            return false;
        }
    }

    /**
     * Memutus sesi aktif PPPoE di MikroTik (/ppp/active/remove)
     * Mekanisme 'kick' agar router memaksa modem ONT pelanggan dial-up ulang
     * dan membaca atribut terbaru dari database FreeRADIUS.
     *
     * @param string $username
     * @param Subscription|null $subscription
     * @param Ticket|null $ticket
     * @return bool
     */
    public function kickActiveSession(string $username, ?Subscription $subscription = null, ?Ticket $ticket = null): bool
    {
        if (empty($username)) {
            return false;
        }

        try {
            $routerConfig = $subscription ? $this->resolveRouterConfig($subscription, $ticket) : [];
            $client = $this->getClient($routerConfig);

            $printActive = (new Query('/ppp/active/print'))
                ->where('name', $username);
            $activeSessions = $client->query($printActive)->read();

            if (!empty($activeSessions)) {
                foreach ($activeSessions as $session) {
                    if (isset($session['.id'])) {
                        $kickQuery = (new Query('/ppp/active/remove'))
                            ->equal('.id', $session['.id']);
                        $client->query($kickQuery)->read();
                        Log::info("MikroTik: Sesi aktif PPPoE {$username} diputus (kicked) via API.");
                    }
                }
            }

            return true;
        } catch (Throwable $e) {
            // Toleransi kegagalan koneksi fisik router agar tidak menggagalkan mutasi database
            Log::warning("MikroTik [kickActiveSession]: Gagal memutus sesi aktif untuk {$username}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cek status koneksi real-time dari database FreeRADIUS (radacct / radreply)
     * dengan fallback ke API MikroTik jika diperlukan.
     *
     * @param string $username
     * @return array
     */
    public function checkStatus(string $username): array
    {
        try {
            // 1. Cek sesi aktif di radacct (accounting session FreeRADIUS)
            $activeAcct = RadAcct::where('username', $username)
                ->whereNull('acctstoptime')
                ->latest('acctstarttime')
                ->first();

            if ($activeAcct) {
                $isIsolated = RadReply::where('username', $username)
                    ->where('attribute', 'Mikrotik-Address-List')
                    ->where('value', 'ISOLIR')
                    ->exists();

                return [
                    'status'     => 'online',
                    'state'      => $isIsolated ? 'isolated' : 'active',
                    'uptime'     => $activeAcct->acctsessiontime ? gmdate('H:i:s', $activeAcct->acctsessiontime) : '-',
                    'ip_address' => $activeAcct->framedipaddress ?? '-',
                    'caller_id'  => $activeAcct->callingstationid ?? '-',
                ];
            }

            // 2. Jika tidak ada sesi online di radacct, cek apakah terdaftar di radcheck
            $existsInRadius = RadCheck::where('username', $username)->exists();

            if ($existsInRadius) {
                $isIsolated = RadReply::where('username', $username)
                    ->where('attribute', 'Mikrotik-Address-List')
                    ->where('value', 'ISOLIR')
                    ->exists();

                return [
                    'status'  => 'offline',
                    'state'   => $isIsolated ? 'isolated' : 'enabled',
                    'message' => 'Akun terdaftar di FreeRADIUS (Offline)',
                ];
            }

            // 3. Fallback: Cek active session langsung di MikroTik jika NAS belum mencatat radacct
            $client = $this->getClient();
            $query = (new Query('/ppp/active/print'))->where('name', $username);
            $sessions = $client->query($query)->read();

            if (!empty($sessions)) {
                $session = $sessions[0];
                return [
                    'status'     => 'online',
                    'uptime'     => $session['uptime'] ?? '-',
                    'ip_address' => $session['address'] ?? '-',
                    'caller_id'  => $session['caller-id'] ?? '-',
                ];
            }

            return [
                'status'  => 'not_found',
                'message' => 'Akun PPPoE tidak ditemukan di FreeRADIUS maupun MikroTik',
            ];
        } catch (Throwable $e) {
            Log::error("FreeRADIUS checkStatus error: " . $e->getMessage());
            return [
                'status' => 'error',
                'error'  => $e->getMessage(),
            ];
        }
    }
}
