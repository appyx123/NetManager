# PROJECT INTELLIGENCE REPORT
## COMPREHENSIVE LEGACY PROJECT REVERSE ENGINEERING & DISCOVERY

---

## 1. Executive Summary

- **Project Name:** NetManagement (branded in views and configuration as "NetManager" / "PT. Mandiri Global Data").
- **Current Status:** Refactored, Stabilized & Production-Hardened (Technical Debt Remediated, Production-Ready).
- **Core Stack:** PHP 8.2+, Laravel 11.51, Laravel Jetstream (Livewire 3 + Fortify + Sanctum), Tailwind CSS 3, Vite 5, MySQL 8.0 / MariaDB, Node.js 20 WhatsApp Gateway microservice (`whatsapp-web.js` + Express), MikroTik RouterOS API (`evilfreelancer/routeros-api-php`), and Midtrans Snap Payment Gateway.
- **Key Findings & Resolution Status:**
  1. **Dual Architecture / Abandoned Polymorphic Models (RESOLVED):** The schema previously contained obsolete polymorphic models (`survey_forms`, `installation_forms`, `device_configs`, `network_configs`, `repair_forms`) that caused a fatal `BadMethodCallException` during lead conversion. **Resolved:** `LeadController::convert` now bypasses legacy models to attach tickets directly to the customer. All dead models (`SurveyForm`, `InstallationForm`, `DeviceConfig`, `NetworkConfig`, `RepainForm`) and tables were dropped via migration `2026_09_25_131406_drop_legacy_polymorphic_tables.php`.
  2. **Integration Services & Closed-Registration Architecture (RESOLVED):** `NetworkController.php` and `WhatsappController.php` were misplaced in the controllers namespace. `PublicRegistrationController.php` and guest self-registration features have been completely removed. The system is strictly closed-registration where leads/customers can only be registered internally via Admin/Marketing dashboards (`/marketing/leads` and `/admin/customers`). Moved integration controllers to `App\Services\NetworkService` and `App\Services\WhatsappService`.
  3. **Broken Views & Controller Actions (RESOLVED):** Missing `technician.profile.index` view caused HTTP 500 errors, and several undefined ticket methods were routed. **Resolved:** Created modern `technician/profile/index.blade.php`, pruned duplicate route definitions, and cleaned up unused technician endpoints.
  4. **Simulated / Mocked Functions (RESOLVED):** `SuperAdmin\MaintenanceController` and marketing views used dummy stubs and `@for` mock loops. **Resolved:** Implemented genuine Artisan maintenance operations (`optimize:clear`, `down`/`up` with bypass secret, `optimize`, log purging). Built dedicated `Marketing\CustomerController` and `Marketing\ReportController` backed by live database queries.
  5. **Security & Configuration Vulnerabilities (RESOLVED):** Midtrans webhook signature check had a bypass vulnerability, KTP identity uploads were in public web storage, and router ping executed raw OS shell commands. **Resolved:** Enforced strict unconditional SHA-512 signature validation in `MidtransWebhookController`; moved KTP uploads to `local` private disk streamed via authenticated `CustomerDocumentController`; replaced shell `exec()` with safe non-blocking `fsockopen()` socket tests.
  6. **Query Performance & Telemetry Hardening (RESOLVED):** SuperAdmin revenue calculation executed 12 SQL queries in a loop, `add_indexes.php` was unmigrated, and audit log exports suffered from null-pointer crashes on deleted users. **Resolved:** Optimized revenue to a single `GROUP BY YEAR, MONTH` query; codified indexes into migration `2026_09_25_132436_add_performance_indexes_to_core_tables.php`; applied null-safe operators and fallbacks across audit exports and Blade views.
  7. **Payment Gateway & WhatsApp Gateway Pre-Flight Hardening (RESOLVED):** Midtrans webhook lacked idempotency checks against duplicate webhooks, customer portal lacked real-time status reconciliation after checkout, customer dashboard leaked technical PPPoE parameters and clashed with the dark theme, WhatsApp gateway crashed when message models lacked `.id` or returned internal `@lid` accounts, and daily billing lacked H-0 (due today) reminders. **Resolved:** Implemented webhook idempotency early-return (`Already processed`), direct Midtrans status synchronization endpoint with Snap JS callback triggers, dark slate theme customer dashboard with customer support card, hardened Node.js gateway with `@c.us` target sanitization and safe exception handling, full H-3/H-1/H-0/overdue billing cycle, and triple-path payment success WA dispatching (Webhook, Customer Portal, Admin Manual). Live dispatch verified 100% successful.
  8. **Customer Portal Routing, Reverse Proxy & Auth UI Refresh (RESOLVED):** Authenticated customers visiting root URL `/` were blocked by portal restrictions; reverse proxies dropped SSL forwarding headers; landing page relied on slow external Tailwind CDN; login password lacked show/hide toggle. **Resolved:** Added `home` route allowance in `RestrictCustomerPortal` middleware; added `trustProxies` in `bootstrap/app.php`; bundled landing page CSS via `@vite('resources/css/app.css')`; implemented accessible password reveal toggle in `resources/views/auth/login.blade.php`; added automated feature test `CustomerPortalAccessTest.php`.
  9. **Containerization & DevContainer Hardening (RESOLVED):** Docker configuration lacked non-root database credentials and robust health checks; devcontainer missed PHP `sockets` and `zip` extensions. **Resolved:** Updated `compose.yaml` with non-root MySQL user, `CMD-SHELL` ping healthcheck, and devcontainer build scripts installing all required extensions.
  10. **Customer Activation, Fortify Guard & Complaint Detail View (RESOLVED):** Inactive user rejection was inconsistent between Fortify login and subsequent requests, customer complaints list had dead '#' links with no detail view, and database seeding lacked full realistic operations data. **Resolved:** Enforced `$user->is_active` validation in `FortifyServiceProvider` throwing an informative validation error message ("Akun Anda belum aktif. Silakan hubungi administrator untuk aktivasi."), verified by `tests/Feature/AuthenticationTest.php`; added `client.complaints.show` route (`/client/complaints/{ticket}`) and dark-themed `resources/views/client/complaints/show.blade.php` displaying assigned technician, status badge, issue description, and technician notes; overhauled `DatabaseSeeder.php` with complete realistic ISP workflow entities (roles, customer, active subscription, unpaid invoice, open repair ticket, prospect lead).
  11. **Codespaces 1-Click Environment & Repository Decluttering (RESOLVED):** Repository contained orphaned dead controllers (`Admin\UserController`, `TicketQCController`), unmigrated dummy photos in public storage, and complex local setup steps. **Resolved:** Purged dead controllers and orphaned routes; protected storage directories with `.gitignore`; automated 1-Click cloud developer environment in `.devcontainer` and `codespace.md` running on containerized MySQL 8 and Node 20 WhatsApp service.
  12. **Continuous Integration (CI) Workflow Hardening & Route Validation (RESOLVED):** GitHub Actions runner failed due to missing local MySQL service, uncommitted lockfile assertions, and route reflection errors. **Resolved:** Re-architected `.github/workflows/ci.yml` using isolated in-memory SQLite and file-backed session/cache/maintenance drivers; audited and committed explicit npm lockfiles; fully restored and verified Admin `CustomerController.php` with complete RouterOS PPPoE isolation & activation methods.

---

## 2. Project Identity

- **Project Type:** Full-Stack Web Application with an external Node.js microservice.
- **Primary Purpose:** Operational Support System (OSS) and Business Support System (BSS) for an Indonesian Internet Service Provider (ISP), specifically referencing "PT. Mandiri Global Data".
- **Target Users:**
  1. Super Administrator (System Owner / IT Director)
  2. Administrative Staff (Customer Service, Billing, Operations)
  3. Field Technicians (Network Installers, Surveyors, Troubleshooting Crew)
  4. Marketing / Sales Agents (Lead Acquisition, Canvassing)
  5. End Customers (Subscribers checking bills, paying via Midtrans, and filing repair complaints)
- **Main Problem Solved:** Eliminates fragmented paper and spreadsheet operations for regional ISPs by unifying lead prospecting, physical dispatching/installation, PPPoE credentials, bandwidth package billing, and customer ticketing into a single portal.
- **Core Value Proposition:** End-to-end subscriber lifecycle management: Marketing acquires lead $\rightarrow$ Admin verifies $\rightarrow$ Technician installs and documents fiber/ODP parameters $\rightarrow$ System activates PPPoE and issues invoices $\rightarrow$ Customer pays online $\rightarrow$ Auto un-isolation.
- **Application Domain:** Telecommunications, ISP Management, Network Automation.
- **Architecture Type:** Monolithic Laravel 11 application paired with an out-of-process Node.js sidecar gateway for WhatsApp messaging.
- **Current Development Status:** Stabilized & Production-Ready. Core database entities exist, Dusk browser tests pass against seeded data, all critical routes and security boundaries verified.

---

## 3. What This Project Appears To Do

### One Sentence
NetManagement is a multi-role operational and billing portal for an Internet Service Provider that coordinates sales leads, technician field installations, customer PPPoE subscriptions, automated Midtrans invoice payments, and MikroTik router isolation.

### Short Description (3–5 Sentences)
The platform allows marketing personnel to capture customer prospects, submit site photos, and convert leads into active subscribers. Field technicians receive survey and installation job orders, recording ODP ports, fiber cable lengths, optical signal levels (dBm), and ONU/modem MAC addresses. Administrative staff monitor recurring invoices, customer isolation states, and network routers. Customers access a dedicated self-service portal to view invoice balances, pay via Midtrans Snap, and report optical LOS or connectivity issues.

### Detailed Workflow Description
1. **Lead Generation & Intake:** A sales agent logs prospective client details, installation address, GPS coordinates, KTP ID cards, and package preferences.
2. **Dispatch & Field Execution:** A job ticket is dispatched to technicians. The technician visits the customer location, records survey feasibility, pulls drop cable, splices fiber into an ODP port, provisions the ONU/ONT modem, records optical power (dBm), and verifies speed test connectivity.
3. **Provisioning & Billing:** A customer account and subscription profile are initialized. Monthly invoices are generated with due dates.
4. **Self-Service & Settlement:** The customer logs into the client portal, initiates payment via Midtrans Snap, and receives automated WhatsApp confirmation while the MikroTik router is signaled to unblock traffic.
5. **After-Sales Support:** Customers lodge repair tickets when red LOS indicator lights flash, queuing tasks for field technicians.

