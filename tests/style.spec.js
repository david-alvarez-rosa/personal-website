import { test, expect } from '@playwright/test';
import { DARK, PAGES, PINNED_POSTS, RECENT_POSTS } from './config.js';

const tagFor = name => ({ tag: DARK.includes(name) ? ['@dark'] : [] });

async function discoverBlogPosts(page) {
  await page.goto('/posts/');
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
  test(`${page.name} visual regression`, tagFor(page.name), async ({ page: playwright }) => {
    await playwright.goto(page.url);
    await waitForStableRender(playwright);
    await expect(playwright).toHaveScreenshot(`${page.name}.png`, {
      fullPage: true,
      animations: 'disabled',
    });
  });
}

async function expectPostScreenshot(page, url) {
  await page.goto(url);
  await waitForStableRender(page);
  await expect(page).toHaveScreenshot(getSafeFilename(url), {
    fullPage: true,
    animations: 'disabled',
  });
}

test.describe('Blog Posts', () => {
  for (const slug of PINNED_POSTS) {
    test(`${slug} visual regression`, tagFor(slug), async ({ page }) => {
      await expectPostScreenshot(page, `/posts/${slug}/`);
    });
  }

  for (let i = 0; i < RECENT_POSTS; i++) {
    test(`recent post ${i + 1} visual regression`, async ({ page }) => {
      const url = (await discoverBlogPosts(page))[i];
      const slug = new URL(url).pathname.split('/').filter(Boolean).pop();
      test.skip(PINNED_POSTS.includes(slug), `${slug} is pinned`);
      await expectPostScreenshot(page, url);
    });
  }
});
