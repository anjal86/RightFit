// Confirm that the exact stock photos referenced by the preview resolve to real images.
import { readFileSync } from 'node:fs';
const pages=['index.html','about.html','services.html','contact.html'];
const urls=[...new Set(pages.flatMap(p=>{
 const html=readFileSync(p,'utf8');
 return [...html.matchAll(/src="(https:\/\/images\.pexels\.com\/photos\/[^"]+)"/g)].map(m=>m[1].replaceAll('&amp;','&'));
}))];
// One original Pexels placement is now self-hosted to prevent blank mobile Values images.
if(urls.length!==11)throw new Error('Expected 11 remote photo placements plus one self-hosted photo, got '+urls.length);
const home=readFileSync('index.html','utf8');
if(!home.includes('src="assets/values-consultation.jpg"'))throw new Error('Self-hosted home Values photo missing');
const local=readFileSync('assets/values-consultation.jpg');
if(local.length<24000 || local[0]!==0xff || local[1]!==0xd8)
  throw new Error('Self-hosted Values image is missing, empty or not a JPEG');
console.log('OK self-hosted homepage Values photo',local.length,'bytes');
const problems=[];
await Promise.all(urls.map(async original=>{
 const u=new URL(original);u.searchParams.set('w','30');
 try {
  const res=await fetch(u,{signal:AbortSignal.timeout(16000)});
  if(!res.ok || !(res.headers.get('content-type')||'').startsWith('image/'))problems.push(u.pathname+' returned '+res.status+' '+res.headers.get('content-type'));
  else console.log('OK',u.pathname,res.status);
  await res.body?.cancel();
 } catch(e){problems.push(u.pathname+': '+e.message);}
}));
if(problems.length){console.error(problems.join('\n'));process.exit(1)}
console.log('PASS: All '+urls.length+' remote image placements and one self-hosted Values photo are valid.');
