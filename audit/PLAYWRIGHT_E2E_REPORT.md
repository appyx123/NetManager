# Playwright E2E Testing Report

## 1. Tujuan

Playwright ditambahkan untuk menguji alur pengguna yang paling kritis pada NetManager secara end-to-end melalui browser nyata.

Cakupan utama:

- Homepage dan navigasi ke login
- Login berhasil
- Login dengan password salah
- Penolakan akun yang belum aktif
- Dashboard customer
- Halaman billing dan status tagihan
- Pembuatan pengajuan gangguan
- Pembukaan detail pengajuan
- Pembatasan akses customer ke halaman admin
- Pemeriksaan tampilan form pada mobile viewport
- Kompatibilitas Chromium, Firefox, dan WebKit

Test menggunakan database dan akun development/testing, bukan production.

## 2. Audit Project Awal

### Stack

- Laravel 11
- PHP 8.2+
- Blade, Jetstream, Fortify, Livewire
- Vite dan Tailwind CSS
- MySQL 8.0
- Node.js WhatsApp gateway menggunakan Express dan `whatsapp-web.js`
- Midtrans untuk payment gateway
- MikroTik RouterOS API untuk network automation

### Package manager

Project menggunakan:

- Composer untuk dependency PHP
- npm untuk dependency frontend dan Playwright

### Runtime

Docker Compose menjalankan:

- `app`: Laravel, PHP-FPM, Nginx, Supervisor
- `mysql`: database MySQL
- `whatsapp`: WhatsApp gateway

Local URL default:

```text
http://127.0.0.1:8000
```

### Authentication

Authentication menggunakan Laravel Fortify dan Jetstream.

Role yang tersedia:

- `super_admin`
- `admin`
- `marketing`
- `technician`
- `customer`

Akun customer aktif untuk testing:

```text
budi@netmanager.local
```

Akun customer inactive untuk negative testing:

```text
customer@gmail.com
```

Password disediakan melalui environment variable E2E dan hanya digunakan pada environment testing/development.

### Existing testing sebelum Playwright

Project sudah memiliki:

- PHPUnit untuk unit dan feature test
- Laravel Dusk untuk browser test
- GitHub Actions untuk validasi Composer, migration, dan frontend build

Project belum memiliki Playwright, Cypress, Jest, atau Vitest.

Laravel Dusk sudah mencakup banyak navigasi role. Playwright difokuskan pada critical path customer, negative authentication, authorization, dan browser compatibility agar tidak sekadar menduplikasi seluruh test Dusk.

## 3. Fitur yang Dipilih

### Critical path

1. Guest membuka homepage.
2. Guest menuju halaman login.
3. Customer login menggunakan credential valid.
4. Customer masuk ke dashboard.
5. Customer membuka billing.
6. Customer membuka form pengajuan gangguan.
7. Customer mengirim complaint.
8. Customer membuka detail complaint.
9. Customer tidak dapat membuka halaman admin.

### Validation dan security

1. Password salah tidak boleh login.
2. Akun inactive tidak boleh login.
3. Customer menerima response HTTP `403` saat membuka halaman admin.

### Responsive

Form complaint diuji pada viewport mobile `390 x 844` dan dicek agar tidak menghasilkan horizontal overflow.

### External service

Midtrans, MikroTik, dan WhatsApp tidak dipanggil secara nyata oleh test. E2E hanya menguji alur UI dan backend yang tidak memerlukan transaksi eksternal. Hal ini mencegah pembayaran nyata, pengiriman WhatsApp, atau akses perangkat jaringan selama test.

## 4. Instalasi Playwright

Playwright di-install menggunakan npm:

```bash
npm install --save-dev @playwright/test
```

Karena konfigurasi dan test ditulis menggunakan TypeScript, dependency berikut juga ditambahkan:

```bash
npm install --save-dev typescript @types/node
```

Versi yang digunakan:

```text
Playwright 1.63.0
```

Browser yang di-install:

- Chromium
- Firefox
- WebKit

Perintah instalasi:

```bash
npx playwright install chromium firefox webkit
```

Pada CI, dependency OS browser di-install dengan:

```bash
npx playwright install --with-deps chromium firefox webkit
```

## 5. Struktur File

