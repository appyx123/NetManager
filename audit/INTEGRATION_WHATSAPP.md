# LAPORAN ANALISIS JALUR INTEGRASI: WHATSAPP GATEWAY

**Project:** NetManager - Integrated ISP Management System  
**Target:** Jalur Eksternal WhatsApp Messaging Microservice  
**Auditor:** Senior System Auditor & Full-Stack Communications Specialist  
**Tanggal:** 2026-10-07  
**Status Integrasi:** 100% Terverifikasi, Hardened & Teruji (Production-Ready)  

---

## 1. Arsitektur & Peran Integrasi

NetManager menggunakan pola **Sidecar Microservice** terpisah untuk menangani seluruh notifikasi WhatsApp operasional ISP. Arsitektur ini memisahkan proses PHP monolitik dari runtime browser headless untuk menjamin efisiensi memori dan kestabilan aplikasi:

- **Komponen Inti:**
  1. **Laravel Backend (`App\Services\WhatsappService`):** Pembentuk template pesan, normalisasi nomor telepon, dan pengirim HTTP request non-blocking.
  2. **Node.js Microservice (`whatsapp-service/server.js`):** Web server Express 4.19 yang menjalankan `whatsapp-web.js` dan Puppeteer Chromium headless pada port `3000`.
  3. **Multi-Device Engine:** Menjaga sesi WhatsApp Web aktif secara permanen menggunakan penyimpanan lokal `./.wwebjs_auth`.

```mermaid
flowchart LR
    subgraph Laravel Core
        Mkt[Lead Conversion]
        Bill[Billing & Daily Cron]
        Mid[Midtrans Webhook]
        Tech[Ticket Finalization]
        WASvc[App\\Services\\WhatsappService]
    end

    subgraph Node.js Sidecar (Port 3000)
        Express[Express Server]
        WWebJS[whatsapp-web.js Client]
        Chromium[Puppeteer Chromium Headless]
        AuthData[(Session: .wwebjs_auth)]
    end

    subgraph WhatsApp Cloud
        WANet[WhatsApp Multi-Device Network]
        ClientPhone[Ponsel Pelanggan]
    end

    Mkt & Bill & Mid & Tech --> WASvc
    WASvc -->|HTTP POST /send-message (Timeout 5s)| Express
    Express --> WWebJS
    WWebJS --> Chromium
    Chromium <--> AuthData
    WWebJS -->|WebSocket Encrypted TLS| WANet
    WANet --> ClientPhone
```

---

## 2. Spesifikasi Teknis & Endpoint API

| Parameter | Spesifikasi | Keterangan |
| :--- | :--- | :--- |
| **Runtime Service** | Node.js 20 LTS + Express 4.19 | Port `3000` (konfigurasi `WA_API_URL`) |
| **Driver Library** | `whatsapp-web.js: ^1.26.0` | Library emulasi client WhatsApp Multi-Device |
| **Browser Engine** | `puppeteer: ^22.0.0` | Chromium headless untuk menjaga sesi browser |
| **HTTP Timeout Laravel** | `5 detik` (`Http::timeout(5)`) | Non-blocking fail-fast jika bot gateway terputus |
| **Normalisasi Nomor** | `formatPhoneNumber()` | Regex pembersih karakter; konversi `08xx` $\rightarrow$ `628xx` |
| **Format Target JID** | `${number}@c.us` | Standar JID obrolan personal WhatsApp |

### Daftar Endpoint Microservice:
- `POST /send-message` : Menerima payload JSON `{ "number": "628xxx", "message": "..." }`.
- `GET /status` : Memeriksa kesiapan client WhatsApp (`READY`, `AUTHENTICATED`, `INITIALIZING`, `DISCONNECTED`).
- `GET /qr` : Menampilkan kode QR otentikasi saat pertama kali pairing.

---

## 3. Implementasi Kode & Sanitasi Data