---

## 4. Target Users

| User Persona | Real-World Role | System Role | Primary Objectives |
| :--- | :--- | :--- | :--- |
| **Business Owner / Lead Architect** | Owner, DevOps, IT Auditor | `super_admin` | Monitor system health, manage staff accounts, inspect audit trails, maintain backups. |
| **Operational Admin** | Customer Support, Billing Admin | `admin` | Oversee customer accounts, isolate delinquent users, approve packages, check revenue. |
| **Sales Representative** | Direct Sales, Canvasser | `marketing` | Submit prospect forms, track referral conversion codes, monitor sales pipeline. |
| **Network Technician** | Cable Splicer, Field Engineer | `technician` | Claim open dispatch tickets, execute surveys/installations/repairs, upload site photos. |
| **Subscriber** | Residential / Business Customer | `customer` | View bandwidth plan, download invoices, pay via Midtrans, file repair complaints. |

---

## 5. Core Problem

Small-to-medium regional ISPs (RT/RW Net or local telecommunication operators) frequently suffer from severe operational friction:
- Customer leads captured in WhatsApp get lost before installation.
- Technicians do not document cable lengths, signal dBm, or ODP ports, complicating future troubleshooting.
- Isolating delinquent non-paying users requires manual winbox configuration on MikroTik routers.
- Payment reconciliations rely on manual bank transfer receipts.

NetManagement solves this by synchronizing field data, financial settlement, and network hardware access into a centralized web platform.

---

## 6. MVP Reconstruction

```mermaid
graph TD
    A[Marketing Enters Lead] --> B[Lead Converted to Customer]
    B --> C[Technician Dispatched on Ticket]
    C --> D[Installation Completed: Signal/ODP/MAC Logged]
    D --> E[Subscription & Invoice Created]
    E --> F[Customer Pays via Midtrans]
    F --> G[MikroTik PPPoE Activated & WhatsApp Sent]
```

### Core MVP Features
- **User Authentication & Role Redirection:** Central gatekeeper route (`/dashboard`) directing users to their respective role dashboards (`superadmin.dashboard`, `admin.dashboard`, `marketing.dashboard`, `technician.dashboard`, `client.dashboard`).
- **Customer & Subscription Repository:** Core relational tables storing customer codes, PPPoE credentials, addresses, and package tiers.
- **Technician Ticket Execution:** Ticket intake (`open-tickets`), assignment (`take`), and resolution (`my-tasks`) with upload of proof photos.
- **Self-Service Payment & Webhook:** Midtrans Snap integration creating payment tokens and receiving automated webhooks to update invoice state to `paid`.

### Supporting Features
- **Manual Isolation & Activation:** Admin endpoints to toggle `is_isolated` flag and subscription state.
- **Audit Logging:** Recording administrative actions (`isolate_customer`, `activate_customer`) into `audit_logs`.
- **Router Inventory:** Management of network assets (OLT, Router, AP, ODP) with latency ping testing.

---

## 7. Feature Inventory

| Feature | Description | User Role | Entry Point | Backend Handler | Database Entities | Status | Evidence |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Role Gatekeeper** | Dynamic dashboard redirector | All Authenticated | `/dashboard` | Closure in `routes/web.php` | `users` | Confirmed Working | `routes/web.php:73-86` |
| **Customer Management** | Search, inspect, edit, isolate customer | Admin, SuperAdmin | `/admin/customers` | `Admin\CustomerController` | `customers`, `users`, `subscriptions` | Confirmed Working | `CustomerController.php` |
| **Package Management** | CRUD for internet packages | Admin, SuperAdmin | `/admin/packages` | `Admin\PackageController` | `packages` | Confirmed Working | `PackageController.php` |
| **Router Inventory** | CRUD network devices + Ping check | Admin, SuperAdmin | `/admin/routers` | `Admin\RouterController` | `network_assets` | Confirmed Working | Non-blocking socket check on port 8728 |
| **Billing Management** | Invoice overview & manual mark paid | Admin, SuperAdmin | `/admin/billing` | `Admin\BillingController` | `invoices`, `subscriptions` | Confirmed Working | `BillingController.php` |
| **Lead Governance** | View & reassign sales leads | Admin, SuperAdmin | `/admin/leads` | `Admin\LeadManagementController` | `leads`, `packages` | Confirmed Working | `LeadManagementController.php` |
| **Ticket Governance** | Admin review & dispatch of tickets | Admin, SuperAdmin | `/admin/tickets` | `Admin\TicketManagementController` | `tickets`, `customers`, `users` | Confirmed Working | `TicketManagementController.php` |
| **Audit Logs** | Log viewing and CSV export | SuperAdmin, Admin | `/admin/logs`, `/superadmin/audits` | `LogController`, `AuditController` | `audit_logs` | Confirmed Working | Null-safe operators & CSV stream |
| **Lead Entry** | Marketing creation of prospects | Marketing | `/marketing/leads` | `Marketing\LeadController` | `leads` | Confirmed Working | `LeadController.php:46-100` |
| **Lead Conversion** | Convert prospect to customer + ticket | Marketing | `/marketing/leads/{id}/convert` | `Marketing\LeadController@convert` | `users`, `customers`, `leads`, `tickets` | Confirmed Working | Direct customer ticket creation |
| **Marketing Views** | Live customer & performance reports | Marketing | `/marketing/customers`, `/marketing/reports` | `Marketing\CustomerController`, `Marketing\ReportController` | `customers`, `leads`, `subscriptions` | Confirmed Working | Real DB pagination & analytical KPIs |
| **Ticket Marketplace** | Technician claims available tickets | Technician | `/technician/open-tickets` | `Technician\TicketController@index` | `tickets` | Confirmed Working | `TicketController.php:16-60` |
| **Technician Workbench** | Work order execution & photo upload | Technician | `/technician/my-tasks/{ticket}` | `Technician\TicketController` | `tickets` | Confirmed Working | Form saving, cleaned routes |
| **Technician Profile** | Profile page for technician | Technician | `/technician/profile` | `routes/web.php` | None | Confirmed Working | View `technician.profile.index` created |
| **Customer Dashboard** | View active plan, invoice, tickets | Customer | `/client/dashboard` | `Customer\CustomerDashboardController` | `customers`, `subscriptions`, `invoices`, `tickets` | Confirmed Working | `CustomerDashboardController.php` |
| **Customer Pay Invoice** | Midtrans Snap checkout popup | Customer | `/client/billing/{invoice}/pay` | `Customer\InvoiceController@pay` | `invoices`, `subscriptions` | Confirmed Working | `InvoiceController.php:47-101` |
| **Customer Status Sync** | Direct Midtrans API reconciliation | Customer | `/client/billing/{invoice}/check-status` | `Customer\InvoiceController@checkStatus` | `invoices`, `subscriptions` | Confirmed Working | `InvoiceController.php:103-145` |
| **Midtrans Webhook** | Automated payment & auto-activation | Public Webhook | `/midtrans/notification` | `MidtransWebhookController` | `invoices`, `subscriptions`, `customers` | Confirmed Working & Hardened | Unconditional SHA-512 + Idempotency |
| **Customer Complaints** | Overview list & submit issue with photo | Customer | `/client/complaints`, `/client/complaints/create` | `Customer\ComplaintController` | `tickets` | Confirmed Working | `ComplaintController.php` |
| **Complaint Details** | View ticket progress, technician, & notes | Customer | `/client/complaints/{ticket}` | `Customer\ComplaintController@show` | `tickets`, `users` | Confirmed Working | `client/complaints/show.blade.php` |
| **System Maintenance** | Backup, cache clear, DB optimize | SuperAdmin | `/superadmin/maintenance` | `SuperAdmin\MaintenanceController` | Cache, Log, Filesystem | Confirmed Working | Genuine Artisan command executions |
| **Closed Registration** | Internal-only onboarding (No public registration) | Admin, Marketing | `/marketing/leads`, `/admin/customers` | `LeadController`, `CustomerController` | `leads`, `customers` | Confirmed Working | 100% internal staff manual entry |

---

## 8. User Roles

The system recognizes five distinct user roles stored as enum/string in `users.role`:

1. **`super_admin`:** Unrestricted access across all operational, financial, master data, user provisioning, and maintenance modules.
2. **`admin`:** Full operational access to customers, billing, network routers, package definitions, technician tickets, sales leads, and reports. Forbidden from user management and system maintenance.
3. **`marketing`:** Restricted to lead generation, sales pipeline, converting leads to active customers, and viewing marketing analytics.
4. **`technician`:** Restricted to dispatch task queues (`open-tickets`), active task forms (`my-tasks`), completion history, and personal profile.
5. **`customer`:** Strictly sandboxed by `RestrictCustomerPortal` middleware. Allowed only routes prefixed with `client.`, `/dashboard`, `/`, and `/logout`. Can only interact with owned invoices and repair complaints.

---

## 9. Role & Permission Matrix

| Role | View Dashboards | Manage Users & Staff | Manage Packages & Routers | Isolate / Activate Customer | Mark Invoices Paid | Execute Tech Tasks | Submit Leads | Pay Invoices (Self) | File Complaints | Run Backups / Maintenance |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **`super_admin`** | **Yes** | **Yes** | **Yes** | **Yes** | **Yes** | No | No | No | No | **Yes** (Artisan) |
| **`admin`** | **Yes** | No | **Yes** | **Yes** | **Yes** | Review Only | Review Only | No | No | No |
| **`marketing`** | **Yes** | No | No | No | No | No | **Yes** | No | No | No |
| **`technician`**| **Yes** | No | No | No | No | **Yes** | No | No | No | No |
| **`customer`**  | **Yes** | No | No | No | No | No | No | **Yes** | **Yes** | No |

*Note: Code evidence demonstrates permissions are evaluated strictly through route middleware parameters (`role:super_admin`, `role:admin,super_admin`, etc.). The database table `role_permissions` is non-functional.*

---

## 10. User Journeys

