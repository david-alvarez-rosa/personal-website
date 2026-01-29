import { test, expect } from '@playwright/test';
import { PAGES, VIEWPORTS } from './config.js';

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

function getSafeFilename(url, suffix = '') {
  const urlObj = new URL(url, 'http://localhost');
  let filename = urlObj.pathname
    .replace(/^\//, '')
    .replace(/\/$/, '')
    .replace(/\//g, '-')
    || 'homepage';
  if (suffix) {
    filename += `-${suffix}`;
  }
  return `${filename}.png`;
}

for (const page of PAGES) {
  for (const viewport of Object.values(VIEWPORTS)) {
    test(`${page.name} - ${viewport.name} visual regression`, async ({ page: playwright }) => {
      await playwright.setViewportSize({ width: viewport.width, height: viewport.height });
      await playwright.goto(page.url);
      await playwright.waitForLoadState('networkidle');
      await playwright.waitForLoadState('load');
      // Wait for fonts to load to ensure consistent rendering
      await playwright.evaluate(() => document.fonts.ready);
      await expect(playwright).toHaveScreenshot(`${page.name}-${viewport.name}.png`, {
        fullPage: true,
        animations: 'disabled',
      });
    });
  }
}

test.describe('Blog Posts', () => {
  let blogPostUrls = [];

  test.beforeAll(async ({ browser }) => {
    const page = await browser.newPage();
    blogPostUrls = await discoverBlogPosts(page);
    await page.close();
  });

  test('recent blog posts visual regression - desktop', async ({ page }) => {
    const postsToTest = blogPostUrls.slice(0, 3);
    for (const url of postsToTest) {
      await page.goto(url);
      await page.waitForLoadState('networkidle');
      await page.waitForLoadState('load');
      await page.evaluate(() => document.fonts.ready);
      const filename = getSafeFilename(url, 'desktop');
      await expect(page).toHaveScreenshot(filename, {
        fullPage: true,
        animations: 'disabled',
      });
    }
  });

  test('recent blog post visual regression - mobile', async ({ page }) => {
    if (blogPostUrls.length === 0) return;
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto(blogPostUrls[0]);
    await page.waitForLoadState('networkidle');
    await page.waitForLoadState('load');
    await page.evaluate(() => document.fonts.ready);
    const filename = getSafeFilename(blogPostUrls[0], 'mobile');
    await expect(page).toHaveScreenshot(filename, {
      fullPage: true,
      animations: 'disabled',
    });
  });
});
