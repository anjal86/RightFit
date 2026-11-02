(function(){
  'use strict';
  const year = document.getElementById('year');
  if(year) year.textContent = String(new Date().getFullYear());
  const menuBtn = document.getElementById('menu-button');
  const menu = document.getElementById('mobile-nav');
  if(menuBtn && menu){
    const close=()=>{menu.hidden=true;menuBtn.setAttribute('aria-expanded','false');menuBtn.setAttribute('aria-label','Open navigation');};
    menuBtn.addEventListener('click',()=>{const opened=menuBtn.getAttribute('aria-expanded')==='true';menu.hidden=opened;menuBtn.setAttribute('aria-expanded',String(!opened));menuBtn.setAttribute('aria-label',opened?'Open navigation':'Close navigation');});
    document.addEventListener('keydown',(e)=>{if(e.key==='Escape')close();});
    document.addEventListener('click',(e)=>{if(!menu.hidden && !menu.contains(e.target) && !menuBtn.contains(e.target))close();});
    window.addEventListener('resize',()=>{if(window.innerWidth>800)close();});
  }
  const serviceSelect = document.getElementById('service');
  if(serviceSelect){
    const serviceFromUrl = new URLSearchParams(window.location.search).get('service');
    const selectMap={'physiotherapy':'Physiotherapy','behaviour-support':'Behaviour Support (BSP)','occupational-therapy':'Occupational Therapy','speech-pathology':'Speech Pathology'};
    if(serviceFromUrl && Object.hasOwn(selectMap,serviceFromUrl))serviceSelect.value=selectMap[serviceFromUrl];
  }
  const form = document.getElementById('enquiry-form');
  const feedback=document.getElementById('form-feedback');
  if(form&&feedback){
    form.addEventListener('submit',async(e)=>{
      e.preventDefault();
      if(!form.reportValidity())return;
      const data=new FormData(form);
      const enquiry=`RightFit Allied Health Services — general enquiry\n\nName: ${data.get('name')}\nEmail: ${data.get('email')}\nService: ${data.get('service')}\n\nMessage:\n${data.get('message')}`;
      feedback.className='form-feedback';
      try{
        if(navigator.clipboard&&window.isSecureContext){await navigator.clipboard.writeText(enquiry);feedback.textContent='Enquiry draft copied to clipboard. This preview has not sent or saved your details.';feedback.classList.add('success');}
        else {const blob = new Blob([enquiry],{type:'text/plain;charset=utf-8'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='rightfit-enquiry-draft.txt';document.body.appendChild(a);a.click();a.remove();setTimeout(()=>URL.revokeObjectURL(url),1000);feedback.textContent='Enquiry draft downloaded as a text file. No information was sent or saved by the website.';feedback.classList.add('success');}
      }catch(err){feedback.textContent='Could not copy. Please select and copy your message manually. Nothing has been sent.';feedback.classList.add('error');}
    });
  }
})();
