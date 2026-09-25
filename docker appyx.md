# Development & Deployment Guide — NetManager

Panduan resmi untuk menjalankan aplikasi **NetManager** di lingkungan Local, GitHub Codespaces, Docker, dan deployment ke Linux VPS.

---

## Local Development

### Kebutuhan Sistem
- PHP 8.2 atau lebih baru (dengan ekstensi: `pdo_mysql`, `mbstring`, `curl`, `bcmath`, `sockets`, `zip`, `gd`)
- Composer 2.x
- Node.js 20.x & NPM
- MySQL 8.0+ atau MariaDB 10.11+
- Chromium / Google Chrome (untuk WhatsApp bot gateway)

### Langkah Setup
1. **Clone repository & masuk ke folder project:**
   ```bash
   git clone https://github.com/appyx123/NetManager.git
   cd NetManager
   ```

2. **Setup file konfigurasi:**
   ```bash
   cp .env.example .env
   ```

3. **Install dependensi PHP & generate app key:**
   ```bash
   composer install
   php artisan key:generate
   ```

4. **Install dependensi Frontend & build assets:**
   ```bash
   npm install
   npm run build
   ```

5. **Install dependensi WhatsApp Gateway:**
   ```bash
   cd whatsapp-service
   npm install
   cd ..
   ```

6. **Konfigurasi Database di `.env`:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_netmanager
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Jalankan migrasi dan seeder:**
   ```bash
   php artisan migrate --seed
   ```

8. **Buat storage symlink:**
   ```bash
   php artisan storage:link
   ```

---

## GitHub Codespaces

Repository ini telah dilengkapi dengan `.devcontainer/` otomatis.

