import { expect, test as setup } from '@playwright/test';
import fs from 'node:fs';
import path from 'node:path';

const accounts = [
  { role: 'customer', email: process.env.E2E_CUSTOMER_EMAIL ?? 'budi@netmanager.local', path: 'playwright/.auth/customer.json', dashboard: /\/client\/dashboard$/, heading: /Halo, Budi Santoso/i },
  { role: 'superadmin', email: process.env.E2E_SUPERADMIN_EMAIL ?? 'owner@netmanager.local', path: 'playwright/.auth/superadmin.json', dashboard: /\/superadmin\/dashboard$/, heading: /Super Admin/i },
  { role: 'admin', email: process.env.E2E_ADMIN_EMAIL ?? 'admin@netmanager.local', path: 'playwright/.auth/admin.json', dashboard: /\/admin\/dashboard$/, heading: /Dashboard/i },
  { role: 'marketing', email: process.env.E2E_MARKETING_EMAIL ?? 'marketing@netmanager.local', path: 'playwright/.auth/marketing.json', dashboard: /\/marketing\/dashboard$/, heading: /Dashboard/i },
  { role: 'technician', email: process.env.E2E_TECHNICIAN_EMAIL ?? 'teknisi@netmanager.local', path: 'playwright/.auth/technician.json', dashboard: /\/technician\/dashboard$/, heading: /Selamat Bertugas/i },
] as const;

const testPassword = process.env.E2E_TEST_PASSWORD ?? 'password';

setup.describe('role authentication setup', () => {
  setup.describe.configure({ mode: 'serial' });

  setup.beforeEach(async ({ page }) => {
    await page.route('**/*cdn.jsdelivr.net/**', route => {
      const url = route.request().url();
      if (url.includes('sweetalert2')) {
        route.fulfill({
          status: 302,
          headers: { Location: 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js' }
        });
      } else {
        route.fulfill({
          status: 200,
          contentType: 'application/javascript',
          body: 'window.tsParticles={load:()=>{}};'
        });
      }
    });
  });

  for (const account of accounts) {
    setup(`authenticate as seeded ${account.role}`, async ({ page }) => {
      await page.goto('/login');
      await page.getByLabel('Email Address').fill(account.email);
      await page.getByLabel('Password', { exact: true }).fill(testPassword);
      await page.getByRole('button', { name: 'Sign In' }).click();

      await expect(page).toHaveURL(account.dashboard, { timeout: 15000 });
      await expect(page.getByRole('heading', { name: account.heading }).first()).toBeVisible({ timeout: 10000 });

      fs.mkdirSync(path.dirname(account.path), { recursive: true });
      await page.context().storageState({ path: account.path });
    });
  }
});
