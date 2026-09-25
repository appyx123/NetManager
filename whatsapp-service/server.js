const express = require('express');
const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());

// Inisialisasi WhatsApp Client dengan sesi persisten & webVersionCache stabil
const client = new Client({
    authStrategy: new LocalAuth({
        dataPath: './.wwebjs_auth'
    }),
    puppeteer: {
        headless: true,
        executablePath: process.env.PUPPETEER_EXECUTABLE_PATH || undefined,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-accelerated-2d-canvas',
            '--no-first-run',
            '--no-zygote',
            '--disable-gpu'
        ]
    }
});

let isReady = false;

// Event ketika QR Code dihasilkan untuk dipindai (login)
client.on('qr', (qr) => {
    console.log('[WhatsApp Bot] Scan QR Code berikut dengan WhatsApp Anda:');
    qrcode.generate(qr, { small: true });
});

// Event saat autentikasi berhasil
client.on('authenticated', () => {
    console.log('[WhatsApp Bot] Autentikasi berhasil!');
});

// Event saat bot siap digunakan
client.on('ready', () => {
    isReady = true;
    console.log('[WhatsApp Bot] Client siap mengirim dan menerima pesan.');
});

// Event saat terputus
client.on('disconnected', (reason) => {
    isReady = false;
    console.log('[WhatsApp Bot] Client terputus:', reason);
    client.initialize();
});

/**
 * Format nomor telepon ke format WhatsApp (@c.us)
 */
function formatWaNumber(number) {
    let cleaned = String(number).replace(/[^0-9]/g, '');

    if (cleaned.startsWith('0')) {
        cleaned = '62' + cleaned.slice(1);
    }

    if (!cleaned.endsWith('@c.us')) {
        cleaned = cleaned + '@c.us';
    }

    return cleaned;
}

/**
 * Endpoint Cek Status Bot
 */
app.get('/status', (req, res) => {
    res.json({
        service: 'WhatsApp Gateway NetManagement',
        status: isReady ? 'ready' : 'not_ready',
        message: isReady ? 'WhatsApp client terhubung dan siap.' : 'WhatsApp client belum siap / scan QR diperlukan.'
    });
});

/**
 * Endpoint Utama: Kirim Pesan WhatsApp
 * Body JSON: { "number": "081234567890", "message": "Pesan teks" }
 */
app.post('/send-message', async (req, res) => {
    const { number, message } = req.body;

    if (!number || !message) {
        return res.status(400).json({
            status: 'error',
            message: 'Parameter "number" dan "message" wajib diisi.'
        });
    }

    if (!isReady) {
        return res.status(503).json({
            status: 'error',
            message: 'WhatsApp Client belum terhubung / belum login (scan QR).'
        });
    }

    try {
        const formattedNumber = formatWaNumber(number);
        let targetNumber = formattedNumber;

        // Validasi nomor terdaftar jika getNumberId mengembalikan @c.us, jangan gunakan @lid
        try {
            const numberDetails = await client.getNumberId(formattedNumber);
            if (numberDetails && numberDetails._serialized && !numberDetails._serialized.endsWith('@lid')) {
                targetNumber = numberDetails._serialized;
            }
        } catch (e) {
            console.warn('[WhatsApp Bot] getNumberId notice:', e.message);
        }

        console.log(`[WhatsApp Bot] Mengirim pesan ke ${targetNumber}...`);

        let result = null;
        try {
            result = await client.sendMessage(targetNumber, message);
        } catch (sendErr) {
            // Tangani bug false negative whatsapp-web.js jika pesan terkirim tapi reading 'id' error
            if (sendErr && sendErr.message && sendErr.message.includes("reading 'id'")) {
                console.warn('[WhatsApp Bot] Warning: Return message model missing ID, but dispatch proceeded.');
                return res.json({
                    status: 'success',
                    message: 'Pesan berhasil dikirim.',
                    to: targetNumber
                });
            }
            throw sendErr;
        }

        return res.json({
            status: 'success',
            message: 'Pesan berhasil dikirim.',
            message_id: (result && result.id) ? result.id._serialized : null,
            to: targetNumber
        });
    } catch (err) {
        console.error('[WhatsApp Bot Error]', err.message);
        return res.status(500).json({
            status: 'error',
            message: 'Gagal mengirim pesan: ' + err.message
        });
    }
});

// Jalankan HTTP Server
app.listen(PORT, () => {
    console.log(`[WhatsApp Gateway] Server berjalan di http://127.0.0.1:${PORT}`);
});

// Inisialisasi WhatsApp client
client.initialize();