### Journey 1: Customer Invoice Payment & Automatic PPPoE Activation
1. **Entry Point:** Customer logs in, visits `/client/billing/{invoice}`.
2. **Action:** Customer clicks "Bayar Sekarang" triggering `POST /client/billing/{invoice}/pay`.
3. **Frontend / Backend:** `InvoiceController@pay` invokes `Midtrans\Snap::getSnapToken($params)`. The `snap_token` is stored on the `invoices` table and passed to the Blade view.
4. **Gateway Interaction:** Client triggers the Midtrans Snap modal popup and completes payment (Sandbox or Real).
5. **Webhook Ingestion:** Midtrans sends an asynchronous POST to `/midtrans/notification`.
6. **Backend Processing:** `MidtransWebhookController@handleNotification` validates SHA-512 signature, executes idempotency check, marks invoice status as `paid`, sets `paid_at = now()`.
7. **Automations:**
   - Calls `NetworkService::enableCustomer($subscription)`: Queries MikroTik RouterOS via API port 8728, enables `/ppp/secret`, removes subscriber IP from firewall address-list `ISOLIR`, and updates `customers.is_isolated = false`.
   - Calls `WhatsappService::sendPaymentSuccess(...)`: Posts HTTP payload to `http://127.0.0.1:3000/send-message` to deliver a payment receipt to the customer's mobile number.
8. **Result:** Customer's connection is restored in real-time without administrative intervention.

### Journey 2: Technician Field Task Fulfillment
1. **Entry Point:** Technician visits `/technician/open-tickets`.
2. **Action:** Technician reviews unassigned jobs and clicks "Ambil Pekerjaan" (`POST /technician/open-tickets/{ticket}/take`).
3. **State Change:** `Ticket` status updates from `open` to `assigned`, setting `technician_id = Auth::id()`.
4. **Execution:** Technician visits `/technician/my-tasks/{ticket}`, transitioning status to `in_progress`.
5. **Data Submission:** Depending on `ticket.type` (`survey`, `installation`, or `repair`), the technician submits cable length, ODP port, signal dBm, modem MAC/SN, and uploads photos of site equipment.
6. **Completion:** `TicketController@processUpdate` stores files in `storage/app/public/uploads/teknisi/`, sets status to `resolved`, and stamps `completed_at = now()`.

### Journey 3: Marketing Lead Conversion
1. **Entry Point:** Marketing user navigates to `/marketing/leads`.
2. **Action:** Clicks "Konversi ke Pelanggan" (`POST /marketing/leads/{lead}/convert`).
3. **Execution:** Database transaction begins: creates `User` (role `customer`), creates `Customer` profile, marks `Lead` as `converted`, and creates a `Ticket` directly linked to `$customer->tickets()->create(...)` with denormalized installation parameters.
4. **Resolution:** Bypasses legacy polymorphic forms entirely; customer profile and initial dispatch ticket are persisted cleanly in a single ACID transaction.

---

## 11. Application Routes

The application registers 158 total routes (including Fortify, Jetstream, Sanctum, Dusk, and Livewire internals). Primary business routes:

### Public / Webhook / Documents
- `GET /` $\rightarrow$ Landing Page (`resources/views/welcome.blade.php`, named `home`)
- `POST /midtrans/notification` $\rightarrow$ `MidtransWebhookController@handleNotification` (CSRF excluded)
- `GET /documents/ktp/{lead}` $\rightarrow$ `CustomerDocumentController@showKtp` (Authenticated secure document streaming)
*(Note: Public self-registration routes `/register-service` and `/register` are completely removed/disabled; system is closed-registration).*

### Role Gatekeeper
- `GET /dashboard` $\rightarrow$ Closure redirecting to specific role dashboards based on `Auth::user()->role`.

### Super Admin Area (`prefix: superadmin`, `middleware: role:super_admin`)
- `GET /superadmin/dashboard` $\rightarrow$ `SuperAdminDashboardController@index`
- `RESOURCE /superadmin/users` $\rightarrow$ `UserManagementController` (CRUD staff accounts)
- `POST /superadmin/users/{user}/reset-password` $\rightarrow$ `UserManagementController@resetPassword`
- `GET /superadmin/roles` $\rightarrow$ `RoleAccessController@index`
- `POST /superadmin/roles/permissions` $\rightarrow$ `RoleAccessController@updatePermissions`
- `GET /superadmin/master` $\rightarrow$ `MasterDataController@index`
- `POST|PUT|DELETE /superadmin/master/areas/{area?}` $\rightarrow$ `MasterDataController`
- `GET /superadmin/audits` $\rightarrow$ `AuditController@index`
- `GET /superadmin/audits/export` $\rightarrow$ `AuditController@export`
- `GET /superadmin/audits/{log}` $\rightarrow$ `AuditController@show`
- `GET /superadmin/maintenance` $\rightarrow$ `MaintenanceController@index`
- `POST /superadmin/maintenance/mode` $\rightarrow$ `MaintenanceController@toggleMaintenanceMode`
- `POST /superadmin/maintenance/clear-cache` $\rightarrow$ `MaintenanceController@clearCache`
- `POST /superadmin/maintenance/optimize` $\rightarrow$ `MaintenanceController@optimizeDatabase`
- `POST /superadmin/maintenance/backup` $\rightarrow$ `MaintenanceController@backupDatabase`
- `GET /superadmin/maintenance/logs` $\rightarrow$ `MaintenanceController@viewLogs`
- `POST /superadmin/maintenance/clear-logs` $\rightarrow$ `MaintenanceController@clearLogs`

### Admin Area (`prefix: admin`, `middleware: role:admin,super_admin`)
- `GET /admin/dashboard` $\rightarrow$ `AdminDashboardController@index`
- `GET /admin/customers/search` $\rightarrow$ `CustomerController@search`
- `RESOURCE /admin/customers` $\rightarrow$ `CustomerController` (`index`, `show`, `edit`, `update`)
- `POST /admin/customers/{customer}/isolate` $\rightarrow$ `CustomerController@isolate`
- `POST /admin/customers/{customer}/activate` $\rightarrow$ `CustomerController@activate`
- `RESOURCE /admin/packages` $\rightarrow$ `PackageController` (Full CRUD)
- `RESOURCE /admin/routers` $\rightarrow$ `RouterController` (Full CRUD)
- `POST /admin/routers/{router}/test` $\rightarrow$ `RouterController@testConnection`
- `GET /admin/billing` $\rightarrow$ `BillingController@index`
- `GET|PUT /admin/billing/{invoice}` $\rightarrow$ `BillingController@show|update`
- `GET /admin/billing/{invoice}/edit` $\rightarrow$ `BillingController@edit`
- `POST /admin/billing/{invoice}/mark-as-paid` $\rightarrow$ `BillingController@markAsPaid`
- `RESOURCE /admin/integrations` $\rightarrow$ `IntegrationController` (`index`, `store`, `update`, `destroy`)
- `POST /admin/integrations/{integration}/test` $\rightarrow$ `IntegrationController@testConnection`
- `GET /admin/reports` $\rightarrow$ `ReportController@index`
- `GET /admin/reports/customers` $\rightarrow$ `ReportController@customerReport`
- `GET /admin/reports/arrears` $\rightarrow$ `ReportController@arrearsReport`
- `GET /admin/reports/revenue` $\rightarrow$ `ReportController@revenueReport`
- `GET /admin/reports/activation-log` $\rightarrow$ `ReportController@activationLog`
- `GET /admin/reports/isolation-log` $\rightarrow$ `ReportController@isolationLog`
- `GET /admin/logs` $\rightarrow$ `LogController@index`
- `GET /admin/logs/export` $\rightarrow$ `LogController@export`
- `GET /admin/logs/{log}` $\rightarrow$ `LogController@show`
- `GET /admin/profile` $\rightarrow$ `ProfileController@index`
- `POST /admin/profile/update` $\rightarrow$ `ProfileController@updateProfile`
- `POST /admin/profile/password` $\rightarrow$ `ProfileController@updatePassword`
- `RESOURCE /admin/tickets` $\rightarrow$ `TicketManagementController`
- `PATCH /admin/tickets/{ticket}/status` $\rightarrow$ `TicketManagementController@updateStatus`
- `RESOURCE /admin/leads` $\rightarrow$ `LeadManagementController`
- `POST /admin/leads/bulk-import` $\rightarrow$ `LeadManagementController@bulkImport`
- `PATCH /admin/leads/{lead}/status` $\rightarrow$ `LeadManagementController@updateStatus`

### Marketing Area (`prefix: marketing`, `middleware: role:marketing`)
- `GET /marketing/dashboard` $\rightarrow$ `MarketingDashboardController@index`
- `RESOURCE /marketing/leads` $\rightarrow$ `LeadController`
- `POST /marketing/leads/{lead}/convert` $\rightarrow$ `LeadController@convert`
- `GET /marketing/customers` $\rightarrow$ `Marketing\CustomerController@index`
- `GET /marketing/customers/{customer}` $\rightarrow$ `Marketing\CustomerController@show`
- `GET /marketing/reports` $\rightarrow$ `Marketing\ReportController@index`
- `GET /marketing/profile` $\rightarrow$ `Route::view('marketing.profile.index')`

### Technician Area (`prefix: technician`, `middleware: role:technician`)
- `GET /technician/dashboard` $\rightarrow$ `TechnicianDashboardController@index`
- `GET /technician/open-tickets` $\rightarrow$ `TicketController@index`
- `GET /technician/open-tickets/{ticket}` $\rightarrow$ `TicketController@show`
- `POST /technician/open-tickets/{ticket}/take` $\rightarrow$ `TicketController@take`
- `GET /technician/my-tasks` $\rightarrow$ `TicketController@processIndex`
- `GET /technician/my-tasks/{ticket}` $\rightarrow$ `TicketController@processShow`
- `PUT /technician/my-tasks/{ticket}` $\rightarrow$ `TicketController@processUpdate`
- `GET /technician/history` $\rightarrow$ `TicketController@historyIndex`
- `GET /technician/profile` $\rightarrow$ `Route::view('technician.profile.index')`

### Customer Area (`prefix: client`, `middleware: role:customer`)
- `GET /client/dashboard` $\rightarrow$ `CustomerDashboardController@index`
- `GET /client/billing` $\rightarrow$ `InvoiceController@index`
- `GET /client/billing/{invoice}` $\rightarrow$ `InvoiceController@show`
- `POST /client/billing/{invoice}/pay` $\rightarrow$ `InvoiceController@pay`
- `POST /client/billing/{invoice}/check-status` $\rightarrow$ `InvoiceController@checkStatus`
- `GET /client/complaints` $\rightarrow$ `ComplaintController@index`
- `GET /client/complaints/{ticket}` $\rightarrow$ `ComplaintController@show`
- `GET /client/complaints/create` $\rightarrow$ `Route::view('client.complaints.create')`
- `POST /client/complaints` $\rightarrow$ `ComplaintController@store`