```text
playwright.config.ts
playwright/
  .gitignore
tests/
  e2e/
    auth.setup.ts
    login.spec.ts
    customer.spec.ts
tsconfig.json
docs/
  playwright.md
```

### `playwright.config.ts`

Konfigurasi menyediakan:

- `baseURL`
- `webServer` menggunakan `php artisan serve` ketika CI
- reuse server lokal jika server sudah berjalan
- project Chromium, Firefox, dan WebKit
- authentication dependency
- screenshot saat failure
- trace pada retry pertama
- video hanya saat failure
- HTML report
- retry pada CI
- satu worker pada CI untuk menjaga determinisme database

### `auth.setup.ts`

File ini login satu kali sebagai customer aktif dan menyimpan session state ke:

```text
playwright/.auth/customer.json
```

File authentication state tidak masuk Git.

Test customer menggunakan state tersebut agar tidak login ulang pada setiap test.

### `login.spec.ts`

Menguji:

- homepage menuju login
- credential valid
- password salah
- akun inactive

Login test hanya dijalankan pada Chromium. Alasannya, rate limiter aplikasi membatasi login menjadi lima percobaan per menit per IP/user key. Portal customer tetap dijalankan di Chromium, Firefox, dan WebKit menggunakan authentication state.

### `customer.spec.ts`

Menguji:

- dashboard dan billing
- complaint create dan detail
- customer authorization terhadap admin page
- mobile complaint form

## 6. Test Data

Test menggunakan data dari `DatabaseSeeder` pada environment development/testing.

Akun customer aktif:

```text
E2E_CUSTOMER_EMAIL=budi@netmanager.local
```

Akun customer inactive:

```text
E2E_INACTIVE_EMAIL=customer@gmail.com
```

Password:

```text
E2E_TEST_PASSWORD=password
```

Environment variable yang digunakan:

```bash
export E2E_BASE_URL=http://127.0.0.1:8000
export E2E_CUSTOMER_EMAIL=budi@netmanager.local
export E2E_INACTIVE_EMAIL=customer@gmail.com
export E2E_TEST_PASSWORD=password
```

Data invoice Budi dibuat deterministik oleh seeder:

- `INV-YYYYMMDD-001`: unpaid
- `INV-YYYYMMDD-002`: unpaid
- invoice kedua memiliki jatuh tempo lebih jauh

Seeder menggunakan `updateOrCreate` untuk user, lead, customer, dan invoice penting agar state test tidak menyimpan status lama dari run sebelumnya.

## 7. Perbaikan yang Ditemukan Saat Testing

### 7.1 Selector password ambigu

`getByLabel('Password')` menemukan dua element:

- input password
- tombol tampil/sembunyikan password yang juga memiliki `aria-label`

Perbaikan:

```ts
page.getByLabel('Password', { exact: true })
```

### 7.2 Selector homepage tidak cocok dengan text aktual

Test awal mencari `/login|masuk/i`, sedangkan UI menggunakan text `Log in Portal`.

Perbaikan:

```ts
page.getByRole('link', { name: /log\s*in|masuk/i })
```

### 7.3 Rate limiter login

Login dibatasi lima percobaan per menit. Login tidak dijalankan tiga kali pada setiap browser agar test tidak flaky.

Solusi:

- login flow dijalankan pada Chromium
- authenticated customer flow dijalankan pada Chromium, Firefox, dan WebKit

### 7.4 Assertion billing terlalu umum

Assertion `getByRole('heading', { name: /Tagihan/i })` cocok dengan dua heading.

Perbaikan menggunakan nama heading exact:

```ts
page.getByRole('heading', { name: 'Tagihan & Pembayaran' })
```

Status billing juga diuji menggunakan text UI aktual:

```ts
page.getByText('Belum Bayar', { exact: true })
```

### 7.5 Seeder invoice tidak deterministik

`firstOrCreate` tidak mengubah invoice yang sudah ada. Jika invoice sebelumnya sudah paid, seeder berikutnya tetap mempertahankan status paid.

Perbaikan:

```php
Invoice::updateOrCreate(...)
```

Status invoice kini selalu kembali ke state testing yang diharapkan: `unpaid`.

### 7.6 Seeder akun inactive tidak langsung ter-update

