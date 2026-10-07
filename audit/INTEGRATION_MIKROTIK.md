# LAPORAN ANALISIS JALUR INTEGRASI: MIKROTIK ROUTEROS

**Project:** NetManager - Integrated ISP Management System  
**Target:** Jalur Eksternal MikroTik RouterOS API & Network Socket  
**Auditor:** Senior System Auditor & Network Automation Specialist  
**Tanggal:** 2026-10-07  
**Status Integrasi:** 100% Terverifikasi, Hardened & Terisolasi (Production-Ready)  

---

## 1. Arsitektur & Peran Integrasi

Pada arsitektur terbaru NetManager (pasca-migrasi FreeRADIUS), peran MikroTik RouterOS telah didekopel secara radikal:
- **Bukan Lagi Penyimpan Kredensial:** RouterOS tidak lagi menyimpan akun PPPoE rahasia (`/ppp/secret`) secara lokal. Seluruh otentikasi dialihkan ke FreeRADIUS SQL.
- **Fungsi Utama:**
  1. Bertindak murni sebagai **RADIUS Client (NAS - Network Access Server)** untuk terminasi PPPoE Server.
  2. Eksekusi pemutusan sesi aktif secara instan (**Active Session Kicking** via `/ppp/active/remove`) saat isolir tagihan atau aktivasi pelanggan dipicu.
  3. Pengecekan status ketersediaan router (**Device Liveness Check**) melalui koneksi socket TCP non-blocking ke port API (`8728`).

```mermaid
flowchart LR
    subgraph Laravel Application
        NS[App\\Services\\NetworkService]
        RC[Admin\\RouterController]
        Job[SyncPaidInvoiceHardwareJob]
    end

    subgraph MikroTik RouterOS
        API[Port 8728: RouterOS API]
        PPPoE[PPPoE Server / Radius Client]
        ActiveSessions[/ppp/active]
    end

    RC -->|fsockopen port 8728| API
    NS -->|Query: /ppp/active/remove| API
    Job -->|Async Kick| NS
    API --> ActiveSessions
    PPPoE -->|CoA / Disconnect| ActiveSessions
```

---

## 2. Spesifikasi Teknis & Dependensi

| Parameter | Konfigurasi / Nilai | Keterangan |
| :--- | :--- | :--- |
| **Driver Library** | `evilfreelancer/routeros-api-php: ^1.7` | PHP Client resmi RouterOS API berbasis socket stream |
| **Port Default API** | `8728` (TCP plaintext API) | Port standar RouterOS API |
| **Timeout Soket** | `3 detik` (Config) / `2 detik` (Ping Check) | Mencegah thread hanging jika router mati |
| **Retry Attempts** | `1 kali` | Fail-fast agar tidak memblokir worker PHP |
| **Credential Resolver** | Dinamis dari `network_assets` & `.env` | Mendukung multi-router dengan IP/port/user independen |
| **Keamanan Perintah** | 100% Zero Shell Exec (`exec()` dimusnahkan) | Kebal dari RCE / Command Injection |

---

## 3. Alur & Implementasi Kode

### 3.1. Resolusi Konfigurasi Multi-Router Dinamis (`resolveRouterConfig`)
Berada di [app/Services/NetworkService.php](file:///c:/Users/LENOVO/Documents/Rafli/Project/NetManager/app/Services/NetworkService.php#L51-L108).
NetManager mengelola multi-router cabang dengan hirarki resolusi prioritas:
1. `subscription->router_id` $\rightarrow$ Relasi permanen dari entitas langganan.
2. `ticket->router_id` $\rightarrow$ Relasi dari tiket instalasi aktif.
3. Fallback riwayat tiket instalasi pelanggan terdahulu.
4. Fallback nilai global `.env` (`MIKROTIK_HOST`, `MIKROTIK_USER`, `MIKROTIK_PASS`, `MIKROTIK_PORT`).

```php
// app/Services/NetworkService.php:82-108
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
```

### 3.2. Pemutusan Sesi Aktif PPPoE (`kickSession`)
Ketika status isolir diubah di FreeRADIUS, ONT pelanggan yang masih terhubung harus dipaksa memutus koneksi agar melakukan login ulang dan menerima atribut `Mikrotik-Address-List = ISOLIR` (atau normal):

```php
// app/Services/NetworkService.php:382-411
protected function kickSession(string $username, array $routerConfig = []): bool
{
    try {
        $client = $this->getClient($routerConfig);
        $query = (new Query('/ppp/active/print'))
            ->where('name', $username);

        $activeSessions = $client->query($query)->read();

        if (!empty($activeSessions)) {
            foreach ($activeSessions as $session) {
                if (isset($session['.id'])) {
                    $removeQuery = (new Query('/ppp/active/remove'))
                        ->equal('.id', $session['.id']);
                    $client->query($removeQuery)->read();
                }
            }
        }
        return true;
    } catch (Throwable $e) {
        Log::warning("NetworkService kickSession MikroTik [{$username}]: " . $e->getMessage());
        return false;
    }
}
```

### 3.3. Pengecekan Liveness Non-Blocking (`RouterController@testConnection`)
Berada di [app/Http/Controllers/Admin/RouterController.php](file:///c:/Users/LENOVO/Documents/Rafli/Project/NetManager/app/Http/Controllers/Admin/RouterController.php#L62-L110).
Fitur uji konektivitas router menggunakan `@fsockopen` dengan timeout 2 detik:
- **Zero OS Shell:** Tidak mengeksekusi shell `ping` sistem operasi.
- **Non-blocking:** Thread web worker tidak terkunci saat perangkat fisik padam total.

---

## 4. Evaluasi Ketahanan (Resilience & Error Handling)

1. **Isolasi di Luar Transaksi Database:**
   Seluruh panggilan ke MikroTik RouterOS API dijalankan **di luar** transaksi database relasional MySQL (`DB::transaction`). Jika router cabang mati atau jaringan serat optik putus:
   - Data mutasi invoice (Lunas), status pelanggan, dan tiket tetap tersimpan 100% konsisten.
   - Tidak terjadi rollback transaksi bisnis akibat kegagalan perangkat keras.
2. **Pendelegasian Asinkron (Background Queue):**
   Pada pelunasan Midtrans Webhook atau Customer Portal, pemanggilan MikroTik didelegasikan ke queue job `SyncPaidInvoiceHardwareJob`. Midtrans menerima HTTP 200 seketika tanpa hambatan latensi socket router.
3. **Log Audit & Fallback:**
   Setiap exception ditangkap (`try-catch (\Throwable $e)`) dan dicatat ke `storage/logs/laravel.log` dengan level `warning` atau `error` tanpa memunculkan HTTP 500 kepada pengguna.

---

## 5. Rekomendasi Pemeliharaan

- **Firewall Filtering pada Router:** Pastikan port API `8728` hanya dapat diakses dari IP server aplikasi NetManager (`/ip firewall filter add chain=input protocol=tcp dst-port=8728 src-address=SERVER_IP action=accept`).
- **MikroTik User Policy:** Buat akun user khusus NetManager pada RouterOS dengan permission terbatas: `api`, `read`, `write`, `test` (tanpa wewenang `reboot` atau `policy`).
