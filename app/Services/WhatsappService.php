<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Mendapatkan URL endpoint pengiriman pesan WhatsApp Gateway
     */
    public static function getEndpoint(): string
    {
        $baseUrl = rtrim(config('services.whatsapp.api_url', env('WA_API_URL', 'http://127.0.0.1:3000')), '/');
        return str_ends_with($baseUrl, '/send-message') ? $baseUrl : $baseUrl . '/send-message';
    }

    /**
     * Sanitasi dan format nomor telepon ke standar internasional (62xxxx)
     */
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

    /**
     * Fungsi Statis Utama: Kirim Pesan Teks via Node.js Bot Gateway
     *
     * @param string $phone Nomor HP tujuan
     * @param string $message Konten pesan teks
     * @return bool
     */
    public static function send(string $phone, string $message): bool
    {
        try {
            $formattedPhone = self::formatPhoneNumber($phone);

            if (empty($formattedPhone)) {
                Log::warning('WhatsApp Gateway: Nomor telepon kosong atau tidak valid.');
                return false;
            }

            $endpoint = self::getEndpoint();

            // Panggilan HTTP POST dengan timeout 5 detik agar fault-tolerant & non-blocking
            $response = Http::timeout(5)->post($endpoint, [
                'number'  => $formattedPhone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp Gateway: Pesan berhasil dikirim ke {$formattedPhone}");
                return true;
            }

            Log::error("WhatsApp Gateway: Gagal kirim ke {$formattedPhone}. Status: {$response->status()}, Response: " . $response->body());
            return false;

        } catch (Exception $e) {
            // Fault-tolerant: log error tanpa melempar exception agar flow utama tidak terputus
            Log::error('WhatsApp Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Method instance untuk backward-compatibility
     */
    public function sendMessage(string $phone, string $message): bool
    {
        $formattedPhone = self::formatPhoneNumber($phone);
        return self::send($formattedPhone, $message);
    }

    /**
     * FUNGSI OTOMATIS: Kirim Notifikasi Pembayaran Berhasil (Payment Success)
     *
     * @param string $customerName
     * @param string $phone
     * @param string $invoiceNumber
     * @param int|float $amount
     * @return bool
     */
    public static function sendPaymentSuccess(string $customerName, string $phone, string $invoiceNumber, $amount): bool
    {
        $formattedPhone = self::formatPhoneNumber($phone);
        $amountFormatted = number_format((float) $amount, 0, ',', '.');

        $message = "Halo {$customerName}, Terima kasih! Pembayaran tagihan {$invoiceNumber} sebesar Rp{$amountFormatted} telah berhasil kami terima. Akses internet Anda telah diaktifkan kembali. - PT. Mandiri Global Data";

        return self::send($formattedPhone, $message);
    }

    /**
     * FUNGSI OTOMATIS: Kirim Pengingat Tagihan (Invoice Reminder)
     */
    public function sendInvoiceNotification(string $customerName, string $phone, string $invoiceNumber, $amount, string $dueDate): bool
    {
        $formattedPhone = self::formatPhoneNumber($phone);
        $amountFormatted = number_format((float) $amount, 0, ',', '.');

        $message = "Halo *{$customerName}*,\n\n";
        $message .= "Ini adalah pengingat tagihan internet Anda dari *NetManager*.\n\n";
        $message .= "🧾 No. Tagihan: {$invoiceNumber}\n";
        $message .= "💰 Jumlah: Rp {$amountFormatted}\n";
        $message .= "🗓 Jatuh Tempo: {$dueDate}\n\n";
        $message .= 'Mohon segera melakukan pembayaran untuk menghindari isolir otomatis. Terima kasih! 🙏';

        return self::send($formattedPhone, $message);
    }

    /**
     * FUNGSI OTOMATIS: Kirim Notifikasi Update Tiket Gangguan
     */
    public function sendTicketUpdate(string $customerName, string $phone, string $ticketSubject, string $status): bool
    {
        $formattedPhone = self::formatPhoneNumber($phone);

        $message = "Halo *{$customerName}*,\n\n";
        $message .= "Status tiket laporan Anda (*{$ticketSubject}*) telah diperbarui menjadi: *{$status}*.\n\n";
        $message .= 'Teknisi kami sedang memproses permintaan Anda. Terima kasih atas kesabarannya.';

        return self::send($formattedPhone, $message);
    }

    /**
     * FUNGSI OTOMATIS: Kirim Pengingat Tagihan H-3, H-1, atau Hari-H (H-0) Jatuh Tempo
     *
     * @param \App\Models\Invoice $invoice
     * @param int $daysLeft
     * @return bool
     */
    public static function sendBillingReminder($invoice, int $daysLeft): bool
    {
        $customer = $invoice->subscription?->customer;
        $rawPhone = $customer?->phone_number ?? $customer?->user?->phone_number ?? $customer?->lead?->phone;

        if (empty($rawPhone)) {
            Log::warning("WhatsApp Gateway: Nomor telepon pelanggan tidak ditemukan untuk Invoice #{$invoice->id}");
            return false;
        }

        $formattedPhone = self::formatPhoneNumber($rawPhone);
        $customerName = $customer?->user?->name ?? $customer?->lead?->name ?? 'Pelanggan';
        $amountFormatted = number_format((float) $invoice->amount, 0, ',', '.');
        $dueDate = $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d F Y') : '-';

        $dueNotice = ($daysLeft === 0)
            ? "jatuh tempo *HARI INI*"
            : "akan jatuh tempo dalam *{$daysLeft} hari* lagi";

        $message = "Halo *{$customerName}*,\n\n";
        $message .= "Pemberitahuan: Tagihan internet Anda {$dueNotice}.\n\n";
        $message .= "🧾 No. Tagihan: {$invoice->invoice_number}\n";
        $message .= "💰 Jumlah: Rp {$amountFormatted}\n";
        $message .= "🗓 Batas Jatuh Tempo: {$dueDate}\n\n";
        $message .= "Mohon segera lakukan pembayaran agar koneksi internet tetap aktif. Abaikan pesan ini jika sudah membayar. Terima kasih! 🙏 - PT. Mandiri Global Data";

        return self::send($formattedPhone, $message);
    }

    /**
     * FUNGSI OTOMATIS: Kirim Pemberitahuan Isolir Layanan (Overdue)
     *
     * @param \App\Models\Invoice $invoice
     * @return bool
     */
    public static function sendIsolationNotice($invoice): bool
    {
        $customer = $invoice->subscription?->customer;
        $rawPhone = $customer?->phone_number ?? $customer?->user?->phone_number ?? $customer?->lead?->phone;

        if (empty($rawPhone)) {
            Log::warning("WhatsApp Gateway: Nomor telepon pelanggan tidak ditemukan untuk Invoice #{$invoice->id}");
            return false;
        }

        $formattedPhone = self::formatPhoneNumber($rawPhone);
        $customerName = $customer?->user?->name ?? $customer?->lead?->name ?? 'Pelanggan';
        $amountFormatted = number_format((float) $invoice->amount, 0, ',', '.');
        $dueDate = $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d F Y') : '-';

        $message = "Halo *{$customerName}*,\n\n";
        $message .= "⚠️ *PEMBERITAHUAN ISOLIR LAYANAN*\n\n";
        $message .= "Layanan internet Anda sementara dinonaktifkan (diisolir) karena tagihan telah melewati batas jatuh tempo ({$dueDate}).\n\n";
        $message .= "🧾 No. Tagihan: {$invoice->invoice_number}\n";
        $message .= "💰 Total Tagihan: Rp {$amountFormatted}\n\n";
        $message .= "Silakan lakukan pembayaran melalui portal pelanggan untuk mengaktifkan kembali layanan internet Anda secara otomatis. Terima kasih. - PT. Mandiri Global Data";

        return self::send($formattedPhone, $message);
    }
}
