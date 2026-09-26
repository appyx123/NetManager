import { expect, test } from '@playwright/test';

test.use({ storageState: 'playwright/.auth/marketing.json' });

test('marketing can convert an existing prospect into a customer', async ({ page }) => {
  await page.goto('/marketing/leads');
  await expect(page.getByRole('heading', { name: /Daftar Prospek/i })).toBeVisible();

  const prospect = page.getByRole('row').filter({ hasText: 'Siti Aminah' }).first();
  await expect(prospect).toBeVisible();
  await prospect.getByTitle('Convert to Customer (Mulai Instalasi)').click();

  await expect(page).toHaveURL(/\/marketing\/leads$/);
  await expect(page.getByText(/Konversi Berhasil!/i)).toBeVisible();
  await expect(page.getByText('Siti Aminah').first()).toBeVisible();
});
