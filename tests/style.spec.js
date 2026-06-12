import { test, expect } from '@playwright/test';
import { PAGES } from './config.js';

async function discoverBlogPosts(page) {
  await page.goto('/posts/');
  await page.waitForLoadState('networkidle');
  const blogPostUrls = await page.evaluate(() => {
    const links = Array.from(document.querySelectorAll('a[href*="/posts/"]'));
    return links
      .map(link => link.href)
      .filter(href => href !== window.location.href)
      .filter((href, index, self) => self.indexOf(href) === index);
  });
  return blogPostUrls;
}

function getSafeFilename(url) {
  const urlObj = new URL(url, 'http://localhost');
  const filename = urlObj.pathname
    .replace(/^\//, '')
    .replace(/\/$/, '')
    .replace(/\//g, '-')
    || 'homepage';
  return `${filename}.png`;
}

for (const page of PAGES) {
  test(`${page.name} visual regression`, async ({ page: playwright }) => {
    await playwright.goto(page.url);
    await playwright.waitForLoadState('networkidle');
    await playwright.waitForLoadState('load');
    // Wait for fonts to load to ensure consistent rendering
    await playwright.evaluate(() => document.fonts.ready);
    await expect(playwright).toHaveScreenshot(`${page.name}.png`, {
      fullPage: true,
      animations: 'disabled',
    });
  });
}

test.describe('Blog Posts', () => {
  let blogPostUrls = [];

  test.beforeAll(async ({ browser }) => {
    const page = await browser.newPage();
    blogPostUrls = await discoverBlogPosts(page);
    await page.close();
  });

  test('recent blog posts visual regression', async ({ page }) => {
    const postsToTest = blogPostUrls.slice(0, 3);
    for (const url of postsToTest) {
      await page.goto(url);
      await page.waitForLoadState('networkidle');
      await page.waitForLoadState('load');
      await page.evaluate(() => document.fonts.ready);
      await expect(page).toHaveScreenshot(getSafeFilename(url), {
        fullPage: true,
        animations: 'disabled',
      });
    }
  });
});