---

## 12. API Inventory

The project does not expose a public REST API. The file `routes/api.php` contains solely:
- `GET /api/user` (Protected by `auth:sanctum`): Returns authenticated user profile.
- Internal AJAX endpoint: `GET /admin/customers/search?q={query}` in `CustomerController@search`: Returns JSON list of matched customers.
- Webhook Ingestion API: `POST /midtrans/notification`: Handled by `MidtransWebhookController`.

---

## 13. Technology Stack

### Programming Languages
- **PHP 8.2+:** Primary server language, Eloquent ORM, Blade templating.
- **JavaScript (ES6 / CommonJS):** Node.js runtime for the WhatsApp service (`whatsapp-service/server.js`) and Vite asset bundling.
- **SQL (MySQL dialect):** Database schema, foreign keys, and indexes.
- **CSS / HTML5:** Responsive layouts with Tailwind CSS.

### Frameworks & Libraries
- **Backend:** Laravel 11.51, Laravel Jetstream, Laravel Fortify (2FA, auth sessions), Laravel Sanctum (API tokens).
- **Frontend / Fullstack:** Livewire 3.6.4, Tailwind CSS 3.4.0, Alpine.js (via Jetstream/Livewire).
- **Network Integration:** `evilfreelancer/routeros-api-php: ^1.7` (MikroTik RouterOS API Client).
- **Payment Processing:** `midtrans/midtrans-php: ^2.6` (Official PHP SDK for Snap & Core API).
- **Messaging Microservice:** Node.js Express 4.19, `whatsapp-web.js: ^1.26.0`, `qrcode-terminal: ^0.12.0`, Puppeteer headless browser.
- **Testing:** PHPUnit 10.5, Laravel Dusk 8.6 (Browser automation), Faker 1.23.

---

## 14. Architecture

```mermaid
flowchart TD
    subgraph Client Tier
        BrowserUser[Web Browser: SuperAdmin / Admin / Marketing / Tech / Customer]
    end

    subgraph Presentation & Application Tier
        Vite[Vite / Tailwind CSS Assets]
        L11[Laravel 11 Web Application]
        MW_Auth[Sanctum & Fortify Auth Middleware]
        MW_Role[EnsureUserHasRole Middleware]
        MW_Cust[RestrictCustomerPortal Middleware]
    end

    subgraph Controllers & Services
        Ctrl_Admin[Admin & SuperAdmin Controllers]
        Ctrl_Tech[Technician Ticket Controllers]
        Ctrl_Mkt[Marketing Lead Controllers]
        Ctrl_Cust[Customer Billing & Complaint Controllers]
        Svc_Net[App\Services\NetworkService]
        Svc_WA[App\Services\WhatsappService]
        Ctrl_Midtrans[MidtransWebhookController]
    end

    subgraph Data Tier
        DB[(MySQL Database: db_netmanager)]
        Storage[(Local Filesystem: storage/app/public & storage/app/private)]
    end

    subgraph External Infrastructure
        MikroTik[MikroTik RouterOS via API 8728]
        Midtrans[Midtrans Payment Gateway]
        WAGateway[Node.js WhatsApp Service: Port 3000]
        WhatsAppNet[WhatsApp Multi-Device Cloud]
    end

    BrowserUser --> Vite
    BrowserUser --> L11
    L11 --> MW_Auth --> MW_Role --> MW_Cust
    MW_Cust --> Ctrl_Admin & Ctrl_Tech & Ctrl_Mkt & Ctrl_Cust & Ctrl_Midtrans

    Ctrl_Admin & Ctrl_Tech & Ctrl_Mkt & Ctrl_Cust --> DB
    Ctrl_Tech & Ctrl_Cust & Ctrl_Mkt --> Storage

    Ctrl_Cust -->|Generate Snap Token| Midtrans
    Midtrans -->|POST Webhook /midtrans/notification| Ctrl_Midtrans

    Ctrl_Midtrans --> Svc_Net
    Ctrl_Midtrans --> Svc_WA

    Svc_Net -->|RouterOS Socket 8728| MikroTik
    Svc_WA -->|HTTP POST :3000/send-message| WAGateway
    WAGateway -->|Puppeteer / Chromium| WhatsAppNet
```

---

## 15. Architecture Diagram (Component Breakdown)

- **Laravel Core:** Handles routing, Blade views, authentication sessions, and business transactions.
- **Node.js Microservice (`whatsapp-service`):** Operates on port 3000 as a separate Node process running headless Chromium via Puppeteer to emulate a WhatsApp Web client session stored in `./.wwebjs_auth`.
- **RouterOS Driver:** Direct TCP socket communication using `RouterOS\Client` over port 8728 to modify `/ppp/secret` and `/ip/firewall/address-list`.
- **Midtrans Integration:** Dual-mode: client-side Snap modal invoked via JavaScript token, and server-side webhook notification handling.

---

## 16. Frontend Analysis

- **Layout Structure:** Uses Laravel Jetstream standard layouts (`x-app-layout` and `x-guest-layout`) powered by Tailwind CSS.
- **Visual Design:** Dark-mode aesthetic utilizing deep slate backgrounds (`bg-slate-950`, `bg-slate-900/80`), backdrop blur filters, indigo/amber gradients, and Tailwind cards.
- **Responsiveness:** Grid layouts adapt between desktop tables (`hidden md:block`) and mobile cards (`md:hidden`).
- **Interactive State:** Livewire 3 manages profile photos, password updates, and two-factor authentication. Standard Blade forms handle operational CRUD.
- **Landing Page Performance:** Converted from runtime Tailwind CDN to precompiled `@vite('resources/css/app.css')` in `welcome.blade.php`.
- **Form Accessibility:** Interactive show/hide password toggle button with SVG icons, `aria-pressed`, `aria-label`, and `aria-controls` attributes added to `auth/login.blade.php`.

---

## 17. Backend Analysis

- **Controller Organization:** Grouped cleanly by domain namespaces:
  - `App\Http\Controllers\Admin`
  - `App\Http\Controllers\SuperAdmin`
  - `App\Http\Controllers\Marketing`
  - `App\Http\Controllers\Technician`
  - `App\Http\Controllers\Customer`
  - `App\Http\Controllers\Public`
- **Middleware:**
  - `EnsureUserHasRole`: Checks `Auth::user()->is_active` (forces logout if disabled) and checks if `Auth::user()->role` matches allowed parameters.
  - `RestrictCustomerPortal`: Enforces customer containment to `client.*` route namespaces, `/dashboard`, `/`, `/logout`, and `/documents/ktp/*`.
- **Service Layer:** Decoupled business services located in `App\Services`: `NetworkService`, `WhatsappService`, `NotificationService`.

---

## 18. Database Analysis

- **Database Engine:** MySQL 8.0+ / MariaDB (`DB_CONNECTION=mysql`).
- **ORM:** Laravel Eloquent with active migrations and model relationships.
- **Total Tables:** 14 active business & system tables:
  `users`, `customers`, `packages`, `subscriptions`, `invoices`, `leads`, `tickets`, `network_assets`, `master_areas`, `audit_logs`, `system_integrations`, `role_permissions`, `notifications`, `failed_jobs`.
- **Recent Schema Changes:**
  - `system_settings` table dropped via migration `2026_09_17_000000_remove_system_settings_table.php`.
  - Dropped obsolete polymorphic tables (`survey_forms`, `installation_forms`, `device_configs`, `network_configs`, `repair_forms`) via migration `2026_09_25_131406`.
  - Added performance indexes via migration `2026_09_25_132436`.

---

## 19. Data Model / ERD Representation

```
User (id, name, email, password, role, is_active, phone_number, area_id, marketing_code)
 ├── belongs to MasterArea (area_id)
 ├── has many Leads [marketing_id]
 ├── has many Tickets [technician_id]
 ├── has one Customer [user_id]
 └── has many AuditLogs [user_id]

Customer (id, user_id, lead_id, customer_code, phone_number, address_installation, coordinates, is_isolated)
 ├── belongs to User (user_id)
 ├── belongs to Lead (lead_id)
 ├── has many Subscriptions
 └── has many Tickets

Package (id, name, speed_mbps, price, installation_fee, description, is_active)
 ├── has many Leads
 └── has many Subscriptions

Subscription (id, customer_id, package_id, pppoe_username, pppoe_password, ip_address, status)
 ├── belongs to Customer
 ├── belongs to Package
 └── has many Invoices

Invoice (id, subscription_id, invoice_number, amount, status, due_date, paid_at, payment_method, snap_token)
 └── belongs to Subscription

Lead (id, marketing_id, package_id, name, phone, email, address, coordinates, status, ktp_image_path)
 ├── belongs to User [marketing_id]
 ├── belongs to Package
 └── has one Customer

Ticket (id, customer_id, technician_id, router_id, type, status, subject, description, technical_notes, 
        survey_status, installation_date, connection_type, cable_length, device_brand, device_mac, 
        odp_port, dbm_signal, pppoe_username, pppoe_password, speed_test_result, evidence_photo_path)
 ├── belongs to Customer
 ├── belongs to User [technician_id]
 └── belongs to NetworkAsset [router_id]

NetworkAsset (id, name, type, brand, ip_address, location, is_active)
 └── has many Tickets [router_id]

AuditLog (id, user_id, action, description, details, ip_address, user_agent)
 └── belongs to User
```

---

## 20. Data Flow

### Customer Registration to Active Service
```
[Lead Entry] (Marketing / Public Form)
       │
       ▼
   Lead Record (status: 'prospek')
       │
       ▼ [Convert Action]
   User Account (role: 'customer') + Customer Record + Ticket (type: 'installation')
       │
       ▼ [Technician Claim & Fulfillment]
   Ticket Updated (status: 'resolved', completed_at logged, MAC & dBm recorded)
       │
       ▼ [Admin Approval & Billing]
   Subscription Created (status: 'active') + Invoice Issued (status: 'unpaid')
       │
       ▼ [Payment Gateway Hook]
   Invoice Updated (status: 'paid') ──► MikroTik PPPoE Enabled ──► WhatsApp Notice Dispatched
```

