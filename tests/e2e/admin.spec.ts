import { expect, test } from '@playwright/test';

test.use({ storageState: 'playwright/.auth/admin.json' });

test('admin can isolate a customer from the customer detail page', async ({ page }) => {
  await page.goto('/admin/customers');
  await expect(page.getByRole('heading', { name: 'Manajemen Pelanggan' })).toBeVisible();

  const customerRow = page.getByRole('row').filter({ hasText: 'Budi Santoso' }).first();
  await customerRow.getByRole('link', { name: 'Detail' }).click();
  await expect(page).toHaveURL(/\/admin\/customers\/\d+$/);
  await expect(page.getByRole('heading', { name: 'Budi Santoso' })).toBeVisible();

  await page.getByLabel('Alasan Isolir').fill('Pengujian E2E isolasi pelanggan.');
  await page.getByRole('button', { name: 'Isolir Pelanggan' }).click();
  await page.getByRole('button', { name: 'Ya, Isolir!' }).click();

  await expect(page).toHaveURL(/\/admin\/customers\/\d+$/);
  await expect(page.getByText('Pelanggan berhasil diisolir')).toBeVisible();
});

test('admin can mark an unpaid invoice as paid manually', async ({ page }) => {
  await page.goto('/admin/billing');
  await expect(page.getByRole('heading', { name: 'Manajemen Tagihan' })).toBeVisible();

  const invoiceRow = page.getByRole('row').filter({ hasText: 'INV-' }).filter({ hasText: 'Belum Bayar' }).first();
  await expect(invoiceRow).toBeVisible();
  await invoiceRow.getByRole('link', { name: 'Lihat' }).click();
  await expect(page).toHaveURL(/\/admin\/billing\/\d+$/);
  await page.getByRole('button', { name: 'Tandai Sebagai LUNAS' }).click();

  await expect(page).toHaveURL(/\/admin\/billing\/\d+$/);
  await expect(page.getByText(/Tagihan berhasil ditandai LUNAS/i)).toBeVisible();
  await expect(page.getByText('TAGIHAN TELAH LUNAS')).toBeVisible();
});
