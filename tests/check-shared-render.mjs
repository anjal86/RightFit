/**
 * Tests prove that the PHP site reads the same four HTML templates as GitHub Pages.
 * PHP-only differences: navigation, form endpoints/CSRF, checkbox arrays and flash.
 */
import {spawn} from 'node:child_process';
import {createServer} from 'node:net';
import {readFileSync,existsSync} from 'node:fs';
import assert from 'node:assert/strict';

const pages=['index','about','services','contact'];
const port=await new Promise((resolve,reject)=>{
  const socket=createServer();
  socket.on('error',reject);
  socket.listen(0,'127.0.0.1',()=>{
    const number=socket.address().port;
    socket.close(()=>resolve(number));
  });
});
let stderr='';
const proc=spawn('php',['-S','127.0.0.1:'+port,'-t','.'],{
  stdio:['ignore','ignore','pipe'],
  env:{...process.env,RIGHTFIT_INTAKE_ENABLED:'0',RIGHTFIT_ADMIN_ENABLED:'0',RIGHTFIT_SEO_APPROVED:'0'}
});
proc.stderr.on('data',chunk=>{stderr+=chunk.toString();});
const base='http://127.0.0.1:'+port;

function normalizedMain(html,label){
  const match=html.match(/<main id="main">([\s\S]*?)<\/main>/);
  if(!match)throw new Error(label+': missing main');
  return match[1]
    .replace(/href="(index|about|services|contact)\.php(?=[?#"])/g,(_,page)=>'href="'+page+'.html')
    .replace(/action="submit\.php" data-server-intake="true"/g,'action="https://formspree.io/f/placeholder"')
    .replace(/\s*<!-- Server-side CSRF & submission identity \(PHP only\) -->\s*<input type="hidden" name="csrf_token" value="[^"]*">\s*<input type="hidden" name="form_type" value="(?:enquiry|referral)">/g,'')
    .replace(/name="services_requested\[\]"/g,'name="services_requested"')
    .replace(/\s+/g,' ').trim();
}
const errors=[];
try {
  let ready=false;
  for(let i=0;i<40;i++){
    if(proc.exitCode!==null)throw new Error('PHP server stopped: '+stderr);
    try{const resp=await fetch(base+'/index.php');if(resp.ok){ready=true;break;}}catch{}
    await new Promise(resolve=>setTimeout(resolve,100));
  }
  if(!ready)throw new Error('PHP server never became ready: '+stderr);
  for(const page of pages){
    const original=readFileSync(page+'.html','utf8');
    const response=await fetch(base+'/'+page+'.php');
    const generated=await response.text();
    assert.equal(response.status,200,page+' PHP returned '+response.status+': '+stderr.slice(-1200));
    assert.ok(response.headers.get('content-type')?.includes('text/html'));
    assert.equal(normalizedMain(generated,page+'.php'),normalizedMain(original,page+'.html'),
      page+': PHP page content diverges from original HTML');
    for(const link of pages){
      if(original.includes('href="'+link+'.html'))
        assert.ok(generated.includes('href="'+link+'.php'),page+' PHP navigation lacks '+link);
    }
    assert.ok(generated.includes('name="robots" content="noindex, follow"'));
    assert.ok(generated.includes('assets/rightfit-logo'));
    assert.ok(generated.includes('class="rf-contact-band-call" href="tel:1300000000"'),
      page+' PHP must render shared click-to-call banner');
    assert.ok(generated.includes('data-lucide="phone-call"'),page+' PHP missing call icon');
    if(page==='index')assert.ok(generated.includes('class="hero home-hero-viewport"'));
    if(page==='services')assert.ok(generated.includes('rf-services-hero-photo'));
    if(page==='contact'){
      const actions=(generated.match(/action="submit\.php" data-server-intake="true"/g)||[]).length;
      const csrf=[...generated.matchAll(/name="csrf_token" value="([a-f0-9]{64})"/g)].map(m=>m[1]);
      const kinds=[...generated.matchAll(/name="form_type" value="([^"]+)"/g)].map(m=>m[1]);
      assert.equal(actions,2,'Both forms must use PHP intake endpoint');
      assert.equal(csrf.length,2,'Both forms must have a CSRF field');
      assert.equal(csrf[0],csrf[1],'The current session must share a CSRF token');
      assert.deepEqual(kinds,['enquiry','referral']);
      assert.equal((generated.match(/name="services_requested\[\]"/g)||[]).length,4);
      assert.equal((generated.match(/name="_website_trap"/g)||[]).length,2);
      assert.ok(!original.includes('action="submit.php"'));
    }
    console.log('PASS '+page+'.html ⇄ '+page+'.php: shared sections and PHP routes');
  }
  const disabled=await fetch(base+'/submit.php',{
    method:'POST',
    headers:{'Accept':'application/json','Content-Type':'application/x-www-form-urlencoded'},
    body:'form_type=enquiry&name=Preview'
  });
  const reply=await disabled.json();
  assert.equal(disabled.status,503,'Intake must be disabled unless approved');
  assert.equal(reply.success,false,'Intake must reject rather than claim delivery');
  const admin=await fetch(base+'/admin/login.php');
  assert.equal(admin.status,503,'Admin remains disabled');
  assert.ok(!existsSync('includes/header.php')&&!existsSync('includes/footer.php'),
    'Duplicate retired PHP header/footer should be absent');
  console.log('PASS: PHP security gates and both shared page outputs');
}catch(e){errors.push(e.stack||String(e));}
finally{
  proc.kill('SIGTERM');
  await new Promise(resolve=>{
    if(proc.exitCode!==null||proc.signalCode!==null)return resolve();
    proc.once('exit',resolve);setTimeout(resolve,2000);
  });
}
if(errors.length){
  console.error(errors.join('\n'));
  if(stderr.includes('Fatal error'))console.error(stderr.slice(-2000));
  process.exitCode=1;
}
