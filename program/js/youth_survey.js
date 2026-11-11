'use strict';
(() => {
 const config=JSON.parse(document.getElementById('survey-config').textContent), schema=config.schema;
 const $=id=>document.getElementById(id); let step=1, busy=false;
 const showError=text=>{ $('notice').textContent=text; $('notice').hidden=false; $('notice').focus(); };
 const clearError=()=>{$('notice').hidden=true;};
 function values(){
  const data={}; for(const q of schema.questions){const box=$(q.id); if(box.hidden)continue;
   if(q.type==='text')data[q.id]=box.querySelector('textarea').value.trim();
   else if(q.type==='multiple')data[q.id]=Array.from(box.querySelectorAll('input:checked'),x=>x.value);
   else data[q.id]=box.querySelector('input:checked')?.value??null;
  } return data;
 }
 function sync(){
  for(const q of schema.questions.filter(q=>q.depends)){
   const off=$(q.depends).querySelector('input:checked')?.value==='2',box=$(q.id);box.hidden=off;
   box.querySelectorAll('input').forEach(i=>{i.disabled=off;if(off)i.checked=false;});
  }
  const v=values(); $('answered').textContent=`ตอบแล้ว ${Object.values(v).filter(x=>Array.isArray(x)?x.length:!!x).length} / 30 ข้อ`;
 }
 function renderAnswers(target,answers,stored=false){
  target.replaceChildren();
  for(let s=1;s<=5;s++){
   const title=document.createElement('h3');title.textContent=`ส่วนที่ ${s}: ${schema.sections[s-1]}`;target.append(title);
   for(const q of schema.questions.filter(q=>q.section===s)){
    const row=document.createElement('div');row.className='answer-row';const label=document.createElement('strong');label.textContent=`${q.number}. ${q.text}`;
    const p=document.createElement('p');const item=stored?answers[q.id]:null;
    const v=stored?item?.value:answers[q.id];
    const inactive=stored?item?.status==='not_applicable':q.depends&&answers[q.depends]==='2';
    p.textContent=inactive?'ไม่เข้าเงื่อนไข (เลือกไม่เคยใช้)':v===null||v===undefined||v===''||(Array.isArray(v)&&!v.length)?'ข้าม / ไม่ประสงค์ตอบ':q.type==='text'?v:(Array.isArray(v)?v:[v]).map(x=>q.options[Number(x)-1]??'ไม่พบตัวเลือก').join(' · ');
    row.append(label,p);target.append(row);
   }
  }
 }
 function go(n){step=n;clearError();document.querySelectorAll('.survey-section').forEach(el=>el.hidden=Number(el.dataset.section)!==n);$('review').hidden=n!==6;$('previous').hidden=n===1;$('next').hidden=n===6;$('send').hidden=n!==6;$('step-label').textContent=n===6?'ตรวจคำตอบก่อนส่ง':`ส่วนที่ ${n} / 5`;$('progress').value=Math.min(n,5);if(n===6)renderAnswers($('review-answers'),values());const head=(n===6?$('review'):document.querySelector(`[data-section="${n}"]`)).querySelector('h2');head.focus();head.scrollIntoView({block:'start',behavior:'smooth'});}
 $('start').onclick=()=>{if(!['eligible','guardian','assent'].every(id=>$(id).checked)){showError('กรุณาอ่านคำชี้แจง ตรวจคุณสมบัติ และยืนยันความยินยอมทั้ง 3 ข้อ หากยังไม่พร้อม สามารถกลับหน้ากิจกรรมได้');return;}$('intro').hidden=true;$('survey').hidden=false;sync();go(1);};
 $('survey').addEventListener('change',event=>{if(event.target.name==='s2q7'&&event.target.checked){const box=$('s2q7');if(event.target.value==='1')box.querySelectorAll('input').forEach(i=>{if(i!==event.target)i.checked=false;});else box.querySelector('input[value="1"]').checked=false;}sync();});
 $('survey').addEventListener('input',sync);
 $('previous').onclick=()=>go(Math.max(1,step-1));$('next').onclick=()=>go(Math.min(6,step+1));
 $('stop').onclick=()=>{if(confirm('ออกโดยไม่บันทึกคำตอบใช่ไหม?')){ $('survey').reset();location.href='home.php';}};
 async function api(data){const response=await fetch('api/youth_survey.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({...data,csrf:config.csrf})});let json;try{json=await response.json();}catch{throw new Error('ติดต่อระบบไม่ได้ กรุณาลองอีกครั้ง');}if(!response.ok)throw new Error(json.error||'เกิดข้อผิดพลาด');return json;}
 $('survey').onsubmit=async event=>{event.preventDefault();if(busy||step!==6)return;busy=true;$('send').disabled=true;clearError();try{const data=await api({action:'submit',submission:config.submission,area:config.area,eligible:$('eligible').checked,guardian:$('guardian').checked,assent:$('assent').checked,answers:values()});$('survey').hidden=true;$('result').hidden=false;$('result').scrollIntoView({behavior:'smooth'});}catch(e){showError(e.message);}finally{busy=false;$('send').disabled=false;}};
})();
