import { defineConfig, devices } from '@playwright/test';

const baseURL = process.env.E2E_BASE_URL ?? 'http://127.0.0.1:8000';
const authFile = 'playwright/.auth/customer.json';
const webServerCommand = process.env.E2E_WEB_SERVER_COMMAND ?? 'php artisan serve --host=127.0.0.1 --port=8000';

export default defineConfig({
  testDir: './tests/e2e',
  fullyParallel: true,
  forbidOnly: Boolean(process.env.CI),
  retries: process.env.CI ? 2 : 0,
  workers: process.env.CI ? 1 : undefined,
  reporter: [['list'], ['html', { outputFolder: 'playwright-report', open: 'never' }]],
  use: {
    baseURL,
    screenshot: 'only-on-failure',
    trace: 'on-first-retry',
    video: 'retain-on-failure',
  },
  webServer: {
    command: webServerCommand,
    url: `${baseURL}/up`,
    reuseExistingServer: !process.env.CI,
    timeout: 120_000,
    stdout: 'ignore',
    stderr: 'pipe',
  },
  projects: [
    {
      name: 'setup',
      testMatch: /auth\.setup\.ts/,
      use: { ...devices['Desktop Chrome'], browserName: 'chromium' },
    },
    {
      name: 'login-chromium',
      testMatch: /login\.spec\.ts/,
      use: { ...devices['Desktop Chrome'], browserName: 'chromium' },
    },
    {
      name: 'chromium',
      dependencies: ['setup'],
      testIgnore: /login\.spec\.ts|(?:marketing|technician|admin|superadmin)\.spec\.ts/,
      use: { ...devices['Desktop Chrome'], browserName: 'chromium', storageState: authFile },
    },
    {
      name: 'firefox',
      dependencies: ['setup'],
      testIgnore: /login\.spec\.ts|(?:marketing|technician|admin|superadmin)\.spec\.ts/,
      use: { ...devices['Desktop Firefox'], browserName: 'firefox', storageState: authFile },
    },
    {
      name: 'webkit',
      dependencies: ['setup'],
      testIgnore: /login\.spec\.ts|(?:marketing|technician|admin|superadmin)\.spec\.ts/,
      use: { ...devices['Desktop Safari'], browserName: 'webkit', storageState: authFile },
    },
    {
      name: 'staff-chromium',
      dependencies: ['setup'],
      testMatch: /(?:marketing|technician|admin|superadmin)\.spec\.ts/,
      use: { ...devices['Desktop Chrome'], browserName: 'chromium' },
    },
  ],
});
