import { readFileSync, existsSync } from 'node:fs';
import { resolve } from 'node:path';
const pages=['index.html','about.html','services.html','contact.html'];
const contents=Object.fromEntries(pages.map(p=>[p,readFileSync(p,'utf8')]));
const failures=[];
const photoCount=(html)=>(html.match(/images\.pexels\.com\/photos\//g)||[]).length;
const emojis=/[\u{1F300}-\u{1FAFF}]/u;
for(const [page,html] of Object.entries(contents)){
  if(photoCount(html)<1)failures.push(page+': missing illustrative photography');
  if(!html.includes('rf-brand-ribbon'))failures.push(page+': missing four-service brand strip');
  if(emojis.test(html))failures.push(page+': emoji must be replaced with vector icons');
  if(!html.includes('assets/lucide.min.js'))failures.push(page+': missing local Lucide icon runtime');
  if(!html.includes('name="robots" content="noindex, follow"'))failures.push(page+': no preview noindex');
  if(!html.includes('name="viewport"'))failures.push(page+': no viewport');
  if(!html.includes('styles.css'))failures.push(page+': no stylesheet');
  if(!html.includes('id="main"'))failures.push(page+': no main region');
  for(const match of html.matchAll(/(?:href|src|srcset)="([^"]+)"/g)){
    const raw=match[1].replaceAll('&amp;','&').trim().split(/\s+/)[0];
    if(/^(?:https?:|data:|mailto:|tel:|javascript:)/.test(raw))continue;
    const [rawPath,hash]=raw.split('#');
    const file=rawPath.split('?')[0];
    if(file&&!existsSync(resolve(file)))failures.push(page+': missing local asset '+file);
    const target=contents[file]||contents[page];
    if(hash && !target.includes('id="'+hash+'"'))failures.push(page+': missing target '+raw);
  }
}
if(!contents['index.html'].includes('Behaviour Support')||!contents['services.html'].includes('Occupational Therapy'))failures.push('Original service content missing');
if(!contents['contact.html'].includes('formspree.io/f/placeholder'))failures.push('Unexpected change to client-review intake concept');
const sv=contents['services.html'];
if(!sv.includes('rf-services-hero-media'))failures.push('Services header photo missing');
if((sv.match(/class="service-art-wrapper rf-service-media"/g)||[]).length!==4)failures.push('Expected 4 consistent service photo panels');
for(const service of ['physiotherapy','behaviour-support','occupational-therapy','speech-pathology']) {
  if(!sv.includes('assets/'+service+'-art.webp'))failures.push('Missing brand artwork '+service);
}
const loaderJS=readFileSync('script.js','utf8');
const loaderCSS=readFileSync('styles.css','utf8');
if(!loaderJS.includes("rf_loader_played_session"))failures.push('Loader must play only once per session');
if(!loaderJS.includes('cycleMs = 5600'))failures.push('Loader display must match the retimed 5.6s assets');
if(!loaderJS.includes('scheduled = true'))failures.push('Loader must not schedule duplicate dismissals');
if(!loaderCSS.includes('opacity 0.8s'))failures.push('Loader fade timing should remain 0.8s');
// The first fold is viewport-height-aware, not an oversized 620px fixed hero.
const homeHeroHTML=contents['index.html'];
const homeHeroCSS=readFileSync('styles.css','utf8');
const homeHeroJS=readFileSync('script.js','utf8');
if(!homeHeroHTML.includes('class="hero home-hero-viewport"'))failures.push('Homepage is missing the first-fold hero class');
if(!homeHeroCSS.includes('min-height: calc(100svh - var(--rf-first-fold-offset))'))failures.push('Homepage hero does not fit visible viewport');
if(!homeHeroCSS.includes('flex: 1 0 auto'))failures.push('Homepage hero needs a flexible content row');
if(!homeHeroJS.includes("getBoundingClientRect().height") || !homeHeroJS.includes("--rf-first-fold-offset"))failures.push('Homepage hero must account for actual header height');
// Keep the same minimalist pre-footer contact band on every shared page.
const bandSource=contents['index.html'].match(/  <!-- Shared preview \+ PHP contact band[\s\S]*?<\/section>/)?.[0];
if(!bandSource)failures.push('Shared contact band missing on home');
for(const [page,content] of Object.entries(contents)){
  const match=content.match(/  <!-- Shared preview \+ PHP contact band[\s\S]*?<\/section>/);
  if(!match || match[0]!==bandSource)failures.push(page+': contact band out of sync');
  if(!match || content.indexOf('rf-contact-band-inner')>content.indexOf('<footer class="footer">'))failures.push(page+': contact band not before footer');
  if(!match || !match[0].includes('contact.html'))failures.push(page+': missing email enquiry action');
  if(!match || !match[0].includes('awaiting client confirmation'))failures.push(page+': unverified number must be labelled');
}
for(const [page,html] of Object.entries(contents)){
  const band=html.match(/<section class="rf-contact-band"[\s\S]*?<\/section>/)?.[0]||'';
  if(!band.includes('1300 000 000') || !band.includes('<span>000 000</span>'))failures.push(page+': numeric placeholder missing from contact banner');
  if(!band.includes('href="tel:1300000000"'))failures.push(page+': banner number must be clickable');
  if(!band.includes('data-lucide="phone-call"'))failures.push(page+': banner missing left call icon');
  if(!band.includes('data-lucide="mail"'))failures.push(page+': banner missing email icon');
  if(band.includes('1300 RIGHTFIT') || band.includes('>RIGHTFIT</span>'))failures.push(page+': obsolete branded number in contact banner');
}
// Homepage values is a genuinely responsive photo composition with its own service illustration.
const home=contents['index.html'];
const valuesMarkup=home.match(/<section class="section values-section rf-home-values"[\s\S]*?<\/section>/)?.[0]||'';
for(const fragment of [
  'assets/values-consultation.jpg','assets/behaviour-support-art.webp',
  'assets/behaviour-support-art.png','class="rf-values-art"',
  'class="rf-values-photo"','class="rf-values-intro"','class="rf-values-details"'
])if(!valuesMarkup.includes(fragment))failures.push('Homepage values missing '+fragment);
if(valuesMarkup.indexOf('class="rf-values-intro"')>valuesMarkup.indexOf('class="values-visual rf-values-visual"'))
  failures.push('Homepage values heading must precede visual in reading order');
if(failures.length){console.error(failures.join('\n'));process.exit(1)}
console.log('PASS: Original client-review design and services, internal links, assets, preview SEO meta.');
