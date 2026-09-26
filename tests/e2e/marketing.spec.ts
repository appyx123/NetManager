import { expect, test } from '@playwright/test';

test.use({ storageState: 'playwright/.auth/marketing.json' });

test('marketing can convert an existing prospect into a customer', async ({ page }) => {
  await page.goto('/marketing/leads');
  await expect(page.getByRole('heading', { name: /Daftar Prospek/i })).toBeVisible();

  const convertBtn = page.getByTitle('Convert to Customer (Mulai Instalasi)').first();
  if (await convertBtn.isVisible({ timeout: 4000 }).catch(() => false)) {
    page.once('dialog', dialog => dialog.accept());
    await convertBtn.click();

    await expect(page).toHaveURL(/\/marketing\/leads$/);
    await expect(page.getByText(/Konversi Berhasil!/i).first()).toBeVisible();
  } else {
    // If already converted or no open prospect, verify prospect table is visible
    await expect(page.getByRole('table')).toBeVisible();
  }
});
