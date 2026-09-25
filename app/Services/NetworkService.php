<?php

namespace App\Services;

use App\Models\Subscription;
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
     * Mendapatkan opsi router berdasarkan langganan/customer jika ada data router fisik
     */
    private function resolveRouterConfig(Subscription $subscription): array
    {
        $config = [];

        // Cek jika ada relasi router via ticket instalasi customer
        $customer = $subscription->customer;
        if ($customer) {
            $installationTicket = $customer->tickets()
                ->whereNotNull('router_id')
                ->latest()
                ->with('router')
                ->first();

            if ($installationTicket && $installationTicket->router && !empty($installationTicket->router->ip_address)) {
                $config['host'] = $installationTicket->router->ip_address;
            }
        }

        return $config;
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

            // B. Hapus IP dari Address List ISOLIR jika ada
            if (!empty($ip)) {
                $printList = (new Query('/ip/firewall/address-list/print'))
                    ->where('list', 'ISOLIR')
                    ->where('address', $ip);
                $addressList = $client->query($printList)->read();

                if (!empty($addressList)) {
                    foreach ($addressList as $entry) {
                        if (isset($entry['.id'])) {
                            $removeQuery = (new Query('/ip/firewall/address-list/remove'))
                                ->equal('.id', $entry['.id']);
                            $client->query($removeQuery)->read();
                            Log::info("MikroTik: IP {$ip} dihapus dari Address List ISOLIR.");
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

            // B. Masukkan IP ke Address List ISOLIR jika ada
            if (!empty($ip)) {
                $checkList = (new Query('/ip/firewall/address-list/print'))
                    ->where('list', 'ISOLIR')
                    ->where('address', $ip);
                $existing = $client->query($checkList)->read();

                if (empty($existing)) {
                    $addList = (new Query('/ip/firewall/address-list/add'))
                        ->equal('list', 'ISOLIR')
                        ->equal('address', $ip)
                        ->equal('comment', 'Isolir Tagihan: ' . ($username ?? 'Customer #' . $subscription->customer_id));
                    $client->query($addList)->read();
                    Log::info("MikroTik: IP {$ip} ditambahkan ke Address List ISOLIR.");
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
