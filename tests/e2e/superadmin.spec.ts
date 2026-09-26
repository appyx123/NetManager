import { expect, test } from '@playwright/test';

test.use({ storageState: 'playwright/.auth/superadmin.json' });

test('super admin sees staff accounts and reset password controls', async ({ page }) => {
  await page.goto('/superadmin/users');
  await expect(page.getByRole('heading', { name: 'Manajemen Akun Staf' })).toBeVisible();

  const userRow = page.getByRole('row').filter({ hasText: 'admin@netmanager.local' }).first();
  await expect(userRow).toBeVisible();
  await expect(userRow.getByRole('button', { name: 'Reset PW' })).toBeVisible();
});

test('super admin sees maintenance command controls', async ({ page }) => {
  await page.goto('/superadmin/maintenance');

  await expect(page.getByText('Clear Cache')).toBeVisible();
  await expect(page.getByRole('button', { name: 'Clear Cache' })).toBeVisible();
  await expect(page.getByRole('button', { name: 'Jalankan Optimasi' })).toBeVisible();
  await expect(page.getByRole('button', { name: 'Bersihkan Log' })).toBeVisible();
});
