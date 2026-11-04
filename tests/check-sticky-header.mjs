/**
 * Verify the shared header remains visible while scrolling all four pages.
 * Runs on desktop, tablet and small/mobile viewport sizes, and checks the
 * mobile navigation drawer still opens relative to the sticky header.
 */
import http from 'node:http';
import {readFileSync, existsSync} from 'node:fs';
import {resolve, extname, sep} from 'node:path';
import {chromium} from 'playwright';

const root=process.cwd();
const mime={'.html':'text/html; charset=utf-8','.css':'text/css; charset=utf-8',
 '.js':'text/javascript; charset=utf-8','.webp':'image/webp',
 '.png':'image/png','.jpg':'image/jpeg','.gif':'image/gif'};
const server=http.createServer((req,res)=>{
 const reqPath=new URL(req.url,'http://localhost').pathname;
 const target=resolve(root,'.'+(reqPath==='/'?'/index.html':reqPath));
 if(!target.startsWith(root+sep)||!existsSync(target)){
   res.writeHead(404);res.end('Not found');return;
 }
 res.writeHead(200,{'Content-Type':mime[extname(target)]||'application/octet-stream'});
 res.end(readFileSync(target));
});
await new Promise(resolve=>server.listen(0,'127.0.0.1',resolve));
const browser=await chromium.launch({headless:true,args:['--no-sandbox']});
const pages=['index','about','services','contact'];
const viewports=[{width:1440,height:900},{width:1024,height:768},
 {width:768,height:1024},{width:390,height:844},{width:320,height:568}];
let failures=[];
try{
 for(const size of viewports){
  for(const pageName of pages){
   const page=await browser.newPage({viewport:size});
   await page.addInitScript(()=>sessionStorage.setItem('rf_loader_played_session','1'));
   await page.route(/^https:\/\/(images\.pexels\.com|fonts\.googleapis\.com|fonts\.gstatic\.com)\//,route=>route.abort());
   await page.goto('http://127.0.0.1:'+server.address().port+'/'+pageName+'.html',{waitUntil:'domcontentloaded'});
   await page.addStyleTag({content:'html{scroll-behavior:auto!important}'});
   const first=await page.evaluate(()=>{
    const header=document.querySelector('.site-header');
    const bar=document.querySelector('.top-bar');
    return {
      headerTop:header.getBoundingClientRect().top,
      headerHeight:header.getBoundingClientRect().height,
      barHeight:bar.getBoundingClientRect().height,
      headerPosition:getComputedStyle(header).position,
      bodyOverflow:getComputedStyle(document.body).overflowX,
      htmlOverflow:getComputedStyle(document.documentElement).overflowX,
      documentHeight:document.documentElement.scrollHeight,
      viewportHeight:innerHeight
    };
   });
   if(first.headerPosition!=='sticky'||first.htmlOverflow!=='clip'||first.bodyOverflow!=='clip')
    failures.push(size.width+' '+pageName+': sticky or overflow CSS invalid '+JSON.stringify(first));
   if(first.documentHeight<first.viewportHeight+950)
    failures.push(size.width+' '+pageName+': cannot sufficiently test scrolling');
   if(size.width>860&&Math.abs(first.headerTop-first.barHeight)>3)
    failures.push(size.width+' '+pageName+': desktop header must start below top utility bar');
   if(size.width<=860&&Math.abs(first.headerTop)>2)
    failures.push(size.width+' '+pageName+': mobile header must start at viewport top');

   for(const scrollPoint of [450,1100]){
    const pos=await page.evaluate(async y=>{
      window.scrollTo(0,y);
      await new Promise(resolve=>requestAnimationFrame(()=>requestAnimationFrame(resolve)));
      const h=document.querySelector('.site-header');
      const rect=h.getBoundingClientRect();
      const content=document.querySelector('main').getBoundingClientRect();
      const menu=getComputedStyle(h).zIndex;
      return {scrollY:scrollY,top:rect.top,bottom:rect.bottom,height:rect.height,
        contentTop:content.top,opacity:getComputedStyle(h).opacity,zIndex:menu,
        boxShadow:getComputedStyle(h).boxShadow};
    },scrollPoint);
    console.log(size.width+'x'+size.height,pageName,scrollPoint,JSON.stringify(pos));
    if(pos.scrollY<scrollPoint-4||Math.abs(pos.top)>2||Math.abs(pos.height-first.headerHeight)>2)
     failures.push(size.width+' '+pageName+': header not sticky after '+scrollPoint+' scroll');
    if(Number(pos.zIndex)<10)
     failures.push(size.width+' '+pageName+': sticky header stacking order too low');
    if(pos.boxShadow==='none')
     failures.push(size.width+' '+pageName+': scrolled header missing elevation');
   }
   if(size.width<=860){
    await page.locator('#menu-button').click();
    const nav=await page.evaluate(()=>{
      const menu=document.querySelector('#mobile-nav');
      const head=document.querySelector('.site-header');
      return {hidden:menu.hidden,top:menu.getBoundingClientRect().top,
        headerBottom:head.getBoundingClientRect().bottom,
        aria:document.querySelector('#menu-button').getAttribute('aria-expanded')};
    });
    if(nav.hidden||nav.aria!=='true'||Math.abs(nav.top-nav.headerBottom)>2)
     failures.push(size.width+' '+pageName+': mobile drawer misaligned after scrolling '+JSON.stringify(nav));
   }
   await page.close();
  }
 }
}finally{
 await browser.close();
 await new Promise(resolve=>server.close(resolve));
}
if(failures.length){console.error(failures.join('\n'));process.exitCode=1;}
else console.log('PASS: Sticky header and mobile drawer on all four pages at five viewport sizes');
