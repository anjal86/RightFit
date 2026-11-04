/**
 * RightFit reusable build-time HTML components.
 *
 * Editable source: site/pages/*.html and site/components/*.html.
 * Generated output: 4 root *.html pages, consumed by GitHub Pages and PHP.
 *
 * Usage: node tools/build-site.mjs          (write output)
 *        node tools/build-site.mjs --check  (verify committed output)
 */
import {readFileSync,writeFileSync} from 'node:fs';
import {resolve,dirname} from 'node:path';
import {fileURLToPath} from 'node:url';

const root=resolve(dirname(fileURLToPath(import.meta.url)),'..');
const pages=['index','about','services','contact'];
const read=(path)=>readFileSync(resolve(root,path),'utf8');
const headerTemplate=read('site/components/site-header.html');
const footerTemplate=read('site/components/site-footer.html');
const generalEnquiry='        <a class="btn btn-small btn-outline desktop-cta" href="contact.html">General Enquiry</a>\n';
const markers={
  header:'  <!-- @component:site-header -->\n',
  footer:'  <!-- @component:site-footer -->\n'
};
const nav={INDEX:'index',ABOUT:'about',SERVICES:'services',CONTACT:'contact'};

function replaceExactlyOnce(input,needle,replacement,label){
  if(input.split(needle).length!==2){
    throw new Error(label+': expected one '+JSON.stringify(needle));
  }
  return input.replace(needle,replacement);
}
function renderHeader(page){
  const options={
    DELIVERY_MODE:page==='services'?'In-Home, School, Clinic &amp; Telehealth Available':'In-Home, School &amp; Telehealth Available',
    TOP_REFERRAL_HREF:page==='contact'?'#referral-form-section':'contact.html?type=referral',
    TOP_PHONE_LABEL:page==='index'?'Call RightFit Allied Health Services on 1300 000 000':'Call RightFit on 1300 000 000',
    GENERAL_ENQUIRY_CTA:page==='contact'?'':generalEnquiry
  };
  for(const [token,slug] of Object.entries(nav)){
    options['ACTIVE_'+token]=page===slug?' current':'';
    options['ARIA_'+token]=page===slug?' aria-current="page"':'';
  }
  const output=headerTemplate.replace(/\{\{([A-Z_]+)\}\}/g,(_,name)=>{
    if(!Object.hasOwn(options,name))throw new Error('Unknown header option '+name);
    return options[name];
  });
  if(output.includes('{{'))throw new Error('Unrendered header token');
  return output;
}
export function renderPage(page){
  if(!pages.includes(page))throw new Error('Unknown page '+page);
  let output=read('site/pages/'+page+'.html');
  output=replaceExactlyOnce(output,markers.header,renderHeader(page),page+' header');
  output=replaceExactlyOnce(output,markers.footer,footerTemplate,page+' footer');
  if(output.includes('<!-- @component:'))throw new Error(page+': unresolved component');
  return output;
}
const checking=process.argv.includes('--check');
let mismatches=0;
for(const page of pages){
  const target=resolve(root,page+'.html');
  const generated=renderPage(page);
  if(checking){
    if(readFileSync(target,'utf8')!==generated){
      console.error('OUT OF SYNC '+page+'.html: run node tools/build-site.mjs and commit the generated pages.');
      mismatches++;
    }else console.log('OK '+page+'.html matches shared components');
  }else{
    if(readFileSync(target,'utf8')!==generated){
      writeFileSync(target,generated,'utf8');
      mismatches++;
      console.log('Built '+page+'.html');
    }else console.log('Unchanged '+page+'.html');
  }
}
if(checking&&mismatches)process.exitCode=1;
if(checking&&!mismatches)console.log('PASS: All four public pages derive from the same header and footer components.');