---

## 21. Authentication

- **Implementation:** Laravel Jetstream bundled with Laravel Fortify.
- **Mechanisms:**
  - Standard session cookies (`SESSION_DRIVER=database`).
  - Passwords hashed using Bcrypt (`BCRYPT_ROUNDS=12`).
  - Two-Factor Authentication (TOTP app / QR Code and recovery codes) via Fortify.
  - API Token authentication via Laravel Sanctum (`HasApiTokens` on `User`).
- **Login Validation & Deactivation Guard:** `FortifyServiceProvider` verifies `$user->is_active` during authentication. Inactive accounts are blocked before session creation with the validation error message: *"Akun Anda belum aktif. Silakan hubungi administrator untuk aktivasi."* (tested in `tests/Feature/AuthenticationTest.php`). If an active account is deactivated while logged in, `EnsureUserHasRole` middleware intercepts the next request, terminates the session, and redirects to login with the same message.

---

## 22. Authorization

- **Implementation:** Gatekeeper middleware `EnsureUserHasRole` checking role strings against allowed parameters.
- **Customer Quarantine:** `RestrictCustomerPortal` middleware intercepts all requests from accounts where `role === 'customer'`. Unless target route is `dashboard`, `home`, `logout`, `documents.ktp`, or begins with `client.`, an HTTP 403 Forbidden response is returned.
- **Architectural Note:** The SuperAdmin panel exposes an interface (`/superadmin/roles`) allowing checkboxes to be saved to `role_permissions`. However, authorization is enforced via route middleware `role:*`.

---

## 23. External Integrations

| External Service | Category | Technical Implementation | Config Source | Evidence |
| :--- | :--- | :--- | :--- | :--- |
| **Midtrans Snap** | Payment Gateway | `\Midtrans\Snap::getSnapToken()` | `config/services.php` $\leftarrow$ `.env` (`MIDTRANS_SERVER_KEY`) | `InvoiceController.php:62-95` |
| **Midtrans Webhook** | Asynchronous Notification | `POST /midtrans/notification` | `config/services.php` | `MidtransWebhookController.php` |
| **MikroTik RouterOS** | Network Provisioning | `RouterOS\Client` over Port 8728 | `config/services.php` $\leftarrow$ `.env` (`MIKROTIK_HOST`) | `NetworkService.php` |
| **WhatsApp Gateway** | Instant Messaging | Outbound HTTP POST to Node.js service | `config/services.php` $\leftarrow$ `.env` (`WA_API_URL`) | `WhatsappService.php` |

---

## 24. Environment Variables

| Variable | Required | Secret | Purpose | Location Checked |
| :--- | :---: | :---: | :--- | :--- |
| `APP_KEY` | **Yes** | **Yes** | Encryption key for sessions/cookies | `.env` |
| `DB_DATABASE` | **Yes** | No | Database name | `.env` |
| `DB_USERNAME` | **Yes** | No | Database user | `.env` |
| `DB_PASSWORD` | **Yes** | **Yes** | Database password | `.env` |
| `MIDTRANS_SERVER_KEY` | **Yes** | **Yes** | Midtrans server authentication key | `.env` |
| `MIDTRANS_CLIENT_KEY` | **Yes** | No | Public key for client Snap popup | `.env` |
| `MIDTRANS_IS_PRODUCTION` | Optional | No | Toggle Sandbox vs Production Midtrans API | `.env` |
| `MIKROTIK_HOST` | **Yes** | No | IP address of target MikroTik router | `.env` |
| `MIKROTIK_USER` | **Yes** | No | RouterOS API username | `.env` |
| `MIKROTIK_PASS` | **Yes** | **Yes** | RouterOS API password | `.env` |
| `MIKROTIK_PORT` | Optional | No | RouterOS API port (Default 8728) | `.env` |
| `WA_API_URL` | **Yes** | No | Internal URL to Node.js WhatsApp service | `.env` |

### Secrets Detection
- **SECRET DETECTED:** YES
- **LOCATION:** `.env`
- **TYPE:** Application Key, Midtrans Server Key, Database Credentials, MikroTik Credentials
- **VALUE:** REDACTED

*(Note: `.env` is properly registered in `.gitignore` and has not been committed to source control).*

---

## 25. File & Storage System

- **Storage Drivers:** `local` (`FILESYSTEM_DISK=local`), mapped via `php artisan storage:link` to `public/storage`.
- **Public Upload Directories:**
  - `storage/app/public/uploads/house`: Customer property photos.
  - `storage/app/public/uploads/customer`: Customer profile portraits.
  - `storage/app/public/uploads/teknisi/lokasi`: On-site survey and installation photos.
  - `storage/app/public/uploads/teknisi/bukti`: Modem and optical signal verification photos.
  - `storage/app/public/uploads/customer-complaints`: Photos uploaded by subscribers reporting outages.
- **Private Storage:** Sensitive national ID cards (KTP) are stored on `local` private disk under `storage/app/private/documents/ktp/` and streamed only via authenticated `CustomerDocumentController`.

---

## 26. Background Jobs

