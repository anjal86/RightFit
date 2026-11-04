import {readFileSync} from 'node:fs';
import {execFileSync} from 'node:child_process';
import assert from 'node:assert/strict';

// Build output must never drift from the reusable component source.
execFileSync(process.execPath,['tools/build-site.mjs','--check'],{stdio:'inherit'});
const pages=['index','about','services','contact'];
const footers=[];
for(const page of pages){
  const html=readFileSync(page+'.html','utf8');
  const source=readFileSync('site/pages/'+page+'.html','utf8');
  const header=html.split('  <!-- Utility Bar -->')[1]?.split('  <main id="main">')[0];
  const footer=html.split('  <!-- Footer -->')[1]?.split('  <script src="assets/lucide.min.js"')[0];
  assert.ok(header && footer,page+' missing shared markup');
  assert.ok(source.includes('  <!-- @component:site-header -->'),page+' missing header reference');
  assert.ok(source.includes('  <!-- @component:site-footer -->'),page+' missing footer reference');
  assert.ok(!source.includes('<header class="site-header"'),page+' duplicated header in source');
  assert.ok(!source.includes('<footer class="footer"'),page+' duplicated footer in source');
  const links=html.match(/<a class="nav-link current" href="([^"]+)" aria-current="page">/g)||[];
  assert.equal(links.length,2,page+' must have active nav in desktop and mobile');
  assert.ok(links.every(x=>x.includes('href="'+page+'.html"')),page+' has wrong active nav');
  if(page==='contact'){
    assert.ok(header.includes('href="#referral-form-section" class="top-bar-referral"'));
    assert.ok(!header.includes('>General Enquiry</a>'));
  }else{
    assert.ok(header.includes('href="contact.html?type=referral" class="top-bar-referral"'));
    assert.ok(header.includes('>General Enquiry</a>'));
  }
  if(page==='services')assert.ok(header.includes('In-Home, School, Clinic &amp; Telehealth Available'));
  footers.push(footer);
}
assert.ok(footers.every(x=>x===footers[0]),'Footers must be identical on all four pages');
console.log('PASS: shared header/footer and page-aware navigation on all four pages');
