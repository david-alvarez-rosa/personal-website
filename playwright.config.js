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
      maxDiffPixelRatio: process.env.CI ? 0.01 : 0,
    },
  },
  projects: [
    {
      name: 'Desktop Chrome',
      use: { ...devices['Desktop Chrome'] },
      // Skip Desktop Chrome in CI due to rendering inconsistencies
      ...(process.env.CI && { testIgnore: /.*/ }),
    },
    {
      name: 'Desktop Firefox',
      use: { ...devices['Desktop Firefox'] },
    },
    {
      name: 'Mobile Chrome',
      use: { ...devices['Pixel 5'] },
      // Skip Mobile Chrome in CI due to rendering inconsistencies
      ...(process.env.CI && { testIgnore: /.*/ }),
    },
  ],
  webServer: {
    command: 'hugo server',
  },
});
