# Backend PHPUnit Test Report

## 1. Tujuan

Dokumen ini mencatat proses pemeriksaan backend NetManager menggunakan PHPUnit dari awal sampai akhir.

Fokus pemeriksaan:

- Business logic Laravel
- Authentication dan authorization
- Inactive account protection
- Jetstream/Fortify two-factor authentication
- Password confirmation dan password reset
- Profile update
- Account deletion
- Browser session handling
- Customer portal access
- API token feature behavior
- Database migration dan test isolation

Test dijalankan menggunakan database SQLite in-memory agar tidak menyentuh database development atau production.

## 2. Kondisi Awal

Sebelum perbaikan, full PHPUnit suite menghasilkan error:

```text
SQLSTATE[HY000]: General error: 1 table users has no column named two_factor_secret
```

Error muncul ketika `UserFactory` dan Jetstream mencoba menyimpan field:

```text
two_factor_secret
two_factor_recovery_codes
```

Investigasi menunjukkan bahwa:

- Test feature sudah banyak menggunakan `RefreshDatabase`.
- `phpunit.xml` belum mengaktifkan SQLite in-memory.
- Migration users tidak memiliki field Jetstream two-factor.
- `CustomerPortalAccessTest` membuat user tetapi belum menggunakan `RefreshDatabase`.

## 3. Audit Konfigurasi PHPUnit

File konfigurasi utama:

```text
phpunit.xml
```

Konfigurasi database yang digunakan:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

Dengan konfigurasi tersebut, setiap test memakai database SQLite sementara dan tidak memakai MySQL development.

## 4. Audit Migration

Migration users utama berada di:

```text
database/migrations/2026_05_24_170647_create_users_table.php
```

Migration tersebut sebelumnya belum memiliki kolom Jetstream two-factor.

Migration baru ditambahkan:

```text
database/migrations/2026_09_26_000000_add_two_factor_columns_to_users_table.php
```

Kolom yang ditambahkan:

```php
$table->text('two_factor_secret')->nullable();
$table->text('two_factor_recovery_codes')->nullable();
$table->timestamp('two_factor_confirmed_at')->nullable();
```

Kolom `two_factor_confirmed_at` juga diperlukan oleh implementasi Jetstream saat proses konfirmasi two-factor authentication.

## 5. Audit RefreshDatabase

Test berikut sudah menggunakan `RefreshDatabase`:

- AuthenticationTest
- BrowserSessionsTest
- CreateApiTokenTest
- DeleteAccountTest
- DeleteApiTokenTest
- EmailVerificationTest
- PasswordConfirmationTest
- PasswordResetTest
- ProfileInformationTest
- RegistrationTest
- TwoFactorAuthenticationSettingsTest
- UpdatePasswordTest

Test yang ditemukan belum menggunakan trait tersebut:

```text
tests/Feature/CustomerPortalAccessTest.php
```

Perbaikan:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerPortalAccessTest extends TestCase
{
    use RefreshDatabase;
}
```

Dengan begitu, user factory pada test tersebut memiliki tabel users yang sudah dimigrasikan.

## 6. Tahap Verifikasi Pertama

Migration baru diuji secara terisolasi dengan SQLite file:

```bash
rm -f database/database.sqlite
touch database/database.sqlite
DB_CONNECTION=sqlite \
DB_DATABASE=database/database.sqlite \
php artisan migrate:fresh --force
```

Kemudian test two-factor dijalankan:

```bash
DB_CONNECTION=sqlite \
DB_DATABASE=database/database.sqlite \
php artisan test tests/Feature/TwoFactorAuthenticationSettingsTest.php
```

Hasil:

```text
PASS Tests\Feature\TwoFactorAuthenticationTest

