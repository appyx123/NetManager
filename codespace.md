# Panduan Deployment & Simulasi di GitHub Codespaces

Dokumen ini berisi panduan lengkap untuk menjalankan **NetManager** di lingkungan **GitHub Codespaces** dengan setup otomatis **1-Click Ready** (tanpa konfigurasi manual).

---

## 1. Menjalankan Codespace (1-Click Launch)

1. Buka repositori project di GitHub: `https://github.com/appyx123/NetManager`
2. Klik tombol hijau **Code** $\rightarrow$ pilih tab **Codespaces**.
3. Klik **Create codespace on main**.
4. Tunggu beberapa saat selagi GitHub membangun container dan menjalankan script inisialisasi otomatis (`.devcontainer/setup.sh`).

---

## 2. Proses Otomasi (`setup.sh`)

Saat Codespace dibuat, sistem otomatis mengeksekusi tahapan berikut:

| No | Tahapan | Deskripsi |
| :---: | :--- | :--- |
| **1** | **Setup Environment** | Menyalin `.env.example` menjadi `.env` dengan konfigurasi Docker terstandarisasi (`DB_HOST=mysql`, `WA_API_URL=http://whatsapp:3000`). |
| **2** | **PHP Dependencies** | Menjalankan `composer install --prefer-dist` untuk menginstal seluruh package Laravel dan vendor. |
| **3** | **App Key** | Menjalankan `php artisan key:generate --force`. |
| **4** | **Frontend Assets** | Menjalankan `npm install` dan `npm run build` untuk kompilasi CSS Tailwind & aset JS via Vite. |
| **5** | **WhatsApp Microservice** | Menginstal dependensi Node.js di folder `whatsapp-service/`. |
| **6** | **Storage Symlink** | Menjalankan `php artisan storage:link` dan menyiapkan folder upload terisolasi. |
| **7** | **Database & Seeder** | Menjalankan container Docker `mysql` & `whatsapp`, menunggu healthcheck database siap, lalu menjalankan `php artisan migrate:fresh --seed`. |

---

## 3. Menjalankan Aplikasi di Codespaces

Aplikasi kini **100% otomatis aktif** saat container selesai dibuat. Web server pada port `8000`, container MySQL, dan WhatsApp Gateway otomatis berjalan di background.
Browser akan otomatis terbuka menampilkan antarmuka NetManager.

Jika Anda perlu me-restart atau mengontrol server secara manual:

### Kontrol Manual (Jika Dibutuhkan)
- **Restart Web Server:**
  ```bash
  php artisan serve --host=0.0.0.0 --port=8000
  ```
- **Restart Service Docker (MySQL & WhatsApp):**
  ```bash
  docker compose up -d mysql whatsapp
  ```
- **Full Stack Simulasi Production (Semua service dalam container):**
  ```bash
  docker compose up -d
  ```

---

## 4. Akses Port & URL Aplikasi

GitHub Codespaces otomatis mem-forward port berikut (dapat dilihat di tab **Ports** VS Code):

| Port | Service | Deskripsi / Alamat |
| :---: | :--- | :--- |
| **8000** | **Web Application** | Klik ikon bola dunia (*Open in Browser*) atau akses URL forwarded port `8000`. |
| **3000** | **WhatsApp API** | Endpoint microservice bot WhatsApp (`http://localhost:3000/status`). |
| **3306** | **MySQL Database** | Host database MySQL 8.0 internal container. |

---

## 5. Kredensial Akun Default (Database Seeder)

Semua akun contoh di bawah ini menggunakan kata sandi: **`password`**

| Role / Divisi | Email Login | Password | Keterangan Akun |
| :--- | :--- | :---: | :--- |
| **Super Admin** | `owner@netmanager.local` | `password` | Root master control, kelola user & pemeliharaan |
| **Super Admin (Demo)** | `superadmin@gmail.com` | `password` | Akun demo Super Admin |
| **Admin Operasional** | `admin@netmanager.local` | `password` | NOC, konfirmasi bayar manual, rute router |
| **Admin (Demo)** | `admin@gmail.com` | `password` | Akun demo Admin |
| **Marketing** | `marketing@netmanager.local` | `password` | Input lead prospek, konversi pelanggan baru |
| **Marketing (Demo)** | `marketing@gmail.com` | `password` | Akun demo Marketing (Kode: `DEMO2026`) |
| **Teknisi Lapangan** | `teknisi@netmanager.local` | `password` | Bursa tugas tiket, Meja Kerja pengerjaan |
| **Teknisi (Demo)** | `teknisi@gmail.com` | `password` | Akun demo Teknisi |
| **Pelanggan (Aktif)** | `budi@netmanager.local` | `password` | Portal klien (ID: `CUST-001`, paket aktif, ada tagihan) |

---

## 6. Perintah Berguna & Troubleshooting

### Reset Database & Data Uji
Jika ingin mengulang database dari awal:
```bash
php artisan migrate:fresh --seed
```

### Cek Status Container Docker
```bash
docker compose ps
```

### Cek Status Koneksi WhatsApp
```bash
curl http://localhost:3000/status
```

### Build Ulang Aset Frontend
```bash
npm run build
```
