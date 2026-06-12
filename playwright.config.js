import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './tests',
  fullyParallel: true,
  workers: process.env.CI ? '100%' : undefined,
  reporter: 'html',
  use: {
    baseURL: 'http://localhost:1313',
    screenshot: 'only-on-failure',
  },
  projects: [
    {
      name: 'Desktop Chrome',
      use: { ...devices['Desktop Chrome'] },
    },
    {
      name: 'Mobile Chrome',
      use: { ...devices['Pixel 5'] },
    },
  ],
  webServer: {
    command: 'hugo server',
  },
});
