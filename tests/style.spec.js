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

async function waitForStableRender(page) {
  await page.waitForLoadState('networkidle');
  await page.waitForLoadState('load');
  await page.evaluate(async () => {
    await document.fonts.ready;
    await Promise.all(
      Array.from(document.images).map(img => img.decode().catch(() => {})));
  });
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
    await waitForStableRender(playwright);
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
      await waitForStableRender(page);
      await expect(page).toHaveScreenshot(getSafeFilename(url), {
        fullPage: true,
        animations: 'disabled',
      });
    }
  });
});