### Cara Menggunakan
1. Buka repository di GitHub: [github.com/appyx123/NetManager](https://github.com/appyx123/NetManager).
2. Klik tombol hijau **Code** $\rightarrow$ tab **Codespaces** $\rightarrow$ **Create codespace on main**.
3. Codespaces akan secara otomatis:
   - Menyiapkan container Linux dengan PHP 8.2, Composer, dan Node.js 20.
   - Menjalankan `.devcontainer/setup.sh` untuk menginstall dependencies dan build assets.
   - Membuka port `8000` (Aplikasi), `3000` (WhatsApp Gateway), dan `5173` (Vite dev).

### Menjalankan Server di Codespaces
Buka terminal di Codespaces:
```bash
# Terminal 1: Web App
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2: WhatsApp Service
cd whatsapp-service && node server.js
```
Atau jalankan stack lengkap via Docker di dalam Codespace:
```bash
docker compose up -d
```

---

## Docker Development

Menjalankan seluruh stack (Web App, Database MySQL, dan WhatsApp Gateway) menggunakan Docker Compose:

```bash
# Salin konfigurasi environment jika belum ada
cp -n .env.example .env

# Build dan jalankan seluruh container
docker compose up -d --build
```

Container yang berjalan:
| Service | Container Name | Port Host | Deskripsi |
|---|---|---|---|
| `app` | `netmanager-app` | `8000` | Nginx + PHP 8.2 FPM (Laravel) |
| `db` | `netmanager-db` | `3306` | MySQL 8.0 Server |
| `whatsapp` | `netmanager-whatsapp` | `3000` | Node.js WhatsApp Gateway (Puppeteer) |

Cek status container:
```bash
docker compose ps
```

Melihat log aplikasi:
```bash
docker compose logs -f app
```

---

## Environment Variables

Daftar environment variables penting di `.env`:

| Variabel | Default / Nilai Uji | Keterangan |
|---|---|---|
| `APP_ENV` | `local` / `production` | Mode aplikasi |
| `APP_KEY` | *(auto-generated)* | Kunci enkripsi sesi & data Laravel |
| `APP_URL` | `http://localhost:8000` | URL basis aplikasi |
| `DB_CONNECTION` | `mysql` | Driver database |
| `DB_HOST` | `127.0.0.1` (Local) / `db` (Docker) | Host server database |
| `DB_PORT` | `3306` | Port database |
| `DB_DATABASE` | `db_netmanager` | Nama database |
| `DB_USERNAME` | `root` / `netmanager` | Pengguna database |
| `DB_PASSWORD` | `secret` | Sandi database |
| `MIDTRANS_SERVER_KEY` | *(dari portal Midtrans)* | Server Key Sandbox / Production |
| `MIDTRANS_CLIENT_KEY` | *(dari portal Midtrans)* | Client Key Snap |
| `MIDTRANS_IS_PRODUCTION` | `false` | Status mode Midtrans (false = Sandbox) |
| `MIKROTIK_HOST` | `192.168.88.1` | IP Router MikroTik |
| `MIKROTIK_USER` | `admin` | Username API RouterOS |
| `MIKROTIK_PASS` | `""` | Password API RouterOS |
| `MIKROTIK_PORT` | `8728` | Port API RouterOS |
| `WA_API_URL` | `http://whatsapp:3000` | URL endpoint bot WhatsApp |

---

## Database Setup

### Perintah Database (CLI / Docker)
- **Migrasi Baru:**
  ```bash
  php artisan migrate
  # Di dalam Docker:
  docker compose exec app php artisan migrate
  ```

- **Migrasi Bersih + Data Dummy (Fresh Seed):**
  ```bash
  php artisan migrate:fresh --seed
  # Di dalam Docker:
  docker compose exec app php artisan migrate:fresh --seed
  ```

- **Masuk ke MySQL CLI di Container:**
  ```bash
  docker compose exec db mysql -u netmanager -psecret db_netmanager
  ```

---

## Running the Application

### 1. Menjalankan secara Terpisah (Non-Docker)
```bash
# Terminal 1: Laravel Web Server
php artisan serve --port=8000

# Terminal 2: Vite Asset Watcher (Development)
npm run dev

# Terminal 3: WhatsApp Gateway Bot
cd whatsapp-service && node server.js
```

### 2. Menjalankan via Docker Compose
```bash
docker compose up -d
```
Akses web di browser: `http://localhost:8000`  
Health check: `http://localhost:8000/up`  
Status WhatsApp Gateway: `http://localhost:3000/status`

---

## Production Build

Uji build production sebelum deploy:

```bash
# 1. Optimasi dependensi PHP tanpa dev packages
composer install --no-dev --optimize-autoloader

# 2. Build aset frontend Vite terkompresi
npm run build

# 3. Cache konfigurasi, route, dan view
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Tes build container Docker production
docker build -t netmanager:production .
```

---

## VPS Deployment

Target arsitektur VPS: **Linux Ubuntu 22.04 LTS x86_64 dengan Docker & Nginx Reverse Proxy**.

### Langkah di Server VPS:

1. **Install Docker & Docker Compose di VPS:**
   ```bash
   sudo apt update && sudo apt install -y git curl ufw
   curl -fsSL https://get.docker.com -o get-docker.sh && sudo sh get-docker.sh
   sudo usermod -aG docker $USER
   ```

2. **Clone Project:**
   ```bash
   git clone https://github.com/appyx123/NetManager.git /var/www/netmanager
   cd /var/www/netmanager
   ```

3. **Buat File `.env` Produksi:**
   ```bash
   cp .env.example .env
   nano .env
   ```
   *Atur `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domainanda.com`, dan password database yang kuat.*

4. **Jalankan Stack dengan Docker Compose:**
   ```bash
   docker compose up -d --build
   ```

5. **Scan QR Code WhatsApp (Hanya sekali di awal):**
   ```bash
   docker compose logs -f whatsapp
   ```
   *Pindai QR code yang muncul di terminal menggunakan WhatsApp di smartphone.*

6. **Konfigurasi Reverse Proxy Host (Nginx + SSL Certbot):**
   Pasang Nginx di host VPS untuk mengarahkan domain port 80/443 ke port 8000:
   ```nginx
   server {
       server_name domainanda.com;

       location / {
           proxy_pass http://127.0.0.1:8000;
           proxy_set_header Host $host;
           proxy_set_header X-Real-IP $remote_addr;
           proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
           proxy_set_header X-Forwarded-Proto $scheme;
       }
   }
   ```
   Pasang SSL gratis via Let's Encrypt:
   ```bash
   sudo apt install -y certbot python3-certbot-nginx
   sudo certbot --nginx -d domainanda.com
   ```

7. **Setup Cron Job Otomatis (Tagihan Jatuh Tempo & Isolir):**
   Tambahkan di crontab host VPS (`crontab -e`):
   ```cron
   * * * * * cd /var/www/netmanager && docker compose exec -T app php artisan schedule:run >> /dev/null 2>&1
   ```

---

## Troubleshooting

### 1. Error: *Failed to connect to database*
- Pastikan container `netmanager-db` berstatus `healthy`.
- Periksa kesesuaian nilai `DB_HOST=db` di dalam container atau `127.0.0.1` jika di host.

### 2. Error: *WhatsApp Client belum login / QR scan loop*
- Buka log: `docker compose logs -f whatsapp`.
- Jika sesi korup, hapus volume auth:
  ```bash
  docker compose down
  docker volume rm netmanager_wa_auth_data
  docker compose up -d whatsapp
  docker compose logs -f whatsapp
  ```

### 3. File upload / Foto KTP tidak muncul
- Pastikan symbolic link storage telah dibuat:
  ```bash
  docker compose exec app php artisan storage:link
  ```
- Periksa izin folder `storage/`:
  ```bash
  docker compose exec app chown -R www-data:www-data storage bootstrap/cache
  ```

### 4. Cache stale saat update kode
- Bersihkan cache Laravel:
  ```bash
  docker compose exec app php artisan optimize:clear
  ```
