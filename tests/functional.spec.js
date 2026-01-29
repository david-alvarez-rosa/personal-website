import { test, expect } from '@playwright/test';

test.describe('Email Subscription Form', () => {
  test('should validate email input correctly', async ({ page }) => {
    await page.goto('/');
    
    const emailInput = page.locator('input[type="email"]').first();
    const submitButton = page.locator('form button[type="submit"]').first();
    
    // Test empty email
    await emailInput.fill('');
    await submitButton.click();
    let validationMessage = await emailInput.evaluate(el => el.validationMessage);
    expect(validationMessage).toBeTruthy();
    
    // Test invalid email
    await emailInput.fill('not-an-email');
    await submitButton.click();
    validationMessage = await emailInput.evaluate(el => el.validationMessage);
    expect(validationMessage).toBeTruthy();
  });
});

test.describe('RSS Feed', () => {
  test('should be valid and discoverable', async ({ page }) => {
    // Check feed is discoverable from homepage
    await page.goto('/');
    const rssLink = page.locator('link[type="application/rss+xml"], link[type="application/atom+xml"]');
    await expect(rssLink).toHaveCount(1);
    
    // Check feed content
    const response = await page.goto('/index.xml');
    expect(response.status()).toBe(200);
    
    const content = await response.text();
    expect(content).toMatch(/^<\?xml/);
    expect(content).toContain('<title>');
    expect(content).toContain('<link>');
    
    // Check has items
    const hasItems = content.includes('<item>') || content.includes('<entry>');
    expect(hasItems).toBe(true);
  });
});

test.describe('Security', () => {
  test('should not load insecure resources', async ({ page }) => {
    const insecureResources = [];
    
    page.on('request', request => {
      const url = request.url();
      if (url.startsWith('http://') && !url.includes('localhost')) {
        insecureResources.push(url);
      }
    });
    
    await page.goto('/');
    expect(insecureResources).toHaveLength(0);
  });
});
