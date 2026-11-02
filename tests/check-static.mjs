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
if(failures.length){console.error(failures.join('\n'));process.exit(1)}
console.log('PASS: Original client-review design and services, internal links, assets, preview SEO meta.');