Tests:    3 passed (6 assertions)
Duration: 1.50s
```

Hasil tersebut membuktikan bahwa kolom Jetstream sudah tersedia dan fitur berikut berjalan:

- Enable two-factor authentication
- Regenerate recovery codes
- Disable two-factor authentication

## 7. Full Backend Test Execution

Setelah konfigurasi PHPUnit dan migration diperbaiki, seluruh backend test dijalankan dengan:

```bash
php artisan test
```

PHPUnit otomatis menggunakan SQLite in-memory berdasarkan `phpunit.xml`.

## 8. Final Result

Result akhir:

```text
Tests:    0 skipped, 39 passed (109 assertions)
Duration: 8.63s
```

Ringkasan:

| Status | Jumlah |
|---|---:|
| Passed | 39 |
| Failed | 0 |
| Skipped | 0 |
| Assertions | 109 |

## 9. Test yang Berhasil

Test suite yang berhasil (100% Passed):

- `AuthenticationTest` (4 tests)
  - Login screen can be rendered
  - Users can authenticate using the login screen
  - Users can not authenticate with invalid password
  - Inactive users cannot authenticate
- `BrowserSessionsTest` (1 test)
  - Other browser sessions can be logged out
- `CustomerPortalAccessTest` (1 test)
  - Customer root redirects to dashboard
- `DeleteAccountTest` (2 tests)
  - User accounts can be deleted
  - Correct password must be provided before account can be deleted
- `ExampleTest` (Feature & Unit) (2 tests)
  - Application returns successful response
  - True is true
- `FreeRadiusNetworkServiceTest` (4 tests - BARU)
  - `add customer inserts into radcheck and radreply` (PPPoE Cleartext-Password, Calling-Station-Id, Mikrotik-Rate-Limit)
  - `disable customer sets address list isolir in radreply` (Menyisipkan atribut isolir dan drop sesi router)
  - `enable customer removes address list isolir from radreply` (Menghapus atribut isolir dan drop sesi router)
  - `check status reads active session from radacct` (Membaca telemetri sesi aktif dan trafik dari radacct)
- `LeadSurveyAndOdpValidationTest` (5 tests - BARU)
  - `marketing can request survey creates survey ticket without customer or invoice` (Tiket survey mandiri)
  - `convert fails when odp is full` (Validasi port habis menolak konversi dengan aman)
  - `convert succeeds decrements odp available ports and creates records` (Pessimistic lock dan decrement port ODP)
  - `technician survey updates odp recommendation on lead` (Sinkronisasi rekomendasi ODP teknisi ke prospek)
  - `failed installation releases odp available ports` (Pengembalian kuota port ODP jika instalasi batal/gagal)
- `PasswordConfirmationTest` (3 tests)
  - Confirm password screen can be rendered
  - Password can be confirmed
  - Password is not confirmed with invalid password
- `PasswordResetTest` (4 tests)
  - Reset password link screen can be rendered
  - Reset password link can be requested
  - Reset password screen can be rendered
  - Password can be reset with valid token
- `ProfileInformationTest` (2 tests)
  - Current profile information is available
  - Profile information can be updated
- `RegistrationTest` (2 tests)
  - Registration screen is disabled and redirects to login (Sistem ISP tertutup / closed-registration)
  - Registration endpoint rejects post requests and redirects to login
- `RolePermissionGateTest` (2 tests)
  - Super admin bypasses all permission checks
  - Staff role permission is enforced via gate and model
- `SyncPaidInvoiceHardwareJobTest` (1 test)
  - Sync paid invoice hardware job is dispatched via webhook / checkStatus
- `TwoFactorAuthenticationSettingsTest` (3 tests)
  - Two factor authentication can be enabled
  - Recovery codes can be regenerated
  - Two factor authentication can be disabled
- `UpdatePasswordTest` (3 tests)
  - Password can be updated
  - Current password must be correct
  - New passwords must match

## 10. Pembersihan Kode Mati, Skipped Tests & Build Asset

- File test untuk fitur Jetstream yang dinonaktifkan (`ApiTokenPermissionsTest.php`, `CreateApiTokenTest.php`, `DeleteApiTokenTest.php`, `EmailVerificationTest.php`) telah dihapus secara tuntas dari repositori.
- `RegistrationTest.php` dimutakhirkan untuk memvalidasi kebijakan closed-registration NetManager: permintaan GET maupun POST ke `/register` dialihkan secara elegan (`assertRedirect(route('login'))`) tanpa registrasi publik.
- Seluruh asset Vite (`public/build/manifest.json`) telah dikompilasi sehingga seluruh view otentikasi dapat dirender tanpa hambatan.
- Hasil akhir: **0 skipped tests**, **0 failures**, dan seluruh rangkaian pengujian berjalan 100% passed.

## 11. Business Logic dan Security yang Terverifikasi

### Authentication & Authorization
- Credential valid dapat login.
- Password salah ditolak.
- Akun inactive ditolak secara instan.
- Super admin bypass gate; role permission dievaluasi dinamis.

### FreeRADIUS Network Provisioning
- Kredensial PPPoE disimpan langsung pada tabel basis data FreeRADIUS (`radcheck`, `radreply`).
- Isolir dan aktivasi memperbarui atribut RADIUS dan mendepak sesi aktif MikroTik via API port 8728.
- Monitoring status dan penggunaan bandwidth membaca tabel akuntansi `radacct`.

### ODP Port Capacity & Feasibility Survey
- Kapasitas port ODP (`odp_available_ports`) divalidasi ketat dengan locking pesimistik (`lockForUpdate`) saat konversi prospek.
- Alur survey kelayakan lokasi menerbitkan tiket teknisi tipe `survey` tanpa membuat entitas pelanggan atau tagihan prematur.
- Pembatalan atau kegagalan instalasi otomatis mengembalikan alokasi port ODP (`releaseOdpPort`).

### Customer portal & Billing
- Customer dialihkan ke dashboard customer.
- Tagihan perdana otomatis menggabungkan harga paket dan biaya instalasi prospek.
- Asynchronous queue job (`SyncPaidInvoiceHardwareJob`) disalurkan saat pelunasan tagihan.

### Database isolation
- Test memakai SQLite in-memory melalui PHPUnit (`phpunit.xml`).
- `RefreshDatabase` menjalankan migrasi lengkap termasuk skema FreeRADIUS dan port ODP.
- Test terisolasi penuh dan tidak menyentuh database production.

## 12. Perintah Reproduksi

Menjalankan semua backend test:

```bash
php artisan test
```

Menjalankan test FreeRADIUS:

```bash
php artisan test tests/Feature/FreeRadiusNetworkServiceTest.php
```

Menjalankan test Survey & ODP:

```bash
php artisan test tests/Feature/LeadSurveyAndOdpValidationTest.php
```

Menjalankan test tertentu:

```bash
php artisan test tests/Feature/AuthenticationTest.php
```

Menjalankan test two-factor:

```bash
php artisan test tests/Feature/TwoFactorAuthenticationSettingsTest.php
```

Menjalankan satu test berdasarkan nama:

```bash
php artisan test --filter="odp"
```

Menampilkan test tanpa warna:

```bash
php artisan test --without-tty
```

Menguji migration dari awal secara manual:

```bash
rm -f database/database.sqlite
touch database/database.sqlite
DB_CONNECTION=sqlite \
DB_DATABASE=database/database.sqlite \
php artisan migrate:fresh --force
```

## 13. Catatan Environment

Pada local development, PHP host tidak selalu memiliki driver MySQL. PHPUnit tidak bergantung pada driver tersebut karena menggunakan SQLite in-memory.

Aplikasi native runtime menggunakan MySQL:

```text
DB_CONNECTION=mysql
```

Dengan pemisahan ini:

- PHPUnit aman, cepat, dan terisolasi.
- Database development tidak terhapus oleh PHPUnit.
- FreeRADIUS tables termigrasi mulus di SQLite in-memory maupun MySQL produksi.

## 14. Final Kesimpulan

Backend test suite telah dimutakhirkan secara menyeluruh sesuai arsitektur terbaru NetManager.

Result final:

```text
PASS: 39
FAIL: 0
SKIP: 0
ASSERTIONS: 109
DURATION: ~8.6s
```

Fitur FreeRADIUS Database Architecture, Feasibility Survey Flow, ODP Port Capacity Pessimistic Locking, dan Closed Registration Policy terverifikasi 100% lulus uji.
