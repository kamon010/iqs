<?php
require_once __DIR__.'/api/youth_survey_common.php';
youth_session();
$areas = youth_areas();
$area = $_GET['area'] ?? '';
if (!is_string($area) || !isset($areas[$area])) {
?>
<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เลือกพื้นที่แบบสอบถาม | TRC</title>
    <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/youth_survey.css?v=<?=filemtime(__DIR__.'/css/youth_survey.css')?>">
</head>

<body class="youth-page">
    <main class="youth-wrap"><a class="back" href="home.php">‹ กลับหน้ากิจกรรม</a>
        <header class="youth-header"><span class="badge">กิจกรรม · ร่วมสร้างชุมชนปกป้องเด็ก</span>
            <h1>เลือกพื้นที่ของคุณ</h1>
            <p>เลือกพื้นที่ที่คุณอาศัยอยู่ ก่อนเข้าสู่แบบสอบถามเด็กและเยาวชน</p>
        </header>
        <section class="panel area-picker">
            <h2>คุณอาศัยอยู่ในพื้นที่ใด?</h2>
            <?php foreach ($areas as $key=>$label): ?><a class="area-card"
                href="youth_survey.php?area=<?=htmlspecialchars($key,ENT_QUOTES,'UTF-8')?>"><span><?=htmlspecialchars($label,ENT_QUOTES,'UTF-8')?></span><span
                    aria-hidden="true">›</span></a><?php endforeach; ?>
            <p class="hint">เลือกพื้นที่เพื่ออ่านคำชี้แจงก่อนเริ่มตอบ การเลือกพื้นที่เพียงอย่างเดียวยังไม่บันทึกคำตอบ
            </p>
        </section>
    </main>
</body>

</html>
<?php
    exit;
}
if (!isset($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));
$submission=bin2hex(random_bytes(24));
$_SESSION['submissions'][$submission]=bin2hex(random_bytes(32));
if(count($_SESSION['submissions'])>20) array_shift($_SESSION['submissions']);
$schema=youth_schema($area);
function esc($v){return htmlspecialchars($v,ENT_QUOTES,'UTF-8');}
?>
<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แบบสอบถามเด็กและเยาวชน | TRC</title>
    <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/youth_survey.css?v=<?=filemtime(__DIR__.'/css/youth_survey.css')?>">
    <script src="js/youth_survey.js?v=<?=filemtime(__DIR__.'/js/youth_survey.js')?>" defer></script>
</head>

