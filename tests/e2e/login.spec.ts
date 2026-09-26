import { expect, test } from '@playwright/test';

function requiredEnv(name: string): string {
  const value = process.env[name];
  if (!value) {
    if (name === 'E2E_CUSTOMER_EMAIL') return 'budi@netmanager.local';
    if (name === 'E2E_INACTIVE_EMAIL') return 'customer@gmail.com';
    if (name === 'E2E_TEST_PASSWORD') return 'password';
    throw new Error(`Missing ${name}. Provide a dedicated E2E test credential.`);
  }
  return value;
}

test.describe('authentication', () => {
  test('public homepage links users to login', async ({ page }) => {
    await page.goto('/');

    await expect(page.getByText('NetManager').first()).toBeVisible();
    await page.getByRole('link', { name: /log\s*in|masuk/i }).first().click();
    await expect(page).toHaveURL(/\/login$/);
    await expect(page.getByRole('heading', { name: 'Welcome Back' })).toBeVisible();
  });

  test('valid customer credentials redirect to the customer dashboard', async ({ page }) => {
    await page.goto('/login');
    await page.getByLabel('Email Address').fill(requiredEnv('E2E_CUSTOMER_EMAIL'));
    await page.getByLabel('Password', { exact: true }).fill(requiredEnv('E2E_TEST_PASSWORD'));
    await page.getByRole('button', { name: 'Sign In' }).click();

    await expect(page).toHaveURL(/\/client\/dashboard$/);
  });

  test('invalid password keeps the user on login', async ({ page }) => {
    await page.goto('/login');
    await page.getByLabel('Email Address').fill(requiredEnv('E2E_CUSTOMER_EMAIL'));
    await page.getByLabel('Password', { exact: true }).fill(`${requiredEnv('E2E_TEST_PASSWORD')}-invalid`);
    await page.getByRole('button', { name: 'Sign In' }).click();

    await expect(page).toHaveURL(/\/login$/);
    await expect(page.getByRole('alert')).toContainText(/email atau password/i);
  });

  test('inactive account is rejected with an activation message', async ({ page }) => {
    await page.goto('/login');
    await page.getByLabel('Email Address').fill(requiredEnv('E2E_INACTIVE_EMAIL'));
    await page.getByLabel('Password', { exact: true }).fill(requiredEnv('E2E_TEST_PASSWORD'));
    await page.getByRole('button', { name: 'Sign In' }).click();

    await expect(page).toHaveURL(/\/login$/);
    await expect(page.getByRole('alert')).toContainText(/belum aktif.*administrator/i);
  });
});
