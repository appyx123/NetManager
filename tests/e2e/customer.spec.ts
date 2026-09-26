import { expect, test } from '@playwright/test';

function uniqueComplaintTitle(): string {
  return `E2E complaint ${Date.now()}`;
}

test.describe('customer portal', () => {
  test('customer can review dashboard and billing', async ({ page }) => {
    await page.goto('/client/dashboard');
    await expect(page.getByRole('heading', { name: /Halo, Budi Santoso/i })).toBeVisible();
    await expect(page.getByText('ID Pelanggan:')).toBeVisible();

    await page.getByRole('link', { name: 'Pembayaran' }).click();
    await expect(page).toHaveURL(/\/client\/billing$/);
    await expect(page.getByRole('heading', { name: 'Tagihan & Pembayaran' })).toBeVisible();
    await expect(page.getByText('Belum Bayar', { exact: true }).first()).toBeVisible();
  });

  test('customer can submit a complaint and open its detail page', async ({ page }) => {
    const title = uniqueComplaintTitle();

    await page.goto('/client/complaints/create');
    await expect(page.getByRole('heading', { name: /Buat Pengajuan Bantuan Baru/i })).toBeVisible();
    await page.getByRole('radio', { name: /Internet Lambat \/ Putus/i }).check();
    await page.getByLabel('Judul Ringkasan').fill(title);
    await page.getByLabel('Tingkat Prioritas').selectOption('high');
    await page.getByLabel('Jelaskan Detail Permasalahannya').fill('Koneksi internet terputus dan perlu pemeriksaan teknisi.');
    await page.getByRole('button', { name: 'Kirim Pengajuan' }).click();

    await expect(page).toHaveURL(/\/client\/complaints$/);
    await expect(page.getByText('Laporan kerusakan berhasil dikirim.')).toBeVisible();
    await expect(page.getByText(title, { exact: true })).toBeVisible();

    await page.getByRole('link', { name: 'Lihat Detail' }).first().click();
    await expect(page).toHaveURL(/\/client\/complaints\/\d+$/);
    await expect(page.getByRole('heading', { name: /Detail Pengajuan/i })).toBeVisible();
    await expect(page.getByRole('heading', { name: title })).toBeVisible();
  });

  test('customer cannot access an admin page', async ({ page }) => {
    const response = await page.goto('/admin/dashboard');

    expect(response?.status()).toBe(403);
  });

  test('complaint form fits a mobile viewport', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/client/complaints/create');

    await expect(page.getByRole('heading', { name: /Buat Pengajuan Bantuan Baru/i })).toBeVisible();
    await expect(page.getByRole('button', { name: 'Kirim Pengajuan' })).toBeVisible();
    const contentWidth = await page.evaluate(() => document.documentElement.scrollWidth);
    expect(contentWidth).toBeLessThanOrEqual(390);
  });
});