<body class="youth-page">
    <main class="youth-wrap">
        <a class="back" href="home.php">‹ กลับหน้ากิจกรรม</a>
        <header class="youth-header"><span class="badge">กิจกรรม · ร่วมสร้างชุมชนปกป้องเด็ก</span>
            <h1>แบบสอบถามเด็กและเยาวชน</h1>
            <p>อายุ 10–18 ปี · 30 ข้อ · 5 ส่วน · ประมาณ 10–20 นาที</p>
        </header>
        <div class="selected-area"><span>พื้นที่: <?=esc($areas[$area])?></span> <a
                href="youth_survey.php">เปลี่ยนพื้นที่</a></div>
        <div id="notice" role="alert" tabindex="-1" hidden></div>
        <section id="intro" class="panel">
            <h2>ก่อนเริ่ม มาทำความเข้าใจกันก่อน</h2>
            <p>เราอยากฟังความคิดเห็นของคุณเกี่ยวกับชุมชนที่ช่วยปกป้องเด็กจากบุหรี่และบุหรี่ไฟฟ้า ไม่มีคำตอบถูกหรือผิด
                คุณอาจไม่ได้รับประโยชน์โดยตรง แต่ความคิดเห็นจะช่วยวางแผนชุมชน</p>
            <ul>
                <li>เข้าร่วมโดยสมัครใจ ต้องตอบคำถามที่แสดงให้ครบเพื่อไปส่วนถัดไป และหยุดหรือออกได้ทุกเมื่อ
                    โดยไม่มีผลต่อสิทธิหรือประโยชน์ที่คุณได้รับ</li>
                <li>บางข้อเกี่ยวกับการสูบและคนรอบตัวอาจทำให้ไม่สบายใจ หากไม่สะดวกตอบ สามารถหยุดตอบ
                    หรือออกจากแบบสอบถามได้</li>
                <li>คำตอบจะบันทึกเมื่อคุณกดส่งเท่านั้น ไม่เก็บชื่อ เบอร์โทร เลขประจำตัว วันเกิด
                    หรือเชื่อมคำตอบกับบัญชีผู้ใช้ โปรดไม่พิมพ์ชื่อ โรงเรียน ที่อยู่
                    หรือข้อมูลระบุตัวคุณหรือผู้อื่นในช่องข้อความ</li>
                <li>ก่อนส่ง คุณกลับไปแก้คำตอบหรือออกโดยไม่บันทึกได้ เมื่อส่งแล้วระบบจะไม่เชื่อมคำตอบกับบัญชี
                    จึงไม่สามารถค้นคำตอบของคุณจากชื่อหรือบัญชีได้</li>
            </ul>
            <?php if ($area === 'nam_phai'): ?><p class="hint">เอกสารต้นฉบับระบุพื้นที่ทุ่งเสลี่ยม
                หน้านี้ปรับพื้นที่เป็นน้ำไผ่ตามที่เลือก และใช้รายชื่อหมู่บ้านน้ำไผ่ทั้ง 8 หมู่
                โดยไม่ต้องระบุบ้านเลขที่หรือที่อยู่ละเอียด</p><?php endif; ?>
            <details>
                <summary>อ่านข้อมูลโครงการและคำชี้แจงจากเอกสารฉบับเต็ม</summary>
                <div class="source-info">
                    <?php foreach(json_decode(file_get_contents(__DIR__.'/api/youth_survey_information.json'),true) as $text): if(trim($text)==='')continue; ?>
                    <p><?=esc($text)?></p><?php endforeach; ?>
                </div>
            </details>
            <p class="research-note">ผู้วิจัย: รศ.ดร.จักรพันธ์ เพ็ชรภูมิ คณะสาธารณสุขศาสตร์
                มหาวิทยาลัยนเรศวร<br>สอบถามโครงการหรือขอความช่วยเหลือ: 083-9524531 · chakgarphanp@nu.ac.th</p>
            <div class="concept"><strong>แนวคิดที่กำลังศึกษาความคิดเห็น</strong>
                <p>ข้อเสนอให้เด็กที่เกิดหลัง พ.ศ. 2560 (ตั้งแต่ 1 มกราคม 2561)
                    ไม่สามารถเข้าถึงหรือใช้บุหรี่และบุหรี่ไฟฟ้าตลอดชีวิต แม้โตเป็นผู้ใหญ่ โดยชุมชนร่วมกันปกป้องเด็ก
                    ข้อความนี้เป็นแนวคิดในแบบสอบถาม</p>
                <p><strong>ยังเป็นข้อเสนอเพื่อศึกษาความคิดเห็น
                        ไม่ใช่ข้อห้ามตลอดชีวิตที่บังคับใช้ในปัจจุบันตามคำชี้แจงของเอกสาร</strong></p>
                <small>เอกสารระบุปีเริ่มต้นต่างกัน (2560/2561) หน้านี้ใช้ข้อความในคำชี้แจงและส่วนที่ 5
                    ระหว่างรอผู้วิจัยยืนยัน</small>
            </div>
            <h3>ตรวจความพร้อมและความยินยอม</h3>
            <label class="choice"><input type="checkbox" id="eligible"> <span>ฉันอายุ 10–18 ปี
                    อาศัยใน<?=esc($areas[$area])?> และอ่านเข้าใจคำชี้แจงนี้</span></label>
            <label class="choice"><input type="checkbox" id="guardian">
                <span>ฉันได้รับความยินยอมจากผู้ปกครองหรือผู้แทนโดยชอบธรรมแล้ว ตามกระบวนการของโครงการ
                    (ไม่ต้องกรอกชื่อหรือแนบเอกสารในหน้านี้)</span></label>
            <label class="choice"><input type="checkbox" id="assent"> <span>ฉันเข้าใจว่าต้องตอบคำถามที่แสดงให้ครบเพื่อไปต่อ หรือหยุดได้
                    และสมัครใจตอบแบบสอบถาม</span></label>
            <p><small>หากยังไม่ได้รับความยินยอม ให้หยุดไว้ก่อนและติดต่อผู้วิจัย การทำเครื่องหมายนี้เป็นเพียงการยืนยัน
                    ไม่แทนกระบวนการขอความยินยอมของโครงการ</small></p>
            <button type="button" id="start">เริ่มตอบแบบสอบถาม</button> <a href="home.php"
                class="secondary-link">ยังไม่เข้าร่วม</a>
        </section>
        <form id="survey" hidden>
            <div class="survey-status"><span id="step-label"></span><span id="answered" aria-live="polite"></span></div>
            <progress id="progress" max="5" value="1" aria-label="ส่วนที่กำลังตอบ"></progress>
            <?php foreach($schema['sections'] as $index=>$title): ?><section class="survey-section panel"
                data-section="<?=$index+1?>" hidden>
                <h2 tabindex="-1">ส่วนที่ <?=$index+1?>: <?=esc($title)?></h2>
                <p class="hint">ตอบตามความเป็นจริงหรือความรู้สึกของคุณ · กรุณาตอบทุกข้อที่แสดงก่อนกดถัดไป</p>
                <?php if($index===4): ?><div class="concept">ส่วนนี้ถามความคิดเห็นต่อข้อเสนอเด็กที่เกิดหลัง พ.ศ. 2560
                    ปลอดบุหรี่และบุหรี่ไฟฟ้าตลอดชีวิต เป็นแนวคิดระหว่างศึกษา
                    มิใช่ข้อห้ามตลอดชีวิตที่บังคับใช้ในปัจจุบันตามเอกสาร</div><?php endif; ?>
                <?php foreach($schema['questions'] as $q): if($q['section']!==$index+1)continue; ?>
                <fieldset class="question" id="<?=esc($q['id'])?>"
                    <?=isset($q['depends'])?'data-depends="'.esc($q['depends']).'"':''?>>
                    <legend><?=esc($q['number'].'. '.$q['text'])?></legend>
                    <?php if($q['type']==='text'): ?><label
                        for="<?=esc($q['id'])?>-text"><?=$q['id']==='s1q5'?'ชื่อหมู่บ้านเท่านั้น':'ความคิดเห็นของคุณ (ไม่เกิน 2,000 ตัวอักษร)'?></label><textarea
                        id="<?=esc($q['id'])?>-text" name="<?=esc($q['id'])?>" maxlength="2000" rows="5"
                        placeholder="ไม่ระบุชื่อ โรงเรียน ที่อยู่ หรือข้อมูลที่บอกว่าเป็นใคร"></textarea>
                    <?php else: foreach($q['options'] as $i=>$option): ?><label class="choice"><input
                            type="<?=$q['type']==='multiple'?'checkbox':'radio'?>" name="<?=esc($q['id'])?>"
                            value="<?=$i+1?>"><span><?=esc($option)?></span></label><?php endforeach; endif; ?>

                </fieldset><?php endforeach; ?>
            </section><?php endforeach; ?>
            <section id="review" class="panel" hidden>
                <h2 tabindex="-1">ตรวจคำตอบก่อนส่ง</h2>
                <p>ทุกข้อที่แสดงต้องมีคำตอบ ส่วนข้อที่ไม่เข้าเงื่อนไขจะบันทึกเป็น “ไม่เข้าเงื่อนไข” คุณกลับไปแก้ไขหรือหยุดได้ก่อนส่ง</p>
                <div id="review-answers"></div>
            </section>
            <div class="survey-actions"><button type="button" id="previous" class="secondary">ย้อนกลับ</button><button
                    type="button" id="next">ถัดไป</button><button type="submit" id="send"
                    hidden>ส่งคำตอบโดยสมัครใจ</button></div>
            <button type="button" id="stop" class="skip">หยุดและออกโดยไม่บันทึก</button>
        </form>
        <section id="result" class="panel" hidden>
            <h2>บันทึกคำตอบเรียบร้อยแล้ว</h2>
            <p>ขอบคุณที่แบ่งปันความคิดเห็นของคุณ</p><a href="home.php">กลับหน้ากิจกรรม</a>
        </section>
        <footer>
            <p>ใช้ข้อมูลเพื่อการวิจัยและรายงานในภาพรวม</p><a href="home.php">กลับหน้ากิจกรรม</a>
        </footer>
    </main>
    <script type="application/json" id="survey-config">
    <?=json_encode(['csrf'=>$_SESSION['csrf'],'submission'=>$submission,'area'=>$area,'schema'=>$schema],JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE)?>
    </script>
</body>

</html>