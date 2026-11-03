import http from 'node:http';
import { readFileSync, existsSync } from 'node:fs';
import { resolve, extname, sep } from 'node:path';
import { chromium } from 'playwright';

const root = process.cwd();
const mime = {
  '.html': 'text/html; charset=utf-8', '.css': 'text/css; charset=utf-8',
  '.js': 'text/javascript; charset=utf-8', '.png': 'image/png',
  '.webp': 'image/webp', '.gif': 'image/gif', '.ico': 'image/x-icon'
};
const server = http.createServer((req, res) => {
  const reqPath = new URL(req.url, 'http://localhost').pathname;
  const absolute = resolve(root, '.' + (reqPath === '/' ? '/index.html' : reqPath));
  if (!absolute.startsWith(root + sep) || !existsSync(absolute)) {
    res.writeHead(404); res.end('Not found'); return;
  }
  const bytes = readFileSync(absolute);
  res.writeHead(200, {'Content-Type': mime[extname(absolute)] || 'application/octet-stream'});
  res.end(bytes);
});
await new Promise(resolve => server.listen(0, '127.0.0.1', resolve));
const browser = await chromium.launch({headless: true, args:['--no-sandbox']});
const viewports = [
  {width: 1440, height: 900}, {width: 1366, height: 768},
  {width: 1024, height: 768}, {width: 768, height: 1024},
  {width: 390, height: 844}, {width: 375, height: 812},
  {width: 320, height: 568}
];
let errors = [];
try {
  for (const size of viewports) {
    const page = await browser.newPage({viewport:size, deviceScaleFactor:1});
    await page.addInitScript(() => {
      sessionStorage.setItem('rf_loader_played_session','1');
    });
    await page.route(/^https:\/\/(images\.pexels\.com|fonts\.googleapis\.com|fonts\.gstatic\.com)\//,
      route=>route.abort());
    await page.goto('http://127.0.0.1:'+server.address().port+'/index.html', {waitUntil:'domcontentloaded'});
    await page.evaluate(() => new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve))));
    const result = await page.evaluate(() => {
      const hero = document.querySelector('.home-hero-viewport');
      const header = document.querySelector('.site-header');
      const topbar = document.querySelector('.top-bar');
      const heroRect = hero.getBoundingClientRect();
      const copy = document.querySelector('.hero-copy').getBoundingClientRect();
      const visual = document.querySelector('.hero-visual').getBoundingClientRect();
      const contentFooter = document.querySelector('.hero-footer').getBoundingClientRect();
      return {
        viewportHeight: innerHeight,
        heroTop: Math.round(heroRect.top), heroBottom: Math.round(heroRect.bottom),
        header: Math.round(header.getBoundingClientRect().height),
        topbar: Math.round(topbar.getBoundingClientRect().height),
        copyBottom: Math.round(copy.bottom), visualBottom: Math.round(visual.bottom),
        footerTop: Math.round(contentFooter.top),
        overflowX: document.documentElement.scrollWidth - document.documentElement.clientWidth,
        callLink: (() => {
          const call=document.querySelector('.rf-contact-band-call');
          const icon=call?.querySelector('.rf-contact-band-call-icon');
          const num=call?.querySelector('.rf-contact-band-number');
          const bounds=call?.getBoundingClientRect();
          const n=num?.getBoundingClientRect();
          const i=icon?.getBoundingClientRect();
          return {href:call?.getAttribute('href'),left:bounds?.left,right:bounds?.right,
            iconRight:i?.right,numberLeft:n?.left,numberRight:n?.right,
            phoneIcon:!!call?.querySelector('[data-lucide="phone-call"]'),
            width:document.documentElement.clientWidth};
        })()
      };
    });
    console.log(size.width+'x'+size.height, JSON.stringify(result));
    if (result.overflowX > 2) errors.push(size.width+'x'+size.height+': horizontal overflow '+result.overflowX);
    const call=result.callLink;
    if (call.href!=='tel:1300000000' || !call.phoneIcon)
      errors.push(size.width+'x'+size.height+': missing functional call link or icon');
    if (call.left < -2 || call.right > call.width+2 || call.numberRight>call.width+2)
      errors.push(size.width+'x'+size.height+': call link overflows viewport');
    if (call.iconRight > call.numberLeft)
      errors.push(size.width+'x'+size.height+': call icon overlaps phone number');
    if (result.copyBottom > result.footerTop + 2 || result.visualBottom > result.footerTop + 2)
      errors.push(size.width+'x'+size.height+': hero copy or art overlaps footer');
    // Allow very short phone viewports to scroll rather than hiding content.
    if (size.height >= 760 && Math.abs(result.heroBottom - size.height) > 3)
      errors.push(size.width+'x'+size.height+': first screen ends at '+result.heroBottom+'px, viewport is '+size.height+'px');
    if (size.width === 320 && result.heroBottom < size.height - 3)
      errors.push('Short phone hero is shorter than available viewport');
    await page.close();
  }
} finally {
  await browser.close();
  await new Promise(resolve => server.close(resolve));
}
if (errors.length) {
  console.error(errors.join('\n'));
  process.exitCode=1;
} else {
  console.log('PASS: viewport-height homepage first fold across 7 screen sizes');
}
