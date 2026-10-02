<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Ticket;
use App\Models\NetworkAsset;
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
    private function resolveRouterConfig(Subscription $subscription, ?Ticket $ticket = null): array
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
     * Mendaftarkan akun PPPoE baru ke MikroTik (/ppp/secret/add)
     * Mengikat username, password, profile paket, dan MAC Address pelanggan (caller-id)
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
        $package = $subscription->package ?? ($subscription->package_id ? \App\Models\Package::find($subscription->package_id) : null);
        $profile = $package?->name ?? 'default';
        $ip = $subscription->ip_address;
        $routerConfig = $this->resolveRouterConfig($subscription, $ticket);

        Log::info("MikroTik: Memulai pendaftaran PPPoE Secret untuk Subscription #{$subscription->id} ({$username})");

        if (empty($username) || empty($password)) {
            Log::warning("MikroTik [addCustomer]: Gagal mendaftarkan secret, username atau password kosong.", [
                'subscription_id' => $subscription->id,
            ]);
            return false;
        }

        try {
            $client = $this->getClient($routerConfig);

            // 0. Auto-provisioning Profil PPP jika belum terdaftar di MikroTik
            if (!empty($profile)) {
                $profilePrintQuery = (new Query('/ppp/profile/print'))
                    ->where('name', $profile);
                $existingProfile = $client->query($profilePrintQuery)->read();

                if (empty($existingProfile)) {
                    $addProfileQuery = (new Query('/ppp/profile/add'))
                        ->equal('name', $profile);

                    $speed = $package?->speed_mbps;
                    if (!empty($speed)) {
                        $addProfileQuery->equal('rate-limit', "{$speed}M/{$speed}M");
                    }

                    $client->query($addProfileQuery)->read();
                    Log::info("MikroTik [addCustomer]: Profil PPP '{$profile}' berhasil dibuat otomatis" . (!empty($speed) ? " (rate-limit: {$speed}M/{$speed}M)" : "") . ".");
                }
            }

            // 1. Cek apakah secret sudah terdaftar sebelumnya di MikroTik
            $printQuery = (new Query('/ppp/secret/print'))
                ->where('name', $username);
            $existing = $client->query($printQuery)->read();

            $customerName = $subscription->customer?->user?->name 
                ?? $subscription->customer?->customer_code 
                ?? 'Customer #' . $subscription->customer_id;
            $comment = "NetManager - {$customerName} (Ticket #{$ticket->id})";
            $isDisabled = ($subscription->status === 'isolated' || ($subscription->customer && $subscription->customer->is_isolated)) ? 'yes' : 'no';

            if (!empty($existing) && isset($existing[0]['.id'])) {
                // Secret sudah ada: update password, caller-id, profile, dan sesuaikan status disabled
                $setQuery = (new Query('/ppp/secret/set'))
                    ->equal('.id', $existing[0]['.id'])
                    ->equal('password', $password)
                    ->equal('service', 'pppoe')
                    ->equal('disabled', $isDisabled)
                    ->equal('comment', $comment);

                if (!empty($macAddress)) {
                    // Ikat MAC Address perangkat pelanggan ke caller-id
                    $setQuery->equal('caller-id', $macAddress);
                }
                if (!empty($profile)) {
                    $setQuery->equal('profile', $profile);
                }
                if (!empty($ip)) {
                    $setQuery->equal('remote-address', $ip);
                }

                $client->query($setQuery)->read();
                Log::info("MikroTik: PPPoE Secret {$username} sudah ada, berhasil diperbarui (disabled={$isDisabled}).");
            } else {
                // Secret belum ada: eksekusi /ppp/secret/add dengan status disabled dinamis
                $addQuery = (new Query('/ppp/secret/add'))
                    ->equal('name', $username)
                    ->equal('password', $password)
                    ->equal('service', 'pppoe')
                    ->equal('disabled', $isDisabled)
                    ->equal('comment', $comment);

                if (!empty($macAddress)) {
                    // Ikat MAC Address perangkat pelanggan ke caller-id
                    $addQuery->equal('caller-id', $macAddress);
                }
                if (!empty($profile)) {
                    $addQuery->equal('profile', $profile);
                }
                if (!empty($ip)) {
                    $addQuery->equal('remote-address', $ip);
                }

                $client->query($addQuery)->read();
                Log::info("MikroTik: PPPoE Secret {$username} berhasil ditambahkan ke router (disabled={$isDisabled}, caller-id: " . ($macAddress ?? 'none') . ").");
            }

            return true;

        } catch (Throwable $e) {
            // Fault tolerance: catat ke log, router offline / timeout tidak menggagalkan proses sistem
            Log::error("MikroTik Exception [addCustomer]: Gagal mendaftarkan PPPoE secret ke router: " . $e->getMessage(), [
                'subscription_id' => $subscription->id,
                'ticket_id'       => $ticket->id,
                'username'        => $username,
                'device_mac'      => $macAddress,
            ]);
            return false;
        }
    }

    /**
     * Mengaktifkan kembali akses PPPoE pelanggan di MikroTik & menghapus dari address-list ISOLIR
     *
     * @param Subscription $subscription
     * @return bool
     */
    public function enableCustomer(Subscription $subscription): bool
    {
        $username = $subscription->pppoe_username;
        $ip = $subscription->ip_address;
        $routerConfig = $this->resolveRouterConfig($subscription);

        Log::info("MikroTik: Memulai aktivasi layanan untuk Subscription #{$subscription->id} ({$username})");

        // 1. Update status di database lokal terlebih dahulu
        $subscription->update(['status' => 'active']);
        if ($subscription->customer) {
            $subscription->customer->update(['is_isolated' => false]);
        }

        // 2. Eksekusi perintah ke MikroTik dengan error handling ketat
        try {
            $client = $this->getClient($routerConfig);

            // A. Enable PPPoE Secret
            if (!empty($username)) {
                $printSecret = (new Query('/ppp/secret/print'))
                    ->where('name', $username);
                $secrets = $client->query($printSecret)->read();

                if (!empty($secrets)) {
                    foreach ($secrets as $secret) {
                        if (isset($secret['.id'])) {
                            $enableQuery = (new Query('/ppp/secret/set'))
                                ->equal('.id', $secret['.id'])
                                ->equal('disabled', 'no');
                            $client->query($enableQuery)->read();
                            Log::info("MikroTik: PPPoE Secret {$username} diaktifkan (disabled=no).");
                        }
                    }
                } else {
                    Log::warning("MikroTik: PPPoE Secret {$username} tidak ditemukan pada router.");
                }
            }

            // B. Hapus IP dari Address List ISOLIR jika ada (Kompatibel ROS v6 & v7)
            if (!empty($ip)) {
                $cleanIp = trim(explode('/', $ip)[0]);
                $printList = (new Query('/ip/firewall/address-list/print'))
                    ->where('list', 'ISOLIR');
                $addressList = $client->query($printList)->read();

                if (!empty($addressList)) {
                    foreach ($addressList as $entry) {
                        $entryAddress = isset($entry['address']) ? trim(explode('/', $entry['address'])[0]) : '';
                        if ($entryAddress === $cleanIp && isset($entry['.id'])) {
                            $removeQuery = (new Query('/ip/firewall/address-list/remove'))
                                ->equal('.id', $entry['.id']);
                            $client->query($removeQuery)->read();
                            Log::info("MikroTik: IP {$cleanIp} dihapus dari Address List ISOLIR.");
                        }
                    }
                }
            }

            Log::info("MikroTik: Aktivasi sukses untuk Subscription #{$subscription->id}.");
            return true;

        } catch (Throwable $e) {
            // Router offline / timeout / kredensial salah tidak boleh menyebabkan crash
            Log::error("MikroTik Exception [enableCustomer]: Gagal menghubungi router: " . $e->getMessage(), [
                'subscription_id' => $subscription->id,
                'username'        => $username,
                'ip'              => $ip,
            ]);
            return false;
        }
    }

    /**
     * Menonaktifkan akses PPPoE pelanggan di MikroTik (Isolir & Kick koneksi aktif)
     *
     * @param Subscription $subscription
     * @return bool
     */
    public function disableCustomer(Subscription $subscription): bool
    {
        $username = $subscription->pppoe_username;
        $ip = $subscription->ip_address;
        $routerConfig = $this->resolveRouterConfig($subscription);

        Log::info("MikroTik: Memulai isolir/pemutusan untuk Subscription #{$subscription->id} ({$username})");

        // 1. Update status di database lokal terlebih dahulu
        $subscription->update(['status' => 'isolated']);
        if ($subscription->customer) {
            $subscription->customer->update(['is_isolated' => true]);
        }

        // 2. Eksekusi perintah ke MikroTik dengan error handling ketat
        try {
            $client = $this->getClient($routerConfig);

            // A. Disable PPPoE Secret
            if (!empty($username)) {
                $printSecret = (new Query('/ppp/secret/print'))
                    ->where('name', $username);
                $secrets = $client->query($printSecret)->read();

                if (!empty($secrets)) {
                    foreach ($secrets as $secret) {
                        if (isset($secret['.id'])) {
                            $disableQuery = (new Query('/ppp/secret/set'))
                                ->equal('.id', $secret['.id'])
                                ->equal('disabled', 'yes');
                            $client->query($disableQuery)->read();
                            Log::info("MikroTik: PPPoE Secret {$username} dinonaktifkan (disabled=yes).");
                        }
                    }
                }

                // Putus koneksi PPPoE aktif (kick) agar perubahan langsung terasa
                $printActive = (new Query('/ppp/active/print'))
                    ->where('name', $username);
                $activeSessions = $client->query($printActive)->read();

                if (!empty($activeSessions)) {
                    foreach ($activeSessions as $session) {
                        if (isset($session['.id'])) {
                            $kickQuery = (new Query('/ppp/active/remove'))
                                ->equal('.id', $session['.id']);
                            $client->query($kickQuery)->read();
                            Log::info("MikroTik: Sesi aktif PPPoE {$username} diputus (kicked).");
                        }
                    }
                }
            }

            // B. Masukkan IP ke Address List ISOLIR jika ada (Kompatibel ROS v6 & v7)
            if (!empty($ip)) {
                $cleanIp = trim(explode('/', $ip)[0]);
                $checkList = (new Query('/ip/firewall/address-list/print'))
                    ->where('list', 'ISOLIR');
                $existing = $client->query($checkList)->read();

                $alreadyInList = false;
                if (!empty($existing)) {
                    foreach ($existing as $entry) {
                        $entryAddress = isset($entry['address']) ? trim(explode('/', $entry['address'])[0]) : '';
                        if ($entryAddress === $cleanIp) {
                            $alreadyInList = true;
                            break;
                        }
                    }
                }

                if (!$alreadyInList) {
                    $addList = (new Query('/ip/firewall/address-list/add'))
                        ->equal('list', 'ISOLIR')
                        ->equal('address', $cleanIp)
                        ->equal('comment', 'Isolir Tagihan: ' . ($username ?? 'Customer #' . $subscription->customer_id));
                    $client->query($addList)->read();
                    Log::info("MikroTik: IP {$cleanIp} ditambahkan ke Address List ISOLIR.");
                }
            }

            Log::info("MikroTik: Isolir sukses untuk Subscription #{$subscription->id}.");
            return true;

        } catch (Throwable $e) {
            Log::error("MikroTik Exception [disableCustomer]: Gagal menghubungi router: " . $e->getMessage(), [
                'subscription_id' => $subscription->id,
                'username'        => $username,
                'ip'              => $ip,
            ]);
            return false;
        }
    }

    /**
     * Cek status koneksi real-time (Uptime, IP saat ini) dari MikroTik
     */
    public function checkStatus(string $username): array
    {
        try {
            $client = $this->getClient();
            $query = (new Query('/ppp/active/print'))
                ->where('name', $username);
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

            // Jika tidak ada di active session, cek secret
            $querySecret = (new Query('/ppp/secret/print'))
                ->where('name', $username);
            $secrets = $client->query($querySecret)->read();

            if (!empty($secrets)) {
                $isDisabled = ($secrets[0]['disabled'] ?? 'false') === 'true' || ($secrets[0]['disabled'] ?? false) === true;
                return [
                    'status'  => 'offline',
                    'state'   => $isDisabled ? 'disabled' : 'enabled',
                    'profile' => $secrets[0]['profile'] ?? 'default',
                ];
            }

            return [
                'status'  => 'not_found',
                'message' => 'PPPoE Secret tidak terdaftar di MikroTik',
            ];

        } catch (Throwable $e) {
            Log::error("MikroTik checkStatus error: " . $e->getMessage());
            return [
                'status'  => 'router_offline',
                'error'   => $e->getMessage(),
            ];
        }
    }
}