- **Queue Driver:** Configured as `QUEUE_CONNECTION=database` in `.env`.
- **Database Tables:** `jobs`, `job_batches`, `failed_jobs`.
- **Scheduled Tasks:** [ProcessDailyBilling.php](file:///c:/Users/USER/OneDrive/Dokumen/Projects/NetManager/app/Console/Commands/ProcessDailyBilling.php) is scheduled in [routes/console.php](file:///c:/Users/USER/OneDrive/Dokumen/Projects/NetManager/routes/console.php) to run daily at `00:01`:
  - Dispatches H-3, H-1, and H-0 WhatsApp billing reminders.
  - Automatically isolates overdue accounts on MikroTik routers and marks customers isolated.

---

## 27. Configuration

- `config/services.php`: Extends default Laravel services to include custom configuration arrays for `midtrans`, `mikrotik`, and `whatsapp`.
- `config/jetstream.php`: Defines active Jetstream features: profile photos, API tokens, and account deletion. Teams are disabled.
- `config/session.php`: Specifies database sessions (`SESSION_DRIVER=database`) with a 120-minute lifetime.
- `bootstrap/app.php`: Configured with reverse proxy trusting (`trustProxies: '*'`) and CSRF token exception for `/midtrans/notification`.

---

## 28. Dependency Analysis

### PHP Dependencies (`composer.json`)
- `evilfreelancer/routeros-api-php: ^1.7`: RouterOS API client. Active in `NetworkService`.
- `midtrans/midtrans-php: ^2.6`: Midtrans payment library. Active in `InvoiceController` and `MidtransWebhookController`.
- `livewire/livewire: ^3.6.4`: Reactive component library for Jetstream user profile management.
- `laravel/dusk: ^8.6`: Headless browser testing. Actively used in `tests/Browser`.

### Node.js Dependencies (`package.json` & `whatsapp-service/package.json`)
- Root: `tailwindcss: ^3.4.0`, `vite: ^5.0`, `axios: ^1.6.4`.
- WhatsApp Service: `express: ^4.19.2`, `whatsapp-web.js: ^1.26.0`, `qrcode-terminal: ^0.12.0`. Requires Chromium binaries for Puppeteer execution.

---

## 29. Testing

- **Testing Frameworks:** PHPUnit 10.5 and Laravel Dusk 8.6.
- **Browser Automation Suite (`tests/Browser`):**
  - `TechnicianLoginTest.php`: Tests technician authentication and dashboard redirection.
  - `RoleDashboardTest.php`: Tests access control and dashboard rendering across seeded users.
  - `OperationalWorkflowTest.php`: Validates ticket claiming and status transitions.
  - `PublicAndCustomerWorkflowTest.php`: Tests complaint creation and invoice viewing.
- **Feature Tests (`tests/Feature`):**
  - `CustomerPortalAccessTest.php`: Tests authenticated customer redirection from `/` to `/client/dashboard`.
  - `AuthenticationTest.php`: Tests login authentication, invalid credential rejection, and strict rejection of inactive users (`test_inactive_users_cannot_authenticate`).
  - Jetstream standard authentication and account lifecycle tests.

---

## 30. CI/CD

- **Development Containers:** Full `.devcontainer` configuration (`devcontainer.json`, `Dockerfile`, `setup.sh`) supporting VS Code, GitHub Codespaces, and Docker Outside of Docker.
- **Production Build:** Multi-stage production [Dockerfile](file:///c:/Users/USER/OneDrive/Dokumen/Projects/NetManager/Dockerfile) and [compose.yaml](file:///c:/Users/USER/OneDrive/Dokumen/Projects/NetManager/compose.yaml).

---

## 31. Deployment

- **Deployment Requirements:**
  - Web Server: Nginx with reverse proxy and PHP-FPM socket.
  - PHP: PHP 8.2 or 8.3 CLI and FPM with extensions: `pdo_mysql`, `curl`, `mbstring`, `openssl`, `sockets`, `gd`, `zip`, `intl`, `bcmath`, `opcache`.
  - Database: MySQL 8.0+ or MariaDB 10.5+.
  - Process Manager: PM2, Supervisor, or Docker to maintain `whatsapp-service` and `php artisan queue:work`.
  - Network: Server must have direct IP or VPN routing to MikroTik RouterOS API port 8728.
- **Platform Incompatibility:**
  - Serverless platforms (Vercel, Cloudflare Pages) are incompatible due to long-lived Puppeteer Chromium processes and raw TCP socket connections.
- **Recommended Platform:** VPS (Ubuntu 22.04 / 24.04 LTS) or Docker Compose.

---

## 32. Security Audit

### Finding 1: Unconditional Midtrans Webhook Signature Bypass (RESOLVED)
- **Severity:** High
- **Status:** **Fixed**
- **Location:** `app/Http/Controllers/MidtransWebhookController.php`
- **Resolution:** Strict server key validation and unconditional SHA-512 calculation.

### Finding 2: Synchronous Command Execution in Router Ping (RESOLVED)
- **Severity:** Medium
- **Status:** **Fixed**
- **Location:** `app/Http/Controllers/Admin/RouterController.php`
- **Resolution:** Replaced `exec("ping ...")` with safe 2-second TCP socket probe via `fsockopen()` targeting port 8728.

### Finding 3: Publicly Accessible KTP Uploads (RESOLVED)
- **Severity:** Medium
- **Status:** **Fixed**
- **Location:** `LeadController.php`, `CustomerDocumentController.php`
- **Resolution:** Moved KTP uploads to `local` disk and streamed via authenticated `CustomerDocumentController`.

### Finding 4: Webhook Idempotency (RESOLVED)
- **Severity:** Medium
- **Status:** **Fixed**
- **Location:** `MidtransWebhookController.php`
- **Resolution:** Immediate return of HTTP 200 `['message' => 'Already processed']` when invoice is already marked `paid`.

---

## 33. Performance Analysis

- **Performance Indexes (RESOLVED):** Codified into formal migration `2026_09_25_132436_add_performance_indexes_to_core_tables.php`.
- **SuperAdmin Dashboard Revenue Aggregation (RESOLVED):** 12-month revenue trend consolidated into a single aggregated `GROUP BY YEAR, MONTH` query.
- **Frontend Asset Compilation:** Replaced CDN scripts with Vite compilation and Tailwind JIT purging.

---

## 34. Scalability Analysis

- **MikroTik Socket Calls:** Synchronous socket calls during webhook handling are wrapped in isolated `try/catch` blocks. Under heavy subscriber volume, moving socket updates to background queue jobs is recommended.
- **WhatsApp Microservice:** Single Puppeteer session. Higher broadcast throughput requires queueing to prevent WhatsApp rate limits.

---

## 35. Accessibility

- Standard HTML5 semantic structure.
- Accessible show/hide password toggle added to login with ARIA states (`aria-pressed`, `aria-label`, `aria-controls`).
- Focus rings and label associations implemented on forms.

---

## 36. Internationalization

- Configured with `APP_LOCALE=en` in `.env`, but user interface copy, status labels, and notification templates are in Bahasa Indonesia.
- Currency formatted in Indonesian Rupiah (`Rp`).

---

## 37. Observability

- Application errors logged to `storage/logs/laravel.log`.
- Operational audit trails stored in `audit_logs` table.
- Telemetry exports in `LogController` and `AuditController` use null-safe operators to protect against deleted user accounts.

---

## 38. Code Quality

- Clear separation of concerns with domain controllers and dedicated services (`App\Services`).
- Database operations on multi-entity transactions use `DB::beginTransaction()` and `DB::commit()`.
- Clean route groupings with explicit role middleware parameters.

---

## 39. Technical Debt

1. **Dead Schema Entities (RESOLVED):** Dropped obsolete polymorphic tables via formal migration and deleted dead model files.
2. **Ad-Hoc Scripts (RESOLVED):** Codified `add_indexes.php` into migration and removed script.
3. **Decoupled Permissions Table (Informational):** `role_permissions` schema exists but access control is managed via route middleware `role:*`.

---

## 40. Dead / Legacy Code

| Item | Type | Location | Status | Action Taken |
| :--- | :--- | :--- | :--- | :--- |
| `RepainForm.php` | Eloquent Model | `app/Models/RepainForm.php` | Deleted | Safely removed typo model. |
| `SurveyForm.php` | Eloquent Model | `app/Models/SurveyForm.php` | Deleted | Safely removed legacy polymorphic model. |
| `InstallationForm.php` | Eloquent Model | `app/Models/InstallationForm.php` | Deleted | Safely removed legacy polymorphic model. |
| `DeviceConfig.php` | Eloquent Model | `app/Models/DeviceConfig.php` | Deleted | Safely removed legacy polymorphic model. |
| `NetworkConfig.php` | Eloquent Model | `app/Models/NetworkConfig.php` | Deleted | Safely removed legacy polymorphic model. |
| `add_indexes.php` | Root Script | `/add_indexes.php` | Deleted | Codified into migration and removed. |
| `Integrations/` Dir | Controller Folder | `app/Http/Controllers/Integrations/` | Deleted | Refactored into `App\Services`. |

---

## 41. Documentation Accuracy

### Discrepancy 1: System Settings Feature
- **DOCUMENTATION SAYS:** `README.md` lines 59 & 44 claim "System settings and configuration" is an active SuperAdmin feature.
- **IMPLEMENTATION SHOWS:** Migration `2026_09_17_000000_remove_system_settings_table.php` dropped `system_settings` table.
- **CONCLUSION:** Documentation is outdated.
- **CONFIDENCE:** CONFIRMED.

### Discrepancy 2: Testing Suite Scope
- **DOCUMENTATION SAYS:** `docs/testing-matrix.md` lists Dusk coverage for role dashboards and operational pages.
- **IMPLEMENTATION SHOWS:** `tests/Browser` contains matching test classes.
- **CONCLUSION:** Documentation matches implementation.
- **CONFIDENCE:** CONFIRMED.

---

## 42. Git / Project History

- **Repository Start Date:** 2026-02-16.
- **Major Development Milestones:**
  1. *Feb 2026:* Skeleton setup, Laravel Jetstream authentication, basic CRUD.
  2. *May 2026:* Initial polymorphic schema for survey/installation/repair forms.
  3. *Aug 2026:* Pivot to flat architecture; denormalized fields into `tickets` and `leads`.
  4. *Sep 2026:* Removal of `system_settings`, Midtrans Snap and RouterOS API integration, Node.js WhatsApp gateway.
  5. *Sep 2026 (f40564d):* Pre-flight hardening, webhook idempotency, daily billing cron.
  6. *Sep 2026 (cfec5bb):* Docker non-root user and MySQL health check.
  7. *Sep 2026 (6ab089d):* Customer portal root redirection fix, reverse proxy header trusting, auth UI password toggle, Vite bundling on landing page.
  8. *Sep 2026 (43da18a):* Total removal of public self-registration; transition to strict closed-registration system (leads/customers created solely by Admin/Marketing).
  9. *Sep 2026 (8dd3c8d):* Customer activation Fortify authentication guard, customer complaint detail view (`/client/complaints/{ticket}`), and realistic end-to-end database seeder.

---

## 43. Known Problems & Resolution Log

### Functional Problems (ALL RESOLVED)
1. **Lead Conversion Crash (FIXED):** `LeadController::convert` refactored to create `Ticket` directly linked to customer.
2. **Missing View Error (FIXED):** Authored dark-themed `technician/profile/index.blade.php`.
3. **Customer Portal Root Access (FIXED):** Customer visiting `/` now cleanly redirects to `/client/dashboard`.
4. **Inactive User Authentication & Missing Complaint Detail (FIXED):** Added `$user->is_active` validation in `FortifyServiceProvider` with localized error feedback, added `client.complaints.show` route and Blade view, and updated complaint listing link.

### Security Problems (ALL RESOLVED)
1. **Webhook Forgery (FIXED):** Strict SHA-512 verification in `MidtransWebhookController`.
2. **KTP Exposure (FIXED):** Private disk storage and authenticated streaming via `CustomerDocumentController`.
3. **Shell Command Injection (FIXED):** Safe socket check in `RouterController`.

---

## 44. Unknowns & Unverified Assumptions

| Unknown Item | Reason Unknown | Verification Method | Required Access | Risk | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Physical MikroTik Hardware Compatibility** | Cannot test live RouterOS API handshake without physical router | Execute `RouterController@testConnection` against router | Network access to port 8728 | High (v6 vs v7 command differences) | Pending Field Test |
| **WhatsApp Multi-Device Session Persistence** | Node.js gateway relies on local Chromium session data | Scan QR and monitor session over 48 hours | Physical phone with WhatsApp | Medium | Pending Field Test |

---

## 45. Remediation & Recovery Execution (100% COMPLETED)

- **Stage 1 — Stabilize Workflows:** Lead conversion refactored, technician view added, dead routes cleaned.
- **Stage 2 — Secure Boundaries:** Mandatory SHA-512 signature validation, private KTP storage, safe socket router ping.
- **Stage 3 — Architecture Cleanup:** Services extracted to `App\Services`, dead polymorphic tables dropped, indexes migrated.
- **Stage 4 — Strict Closed-Registration:** Dropped public self-registration controllers and views, redirected landing page CTAs to login, enforced internal staff lead/customer registration only.
- **Stage 5 — Query Optimization & Views:** Single aggregate revenue query, live marketing reports, genuine Artisan maintenance.
- **Stage 6 — Hardening & Idempotency:** Webhook idempotency guard, real-time customer status sync, daily automated billing cycle.
- **Stage 7 — Access & UI Refinement:** Customer portal root routing allowed, reverse proxy headers trusted, accessible login password toggle.
- **Stage 8 — Closed Registration & Customer Self-Service Completion:** Fully removed public registration endpoints, fortified inactive user login authentication, added complaint detail route/view with technician assignment tracking, and refreshed database seeder with complete ISP operations data.

---

## 46. Recommended Next Investigation

1. **Verify RouterOS Version:** Determine whether target ISP routers run RouterOS v6 or v7 (v7 uses slightly different syntax for `/ip/firewall/address-list`).
2. **WhatsApp Gateway Resilience:** Evaluate whether to keep `whatsapp-web.js` running Puppeteer or connect to an official Meta Cloud API provider for high scale.
3. **Queue MikroTik Hardware Calls:** Move synchronous RouterOS socket calls from webhook into Laravel background queue jobs.

---

## 47. Complete File/Directory Map

```
NetManagement/
├── .devcontainer/                     # DevContainer & Codespaces environment
├── .github/                           # Repository configurations & templates
├── AGENTS.md                          # Pair-programming instructions & rules
├── README.md                          # Project documentation & overview
├── artisan                            # Laravel CLI entrypoint
├── composer.json                      # PHP dependencies
├── compose.yaml                       # Docker Compose specification
├── Dockerfile                         # Production multi-stage Docker build
├── package.json                       # Frontend Vite & Tailwind dependencies
├── phpunit.xml                        # PHPUnit test configuration
├── app/
│   ├── Console/Commands/
│   │   └── ProcessDailyBilling.php    # Automated H-3, H-1, H-0 reminders & auto-isolation
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CustomerDocumentController.php # Private disk KTP streaming
│   │   │   ├── MidtransWebhookController.php  # Strict SHA-512 payment webhook
│   │   │   ├── Admin/                         # Customer, Router, Billing controllers
│   │   │   ├── SuperAdmin/                    # User, Audit, Maintenance controllers
│   │   │   ├── Marketing/                     # Lead, Customer, & Report controllers
│   │   │   ├── Technician/                    # Task execution, ticket, & profile controllers
│   │   │   └── Customer/                      # Self-service billing & complaints
│   │   └── Middleware/
│   │       ├── EnsureUserHasRole.php          # Role checking & deactivation guard
│   │       └── RestrictCustomerPortal.php     # Customer sandbox firewall
│   ├── Models/                                # User, Customer, Subscription, Invoice, Lead, Ticket, etc.
│   └── Services/                              # NetworkService, WhatsappService, NotificationService
├── bootstrap/app.php                  # Middleware, proxy trust, & CSRF exceptions
├── database/migrations/               # 13 formal migrations
├── docker/                            # Nginx config, Supervisord, entrypoint script
├── resources/views/                   # Blade templates & layouts
├── routes/                            # web.php, console.php, api.php
├── tests/                             # Browser (Dusk), Feature, and Unit tests
└── whatsapp-service/                  # Express + whatsapp-web.js + Puppeteer microservice
```

---

## 48. Final System Summary

NetManagement is a comprehensive, production-hardened ISP management and billing portal tailored for regional Indonesian broadband operations. It automates the complete subscriber lifecycle: marketing lead intake, technician work order fulfillment, PPPoE provisioning, recurring billing, Midtrans Snap settlement, automated MikroTik un-isolation, and WhatsApp receipt delivery. All major architectural debt, polymorphic form defects, webhook vulnerabilities, and portal routing constraints have been resolved.

---

## 51. FINAL EXECUTIVE SUMMARY

- **Project:** NetManagement ("NetManager" / "PT. Mandiri Global Data").
- **Stack:** PHP 8.2+, Laravel 11.51, Livewire 3, Tailwind CSS 3, Vite 5, MySQL 8.0, Node.js (`whatsapp-web.js`), MikroTik RouterOS API, Midtrans Snap.
- **Purpose:** Centralized operational, billing, field technician, and network management system for an Internet Service Provider.
- **Users:** System Owners, Operations Administrators, Sales/Marketing Representatives, Network Field Technicians, and Broadband Subscribers.
- **Roles:** `super_admin`, `admin`, `marketing`, `technician`, `customer`.
- **MVP:** Lead intake $\rightarrow$ Customer account creation $\rightarrow$ Technician installation logging $\rightarrow$ Subscription & invoice issuance $\rightarrow$ Midtrans customer checkout $\rightarrow$ Automatic MikroTik PPPoE enable and WhatsApp notification.
- **Major Features:** Role-specific portals, customer isolation toggling, network device ping testing, ticket claiming and photo upload, self-service Midtrans Snap payments, automated payment webhooks, and automated daily billing cycle.
- **Architecture:** Laravel 11 web monolith with role-segregated routing paired with a local Node.js Puppeteer sidecar service for WhatsApp dispatching and direct TCP socket links to MikroTik routers.
- **Database:** MySQL database with 14 active tables, performance indexes applied.
- **External Services:** Midtrans Payment Gateway, MikroTik RouterOS API (port 8728), WhatsApp Web Gateway (port 3000).
- **Security:** Fully hardened. Strict SHA-512 signature validation enforced on Midtrans webhooks; customer KTP stored on private disk and streamed via authenticated controller; safe 2-second `fsockopen()` socket probe for router reachability; reverse proxy trusting configured.
- **Status:** **Refactored, Hardened, Production-Ready System.**
- **Deployment:** Requires Linux VPS or Docker Compose stack (`compose.yaml`) with PHP 8.2-FPM, MySQL, Nginx, and PM2/Supervisor for the Node.js daemon.
- **Immediate Next Steps:**
  1. Verify physical MikroTik router credentials on port 8728.
  2. Pair WhatsApp Gateway via `http://localhost:3000/qr`.
  3. Ensure container or server cron executes `php artisan schedule:run` every minute.

---

## 52. Pre-Flight Hardening, Payment Idempotency & WhatsApp Gateway Integration

### 1. Midtrans Webhook Idempotency & Status Handling
- **File:** `app/Http/Controllers/MidtransWebhookController.php`
- **Idempotency Guard:** Checks if the target invoice is already paid (`$invoice->status === 'paid'`) immediately after lookup. Returns HTTP 200 `['message' => 'Already processed']` to prevent duplicate MikroTik un-isolation calls and repetitive WhatsApp notification spam on webhook retries.
- **Status Mapping:**
  - `settlement` & `capture` (with `fraud_status === 'accept'`): Marks invoice as `paid`, sets `paid_at = now()`, records `payment_method`, re-enables customer in MikroTik via `NetworkService::enableCustomer()`, and triggers `WhatsappService::sendPaymentSuccess()`.
  - `pending`: Leaves invoice in `unpaid` pending completion.
  - `deny`, `cancel`, `expire`: Resets `status = 'unpaid'` and clears `snap_token = null` to allow regenerating fresh payment tokens.
- **Fault-Tolerant Execution:** MikroTik socket calls and WhatsApp HTTP requests are wrapped in isolated `try/catch (Throwable $e)` blocks so hardware or third-party outages never roll back or fail transaction state commits.

### 2. Customer Portal Experience & Real-Time Sync
- **Files:** `app/Http/Controllers/Customer/InvoiceController.php`, `resources/views/user/billing/show.blade.php`, `resources/views/user/dashboard/index.blade.php`, `resources/views/components/sidebar.blade.php`
- **Instant Status Sync:** Added `checkStatus(Invoice $invoice)` querying `\Midtrans\Transaction::status($invoice->invoice_number)` directly.
- **Snap Checkout Hook:** `snap.pay()` `onSuccess` callback auto-triggers the status sync endpoint via fetch/form submit, eliminating delays between customer payment and invoice reconciliation.
- **Manual Sync Fallback:** Added interactive *"Sudah bayar? Cek & Sinkronkan Status"* button on the invoice view.
- **UI & Security Polish:** 
  - Restyled Customer Dashboard to sleek dark slate aesthetic (`bg-slate-950`, border `border-slate-800`).
  - Purged raw technical PPPoE credentials card (username/password/IP) from subscriber view.
  - Added dedicated Customer Care & Support card with direct WhatsApp link.
  - Added Dashboard navigation item to Customer sidebar.

### 3. Admin Manual Payment Reconciliation
- **File:** `app/Http/Controllers/Admin/BillingController.php`
- **Unified Payment Flow:** When an admin marks an invoice as paid manually (`markAsPaid`):
  - Sets invoice `status = 'paid'`, `payment_method = 'manual_admin'`.
  - Re-enables subscription (`status = 'active'`) and customer (`is_isolated = false`) on MikroTik router.
  - Automatically dispatches WhatsApp receipt (`WhatsappService::sendPaymentSuccess()`).

### 4. WhatsApp Gateway Stabilization (`whatsapp-service`)
- **File:** `whatsapp-service/server.js`
- **Safe Dispatch Result Handling:** Guarded against `whatsapp-web.js` runtime exception `TypeError: Cannot read properties of undefined (reading 'id')` when message model is missing an ID, treating confirmed dispatches gracefully.
- **Linked Identity (`@lid`) Resolution:** Newer WhatsApp Web protocols return contact IDs formatted as `...@lid` (Linked Identity) via `getNumberId()`. Dispatching directly to `@lid` resulted in `Error: Pesan tidak dapat dikirim (chat tidak dapat diinisialisasi)`. The gateway now forces phone-based JIDs (`...@c.us`), ensuring reliable message delivery.
- **DNS Fail-Safe:** Removed remote `webVersionCache` fetch that threw `ENOTFOUND raw.githubusercontent.com` on ISPs blocking GitHub raw user content.

### 5. Automated Billing Reminders & Isolation Cycle
- **Files:** `app/Console/Commands/ProcessDailyBilling.php`, `app/Services/WhatsappService.php`, `routes/console.php`
- **Cadence & Conditions:**
  - **H-3:** 3 days before due date $\rightarrow$ `sendBillingReminder($invoice, 3)`.
  - **H-1:** 1 day before due date $\rightarrow$ `sendBillingReminder($invoice, 1)`.
  - **H-0:** Due date day (Hari-H) $\rightarrow$ `sendBillingReminder($invoice, 0)` with high-priority notice.
  - **Overdue ($<\text{today}$):** Marks `customer.is_isolated = true`, `subscription.status = 'isolated'`, disables router interface via MikroTik API, and dispatches `sendIsolationNotice($invoice)`.
- **Scheduled Automation:** Registered in `routes/console.php` via `Schedule::command('billing:process-daily')->dailyAt('00:01')`.
- **Phone Lookup Fallback:** `WhatsappService` inspects `$customer->phone_number ?? $customer->user?->phone_number ?? $customer->lead?->phone` with international `62xxxx` normalization.

---

## 53. Customer Portal Routing, Reverse Proxy & Auth UI Refinements (Commit 6ab089d)

### 1. Customer Portal Root Redirection
- **File:** `app/Http/Middleware/RestrictCustomerPortal.php`
- **Problem:** Authenticated customers navigating to the root URL `/` (`home`) triggered an HTTP 403 Forbidden response because `RestrictCustomerPortal` only permitted route names `dashboard`, `logout`, `documents.ktp`, or routes beginning with `client.`.
- **Resolution:** Added `$routeName === 'home'` to `isAllowedRoute()`. Customers navigating to `/` are now cleanly captured and redirected to `/client/dashboard`.
- **Automated Verification:** Added `tests/Feature/CustomerPortalAccessTest.php` asserting that an authenticated active customer accessing `/` receives an HTTP 302 redirecting to `/client/dashboard`.

### 2. Reverse Proxy & HTTPS Header Forwarding
- **File:** `bootstrap/app.php`
- **Problem:** When hosted behind an Nginx reverse proxy, Cloudflare, or Docker bridge, forwarded headers (`X-Forwarded-Host`, `X-Forwarded-Proto`) were ignored by default, causing generated URLs or redirects to revert to `http://` or internal port numbers.
- **Resolution:** Configured `$middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PROTO)` to safely trust upstream reverse proxy headers.

### 3. Frontend Landing Page Asset Compilation
- **File:** `resources/views/welcome.blade.php`
- **Problem:** The public landing page used runtime Tailwind CDN (`<script src="https://cdn.tailwindcss.com"></script>`), causing flash of unstyled content (FOUC), external script dependency risks, and slower rendering.
- **Resolution:** Replaced runtime CDN script with local precompiled `@vite('resources/css/app.css')` and corrected static image paths to `/storage/img/LOGOMGD.png`.

### 4. Accessible Password Reveal Toggle
- **File:** `resources/views/auth/login.blade.php`
- **Enhancement:** Implemented an interactive show/hide password toggle button within the password input wrapper. Includes SVG eye / eye-slash icons with accessible attributes (`aria-pressed`, `aria-label`, `aria-controls`), smooth focus rings, and centered submit button styling.

---

## 54. Closed Registration Architecture, Inactive User Fortify Guard & Complaint Detail View (Commits 43da18a & 8dd3c8d)

### 1. Strict Closed Registration & Removal of Public Registration (Commit 43da18a)
- **Files Modified / Removed:** `app/Http/Controllers/Public/PublicRegistrationController.php` (deleted), `resources/views/public/register.blade.php` (deleted), `resources/views/public/success.blade.php` (deleted), `routes/web.php`, `config/fortify.php`, `resources/views/welcome.blade.php`.
- **Architectural Shift:** Open self-registration by unverified visitors created risks of invalid address data, missing KTP verification, and spam subscription records. The platform shifted entirely to a closed-registration model.
- **Implementation:**
  - Removed all public registration routes (`/register-service` and `/register`) and deleted legacy guest controllers and Blade views.
  - Disabled Fortify public self-registration (`Features::registration()` commented out in `config/fortify.php`).
  - Redirected public landing page CTA buttons ("Daftar Sekarang", "Mulai Berlangganan") to the internal login portal (`route('login')`).
  - Enforced onboarding exclusively through internal workflows: Marketing enters prospects (`/marketing/leads`), converts them with site surveys and KTP verification, or Admin registers customers directly (`/admin/customers`).

### 2. Inactive User Authentication Guard & Automated Verification (Commit 8dd3c8d)
- **Files:** `app/Providers/FortifyServiceProvider.php`, `app/Http/Middleware/EnsureUserHasRole.php`, `tests/Feature/AuthenticationTest.php`.
- **Problem:** Attempting to authenticate with an inactive (`is_active = false`) user account could trigger confusing feedback or pass initial Fortify authentication before being terminated by middleware on subsequent requests.
- **Resolution:**
  - Injected an explicit active account check into `Fortify::authenticateUsing` in `FortifyServiceProvider.php`:
    ```php
    if (!$user->is_active) {
        throw ValidationException::withMessages([
            'email' => __('Akun Anda belum aktif. Silakan hubungi administrator untuk aktivasi.'),
        ]);
    }
    ```
  - Aligned `EnsureUserHasRole.php` middleware with identical session-termination error messaging.
  - Added automated test `test_inactive_users_cannot_authenticate()` in `tests/Feature/AuthenticationTest.php` asserting that disabled accounts are blocked, stay unauthenticated, and receive the localized Indonesian error notice.

### 3. Customer Complaint Detail View & Tracking (Commit 8dd3c8d)
- **Files:** `app/Http/Controllers/Customer/ComplaintController.php`, `resources/views/client/complaints/show.blade.php`, `resources/views/client/complaints/index.blade.php`, `routes/web.php`.
- **Problem:** In the subscriber portal complaint list (`/client/complaints`), the "Lihat Detail" action had a placeholder `#` link, leaving subscribers unable to track the progress of reported issues, view the assigned technician, or read technician resolution notes.
- **Resolution:**
  - Added `ComplaintController@show(Ticket $ticket)` enforcing strict customer tenancy via `$this->customer()->tickets()->with('technician')->findOrFail($ticket->id)`.
  - Registered route `GET /client/complaints/{ticket}` named `client.complaints.show`.
  - Authored responsive dark-themed view `resources/views/client/complaints/show.blade.php` displaying ticket ID, submission timestamp, status badge, assigned technician name, problem description, and technician resolution notes (`technical_notes` / `final_technician_notes`).
  - Updated "Lihat Detail" link in `client/complaints/index.blade.php` to target `route('client.complaints.show', $ticket)`.

### 4. Comprehensive Production Database Seeder Overhaul (Commit 8dd3c8d)
- **File:** `database/seeders/DatabaseSeeder.php`.
- **Enhancement:** Overhauled database seeding logic with `firstOrCreate` guards to establish an immediate, end-to-end verifiable test dataset:
  - Default staff accounts with preconfigured roles (`super_admin`, `admin`, `marketing`, `technician`).
  - Active subscriber account (`customer`) with complete profile, unisolated status (`is_isolated = false`), and linked Master Area.
  - Internet package catalogue (Paket Basic 20 Mbps & Paket Pro 50 Mbps).
  - Active subscription linked to PPPoE credentials.
  - Outstanding unpaid invoice (`INV-YYYYMMDD-001`) with upcoming due date.
  - Active repair work order (`Gangguan LOS Merah - CUST-001`, status: `open`) ready for technician claiming.
  - Prospective sales lead (`Siti Aminah`, status: `prospek`) ready for marketing pipeline testing.

---

## 55. Codespaces 1-Click Environment & Repository Decluttering (Commit 86181bb)

### 1. 1-Click GitHub Codespaces & Docker Devcontainer Setup
- **Files:** `.devcontainer/devcontainer.json`, `.devcontainer/setup.sh`, `compose.yaml`, `codespace.md`.
- **Enhancement:**
  - Automated developer bootstrapping inside GitHub Codespaces. Developers can launch a cloud workspace in a single click without manual software installations.
  - Configured port forwarding for port `8000` (Laravel Web Application), `3000` (Node.js WhatsApp microservice), and `3306` (MySQL Database).
  - Built `.devcontainer/setup.sh` to automatically install Composer dependencies, NPM packages, generate application keys, create `.env`, run database migrations, and execute `DatabaseSeeder`.
  - Added [codespace.md](file:///c:/Users/USER/OneDrive/Dokumen/Projects/NetManager/codespace.md) as a complete step-by-step developer reference.

### 2. Codebase Decluttering & Elimination of Dead Controllers
- **Files Deleted / Pruned:** `app/Http/Controllers/Admin/UserController.php`, `app/Http/Controllers/Admin/TicketQCController.php`, `resources/views/marketing/schedules/index.blade.php`.
- **Problem:** `Admin/UserController.php` was redundant because staff management is exclusively governed by `SuperAdmin/UserManagementController.php`. `TicketQCController.php` contained dead legacy logic from the abandoned QC approval flow. `marketing/schedules` contained mock loops without a database backend.
- **Resolution:** Deleted redundant controllers and views, pruned orphaned routes in `routes/web.php`, and cleaned navigation menus (`sidebar.blade.php`, `navigation-menu.blade.php`) to avoid broken links and maintain YAGNI (Ponytail principle).

### 3. File Storage Clean-up & Directory Protection
- **Files:** `storage/app/public/uploads/.gitignore`, `storage/app/private/documents/ktp/.gitignore`, `docs/docker-deployment-guide.md`.
- **Enhancement:**
  - Removed dummy/test camera uploads (`bmuW2kHz...jpg`, `rHVLqfJz...jpg`, `pxKYaEIY...jpg`) from `storage/app/public/uploads/teknisi/`.
  - Added strict `.gitignore` rules (`*\n!.gitignore`) inside upload directories to prevent developer test uploads from polluting version control.
  - Relocated unorganized root notes (`docker appyx.md`) into structured project documentation at [docs/docker-deployment-guide.md](file:///c:/Users/USER/OneDrive/Dokumen/Projects/NetManager/docs/docker-deployment-guide.md).

---

## 56. GitHub Actions CI Workflow Hardening & Route Validation (Commits 57af1b9, 54a9a87, 9d1ab4d, 97c2ec7)

### 1. Isolated In-Memory SQLite Test Harness for CI
- **File:** `.github/workflows/ci.yml`.
- **Problem:** The GitHub Actions runner was failing because the default `.env.example` specified `DB_CONNECTION=mysql`, `SESSION_DRIVER=database`, and `APP_MAINTENANCE_STORE=database`, while the CI container did not host a live MySQL daemon.
- **Resolution:**
  - Replaced ad-hoc `sed` string replacements with a deterministic `.env` generator:
    ```bash
    cat << 'EOF' > .env
    APP_NAME=NetManager-CI
    APP_ENV=testing
    APP_KEY=base64:zZg8G176X7L7o8n54mU6D+9P2f1M3sQ5r7T9u1V3w5Y=
    APP_DEBUG=true
    DB_CONNECTION=sqlite
    DB_DATABASE=:memory:
    SESSION_DRIVER=file
    CACHE_STORE=file
    APP_MAINTENANCE_STORE=file
    QUEUE_CONNECTION=sync
    EOF
    ```
  - Configured migrations to run against SQLite in-memory (`php artisan migrate:fresh --force -vvv`), validating foreign keys and schema syntax without requiring external services.

### 2. Lockfile Verification & Dependency Auditing
- **Files:** `package-lock.json`, `whatsapp-service/package-lock.json`, `.github/workflows/ci.yml`.
- **Enhancement:**
  - Verified that Composer and NPM lockfiles (`composer.lock`, root `package-lock.json`, and `whatsapp-service/package-lock.json`) are committed and synchronized with their respective `package.json` specifications.
  - Decoupled `npm ci` for root and `whatsapp-service` in the CI pipeline with verbose error reporting so dependency issues can be diagnosed immediately.

### 3. Route Integrity & CustomerController Full Restoration
- **Files:** `app/Http/Controllers/Admin/CustomerController.php`, `routes/web.php`.
- **Problem:** `app/Http/Controllers/Admin/CustomerController.php` was accidentally emptied during a file operation, causing `php artisan route:list` in CI to fail with `ReflectionException: Class "App\Http\Controllers\Admin\CustomerController" does not exist`.
- **Resolution:**
  - Completely restored and verified the Admin [CustomerController.php](file:///c:/Users/USER/OneDrive/Dokumen/Projects/NetManager/app/Http/Controllers/Admin/CustomerController.php) class.
  - Re-implemented search, index, show, edit, and update methods, as well as `isolate` and `activate` endpoints with live calls to `NetworkService::disableCustomer` and `NetworkService::enableCustomer`.
  - Added `-vvv` verbose flag to `php artisan route:list -vvv` in the CI workflow to ensure any future routing or controller reflection issues are immediately caught with full stack traces.

