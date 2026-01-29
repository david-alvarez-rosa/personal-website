import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './tests',
  fullyParallel: true,
  reporter: 'html',
  use: {
    baseURL: 'http://localhost:1313',
    screenshot: 'only-on-failure',
  },
  expect: {
    toHaveScreenshot: {
      // Allow 5% pixel differences in CI, but require exact match locally
      maxDiffPixelRatio: process.env.CI ? 0.05 : 0,
    },
  },
  projects: [
    {
      name: 'Desktop Chrome',
      use: { ...devices['Desktop Chrome'] },
    },
    {
      name: 'Desktop Firefox',
      use: { ...devices['Desktop Firefox'] },
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
