// Confirm that the exact stock photos referenced by the preview resolve to real images.
import { readFileSync } from 'node:fs';
const pages=['index.html','about.html','services.html','contact.html'];
const urls=[...new Set(pages.flatMap(p=>{
 const html=readFileSync(p,'utf8');
 return [...html.matchAll(/src="(https:\/\/images\.pexels\.com\/photos\/[^"]+)"/g)].map(m=>m[1].replaceAll('&amp;','&'));
}))];
if(urls.length!==12)throw new Error('Expected 12 photo placements but got '+urls.length);
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
console.log('PASS: All '+urls.length+' image placements are available from Pexels CDN.');