Data akun demo harus mempertahankan status inactive secara deterministik. Seeder menggunakan `updateOrCreate` dengan:

```php
'is_active' => $demoUser['is_active'] ?? true
```

### 7.7 Route complaint create tertutup route dinamis

Sebelumnya route berikut berada sebelum route static:

```php
Route::get('/complaints/{ticket}', ...);
Route::view('/complaints/create', ...);
```

Akibatnya `create` dibaca sebagai ticket ID dan menghasilkan 404.

Urutan diperbaiki menjadi:

```php
Route::view('/complaints/create', ...);
Route::get('/complaints/{ticket}', ...);
```

## 8. Hasil Playwright

Perintah yang dijalankan:

```bash
E2E_BASE_URL=http://127.0.0.1:8000 \
E2E_CUSTOMER_EMAIL=budi@netmanager.local \
E2E_INACTIVE_EMAIL=customer@gmail.com \
E2E_TEST_PASSWORD=password \
npm run test:e2e
```

Result akhir:

```text
Running 17 tests using 2 workers

17 passed
0 failed
0 skipped
Duration: 23.1s
```

Distribusi test:

- Setup authentication: 1
- Authentication Chromium: 4
- Customer Chromium: 4
- Customer Firefox: 4
- Customer WebKit: 4

Total: 17 test.

## 9. Verifikasi Tambahan

### Typecheck

```bash
npm run typecheck:e2e
```

Result: passed.

### Frontend build

```bash
npm run build
```

Result: passed.

### Composer validation

```bash
composer validate --no-check-publish
```

Result: valid dengan warning existing karena constraint `laravel/jetstream` menggunakan `*`.

### Unit test

```text
1 passed
```

### Docker runtime

App, MySQL, dan WhatsApp berhasil berjalan healthy. WhatsApp gateway juga melaporkan status `ready`.

## 10. Existing PHPUnit Issue

Full PHPUnit suite dijalankan menggunakan SQLite terisolasi.

Hasil:

```text
21 failed, 8 skipped, 5 passed
```

Failure berasal dari schema testing existing yang tidak memiliki kolom Jetstream:

```text
two_factor_secret
two_factor_recovery_codes
```

Contoh error:

```text
SQLSTATE[HY000]: General error: 1 table users has no column named two_factor_secret
```

Issue ini bukan disebabkan oleh Playwright. Playwright E2E tetap pass seluruhnya. Perbaikan schema PHPUnit sebaiknya dilakukan sebagai pekerjaan terpisah agar tidak mengubah scope E2E.

## 11. GitHub Actions

Workflow `.github/workflows/ci.yml` sekarang menjalankan Playwright pada:

- push ke `main`
- pull request ke `main`

Tahapan CI:

1. Checkout repository
2. Setup PHP 8.2
3. Setup Node.js 20
4. Buat environment SQLite terisolasi
5. Install Composer dependencies
6. Generate application key
7. Migrate database
8. Seed E2E data
9. Install npm dependencies
10. Build frontend
11. Install Chromium, Firefox, dan WebKit
12. Typecheck Playwright
13. Jalankan Playwright
14. Upload HTML report jika gagal

## 12. Command Reference

Install dependencies:

```bash
npm ci
npx playwright install chromium firefox webkit
```

Run all E2E:

```bash
npm run test:e2e
```

Run UI mode:

```bash
npm run test:e2e:ui
```

Run headed mode:

```bash
npm run test:e2e:headed
```

Run one file:

```bash
npx playwright test tests/e2e/customer.spec.ts
```

Run one test by title:

```bash
npx playwright test -g "inactive account"
```

Typecheck:

```bash
npm run typecheck:e2e
```

Open report:

```bash
npm run test:e2e:report
```

Debug one test:

```bash
npx playwright test tests/e2e/customer.spec.ts --debug
```

## 13. Kesimpulan

Playwright berhasil ditambahkan tanpa mengganti framework atau package manager existing.

Coverage fokus pada alur customer yang paling penting, menggunakan selector semantic, authentication state, test data development, browser matrix, CI integration, dan failure artifacts.

Result akhir E2E:

```text
PASS: 17
FAIL: 0
SKIP: 0
```
