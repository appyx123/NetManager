import { expect, test } from '@playwright/test';

const evidencePng = {
  name: 'e2e-installation-evidence.png',
  mimeType: 'image/png',
  buffer: Buffer.from(
    'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
    'base64',
  ),
};

test.use({ storageState: 'playwright/.auth/technician.json' });

test('technician can claim an installation job and submit physical parameters', async ({ page }) => {
  await page.goto('/technician/open-tickets');
  await expect(page.getByRole('heading', { name: 'Bursa Tugas' })).toBeVisible();

  const job = page.locator('article').filter({ hasText: 'Instalasi tambahan untuk pengujian alur kerja teknisi.' }).first();
  await expect(job).toBeVisible();
  await job.getByRole('link', { name: 'Lihat Rincian' }).click();
  await page.getByRole('button', { name: 'Klaim / Ambil Tugas' }).click();

  await expect(page).toHaveURL(/\/technician\/my-tasks$/);
  await expect(page.getByText(/Tugas berhasil diambil/i).first()).toBeVisible();

  const task = page.getByRole('row').filter({ hasText: 'Instalasi Tambahan - Budi Santoso' }).first();
  await task.getByRole('link', { name: 'Kerjakan' }).click();
  await expect(page.getByRole('heading', { name: 'Form Instalasi' })).toBeVisible();

  await page.locator('input[name="cable_length"]').fill('45');
  await page.locator('input[name="device_brand"]').fill('ZTE F609');
  await page.locator('input[name="device_mac"]').fill('00:1A:2B:3C:4D:5E');
  await page.locator('input[name="odp_port"]').fill('Port 5');
  await page.locator('input[name="dbm_signal"]').fill('-18.5');
  await page.locator('select[name="installation_status"]').selectOption('completed');
  await page.locator('input[name="evidence_photo_path"]').setInputFiles(evidencePng);
  await page.getByRole('button', { name: 'Simpan Laporan Instalasi' }).click();

  await expect(page).toHaveURL(/\/technician\/my-tasks$/);
  await expect(page.getByText(/Laporan.*berhasil disimpan|berhasil diperbarui|Tugas berhasil/i).first()).toBeVisible();
});