### 3.1. Normalisasi Format Nomor Telepon Internasional
Diimplementasikan pada [app/Services/WhatsappService.php](file:///c:/Users/LENOVO/Documents/Rafli/Project/NetManager/app/Services/WhatsappService.php#L23-L34):

```php
private static function formatPhoneNumber(string $number): string
{
    // 1. Buang semua karakter selain angka (spasi, strip, tanda tambah)
    $number = preg_replace('/[^0-9]/', '', $number);

    // 2. Jika diawali angka 0, ganti dengan 62
    if (str_starts_with($number, '0')) {
        $number = '62' . substr($number, 1);
    }

    return $number;
}
```

### 3.2. Panggilan HTTP Non-Blocking & Fault Tolerance
Panggilan HTTP POST dilindungi oleh timeout 5 detik dan penanganan exception yang aman:

```php
// app/Services/WhatsappService.php:43-74
public static function send(string $phone, string $message): bool
{
    try {
        $formattedPhone = self::formatPhoneNumber($phone);
        if (empty($formattedPhone)) {
            Log::warning('WhatsApp Gateway: Nomor telepon kosong atau tidak valid.');
            return false;
        }

        $endpoint = self::getEndpoint();
        $response = Http::timeout(5)->post($endpoint, [
            'number'  => $formattedPhone,
            'message' => $message,
        ]);

        if ($response->successful()) {
            Log::info("WhatsApp Gateway: Pesan berhasil dikirim ke {$formattedPhone}");
            return true;
        }

        Log::error("WhatsApp Gateway: Gagal kirim ke {$formattedPhone}. Status: {$response->status()}");
        return false;
    } catch (Exception $e) {
        // Fault-tolerant: log error tanpa melempar exception agar flow utama tidak terputus
        Log::error('WhatsApp Error: ' . $e->getMessage());
        return false;
    }
}
```

---

## 4. Skenario Otomasi Pesan Bisnis ISP

NetManager mengotomatiskan seluruh komunikasi pelanggan melalui skenario baku:

1. **Pengiriman Akun & Kredensial Baru (`sendAccountCreated`):**
   - **Pemicu:** Pemasaran mengonversi prospek (`LeadController@convertToCustomer`).
   - **Konten:** Nama pelanggan, username portal, password awal default, kode pelanggan (`customer_code`), dan link URL portal klien.
2. **Kuitansi Resmi Pelunasan Tagihan (`sendPaymentSuccess`):**
   - **Pemicu:** Midtrans Webhook, sinkronisasi portal, atau pelunasan manual admin (`BillingController@markAsPaid`).
   - **Konten:** Ucapan terima kasih, nomor invoice resmi, nominal lunas, dan konfirmasi aktifasi kembali internet.
3. **Pengingat Tagihan Bertingkat (Cron Scheduler Harian `billing:process-daily`):**
   - **H-3:** Pengingat ramah tanggal jatuh tempo.
   - **H-1:** Peringatan mendesak sehari sebelum jatuh tempo.
   - **H-0 (Hari-H):** Notifikasi batas akhir pembayaran hari ini.
4. **Pemberitahuan Isolir Jatuh Tempo (Overdue Isolation):**
   - **Pemicu:** Otomasi harian mendeteksi invoice `unpaid` dengan `due_date < hari_ini`.
   - **Konten:** Pemberitahuan resmi pemutusan sementara layanan internet dan instruksi pembayaran mandiri via portal klien.
5. **Pembaruan Tiket Gangguan (`sendComplaintUpdate`):**
   - **Pemicu:** Teknisi mengambil atau menyelesaikan tiket perbaikan pelanggan.
   - **Konten:** Status penanganan gangguan dan estimasi/catatan penyelesaian teknisi.

---

## 5. Evaluasi Ketahanan (Resilience & Error Handling)

1. **Zero Flow Interruption:** Jika microservice Node.js mati, down, atau terputus koneksi internet, aplikasi Laravel tidak akan mengalami HTTP 500 error. Transaksi pembayaran Midtrans, konversi lead, dan penyelesaian tugas teknisi tetap berhasil 100%.
2. **Sanitasi Akun `@lid` & Nomor Tidak Terdaftar:**
   Node.js service mengecek status pendaftaran nomor via `client.getNumberId(chatId)` sebelum memanggil `client.sendMessage()`. Jika nomor tidak memiliki akun WhatsApp, bot mencatat log peringatan tanpa membuat proses server crash.
3. **Daemon Persistence:**
   Pada lingkungan produksi, service Node.js direkomendasikan berjalan di bawah pengawasan **PM2** (`pm2 start server.js --name "netmanager-wa"`) dengan restart otomatis jika konsumsi RAM melampaui batas (`--max-memory-restart 500M`).
