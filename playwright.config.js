import { defineConfig, devices } from '@playwright/test';

const isCI = !!process.env.CI;

const baseProjects = [
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
];

const safariProjects = [
  {
    name: 'Desktop Safari',
    use: { ...devices['Desktop Safari'] },
  },
  {
    name: 'Mobile Safari',
    use: { ...devices['iPhone 12'] },
  },
];

export default defineConfig({
  testDir: './tests',
  fullyParallel: true,
  reporter: 'html',
  use: {
    baseURL: 'http://localhost:1313',
    screenshot: 'only-on-failure',
  },
  projects: isCI ? [...baseProjects, ...safariProjects] : baseProjects,
  webServer: {
    command: 'hugo server',
  },
});
