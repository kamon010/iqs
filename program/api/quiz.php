<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();
$user_id = $_COOKIE["user_id"];

$str = '';

if(isset($_POST['q1_1'])){
    $str .= '<p>1.1 ปริมาณโซเดียมที่แนะนำให้บริโภคต่อวัน คือ ไม่เกิน 2,000 มิลลิกรัม </p>';
    $query->queryDB('INSERT INTO quiz(user_id,no,aws) VALUES ('.$user_id.',1,'.$_POST['q1_1'].')');
    if($_POST['q1_1']==1){
        $score = $query->queryDB_to_string('SELECT score FROM score WHERE name="q1_1"');
        $sql = $query->queryDB('UPDATE user SET user_quiz_score=user_quiz_score+'.$score.' WHERE user_id='.$user_id);
        $str .= ' <span class="text-green"> <i class="fa-regular fa-circle-check"></i> ใช่ </span></p>';
    }
    else{
        $str .= ' <span class="text-red"> <i class="fa-regular fa-circle-xmark"></i> <b>เฉลย</b> ปริมาณโซเดียมที่แนะนำให้บริโภคต่อวัน คือ ไม่เกิน 2,000 มิลลิกรัม </span></p>';
    }
}

if(isset($_POST['q1_2'])){
    $str .= '<p>1.2 ปริมาณโซเดียมในอาหารว่าง ไม่ควรเกิน 600 มิลลิกรัมต่อมื้อ </p>';
    $query->queryDB('INSERT INTO quiz(user_id,no,aws) VALUES ('.$user_id.',2,'.$_POST['q1_2'].')');
    if($_POST['q1_2']==0){
        $score = $query->queryDB_to_string('SELECT score FROM score WHERE name="q1_2"');
        $sql = $query->queryDB('UPDATE user SET user_quiz_score=user_quiz_score+'.$score.' WHERE user_id='.$user_id);
        $str .= ' <span class="text-green"> <i class="fa-regular fa-circle-check"></i> ไม่ใช่ </span></p>';
    }
    else{
        $str .= ' <span class="text-red"> <i class="fa-regular fa-circle-xmark"></i> <b>เฉลย</b> ปริมาณโซเดียมในอาหารว่าง ไม่ควรเกิน 200 มิลลิกรัมต่อวัน </span></p>';
    }
}

if(isset($_POST['q2_1'])){
    $str .= '<p>2.1 อาหารธรรมชาติไม่ผ่านการปรุงแต่ง เป็นอาหารที่ไม่มีโซเดียมเลย </p>';
    $query->queryDB('INSERT INTO quiz(user_id,no,aws) VALUES ('.$user_id.',3,'.$_POST['q2_1'].')');
    if($_POST['q2_1']==0){
        $score = $query->queryDB_to_string('SELECT score FROM score WHERE name="q2_1"');
        $sql = $query->queryDB('UPDATE user SET user_quiz_score=user_quiz_score+'.$score.' WHERE user_id='.$user_id);
        $str .= ' <span class="text-green"> <i class="fa-regular fa-circle-check"></i> ไม่ใช่ </span></p>';
    }
    else{
        $str .= ' <span class="text-red"> <i class="fa-regular fa-circle-xmark"></i> <b>เฉลย</b> อาหารธรรมชาติที่ไม่ผ่านการปรุงแต่ง จะมีโซเดียม แต่ปริมาณน้อยกว่าอาหารที่ผ่านการปรุงแต่งหรือแปรรูป </span></p>';
    }
}
if(isset($_POST['q2_2'])){
    $str .= '<p>2.2 การรับประทานอาหารรูปแบบ DASH เน้นลดแป้ง แต่เพิ่มไขมันเพื่อช่วยลดระดับความดันโลหิต </p>';
    $query->queryDB('INSERT INTO quiz(user_id,no,aws) VALUES ('.$user_id.',4,'.$_POST['q2_2'].')');
    if($_POST['q2_2']==0){
        $score = $query->queryDB_to_string('SELECT score FROM score WHERE name="q2_2"');
        $sql = $query->queryDB('UPDATE user SET user_quiz_score=user_quiz_score+'.$score.' WHERE user_id='.$user_id);
        $str .= ' <span class="text-green"> <i class="fa-regular fa-circle-check"></i> ไม่ใช่ </span></p>';
    }
    else{
        $str .= ' <span class="text-red"> <i class="fa-regular fa-circle-xmark"></i> <b>เฉลย</b> อาหารแบบ DASH เน้นรับประทานผักผลไม้สด เนื้อสัตว์ไม่ติดมัน ธัญพืชไม่ขัดสี เพื่อให้ได้ใยอาหาร โปรตีน แคลเซียม โพแทสเซียม และแมกนีเซียม ลดโซเดียม และลดไขมันอิ่มตัว </span></p>';
    }
}

if(isset($_POST['q3_1'])){
    $str .= '<p>3.1 โซเดียมจะถูกขับออกจากร่างกายภายใน 24 ชั่วโมงผ่านทางปัสสาวะ </p>';
    $query->queryDB('INSERT INTO quiz(user_id,no,aws) VALUES ('.$user_id.',5,'.$_POST['q3_1'].')');
    if($_POST['q3_1']==1){
        $score = $query->queryDB_to_string('SELECT score FROM score WHERE name="q3_1"');
        $sql = $query->queryDB('UPDATE user SET user_quiz_score=user_quiz_score+'.$score.' WHERE user_id='.$user_id);
        $str .= ' <span class="text-green"> <i class="fa-regular fa-circle-check"></i> ใช่ </span></p>';
    }
    else{
        $str .= ' <span class="text-red"> <i class="fa-regular fa-circle-xmark"></i> <b>เฉลย</b> โซเดียมจะถูกขับออกจากร่างกายภายใน 24 ชั่วโมงผ่านทางปัสสาวะ </span></p>';
    }
}
if(isset($_POST['q3_2'])){
    $str .= '<p>3.2 การรับประทานอาหารที่มีโซเดียมสูงเป็นประจำทำให้เสี่ยงต่อโรคมะเร็งกระเพาะอาหาร </p>';
    $query->queryDB('INSERT INTO quiz(user_id,no,aws) VALUES ('.$user_id.',6,'.$_POST['q3_2'].')');
    if($_POST['q3_2']==1){
        $score = $query->queryDB_to_string('SELECT score FROM score WHERE name="q3_2"');
        $sql = $query->queryDB('UPDATE user SET user_quiz_score=user_quiz_score+'.$score.' WHERE user_id='.$user_id);
        $str .= ' <span class="text-green"> <i class="fa-regular fa-circle-check"></i> ใช่ </span></p>';
    }
    else{
        $str .= ' <span class="text-red"> <i class="fa-regular fa-circle-xmark"></i> <b>เฉลย</b> การรับประทนอาหารที่มีโซเดียมสูงเป็นประจำ ทำให้เสี่ยงต่อโรคมะเร็งกระเพาะอาหารได้ </span></p>';
    }
}



if(isset($_POST['q4_1_1']) || isset($_POST['q4_1_2']) || isset($_POST['q4_1_3']) || isset($_POST['q4_1_4']) || isset($_POST['q4_1_5']) || isset($_POST['q4_1_6']) || isset($_POST['q4_1_7']) || isset($_POST['q4_1_8']) ){
    $str .= '<p>4.1 สัญลักษณ์นี้บอกให้ทราบข้อมูลใด </p>';
    $aws = $_POST['q4_1_1'].','.$_POST['q4_1_2'].','.$_POST['q4_1_3'].','.$_POST['q4_1_4'].','.$_POST['q4_1_5'].','.$_POST['q4_1_6'].','.$_POST['q4_1_7'].','.$_POST['q4_1_8'];
    $query->queryDB('INSERT INTO quiz(user_id,no,aws) VALUES ('.$user_id.',7,"'.$aws.'")');
    if($_POST['q4_1_2']==1 && $_POST['q4_1_6']==1 && $_POST['q4_1_7']==1 && $_POST['q4_1_1']==0 && $_POST['q4_1_3']==0 && $_POST['q4_1_4']==0 && $_POST['q4_1_5']==0 && $_POST['q4_1_8']==0){
        $score = $query->queryDB_to_string('SELECT score FROM score WHERE name="q4_1"');
        $sql = $query->queryDB('UPDATE user SET user_quiz_score=user_quiz_score+'.$score.' WHERE user_id='.$user_id);
        $str .= ' <span class="text-green"> <i class="fa-regular fa-circle-check"></i> น้ำตาล ไขมัน โซเดียม  </span></p>';
    }
    else{
        $str .= ' <span class="text-red"> <i class="fa-regular fa-circle-xmark"></i> <b>เฉลย</b> สัญลักษณ์ทางเลือกสุขภาพ บอกให้ทราบปริมาณน้ำตาล ไขมัน โซเดียม ผ่านเกณฑ์ </span></p>';
    }
}
if($_POST['q4_2']!=''){
    $str .= '<p>4.2 ถ้ารับประทานบะหมี่กึ่งสำเร็จรูปนี้หมดซอง เติมเครื่องปรุง และรับประทานน้ำซุปหมด แสดงว่า วันนี้ท่านจะรับประทานโซเดียมได้อีกเท่าไร </p>';
    $query->queryDB('INSERT INTO quiz(user_id,no,aws) VALUES ('.$user_id.',8,'.$_POST['q4_2'].')');
    if($_POST['q4_2']=='600'){
        $score = $query->queryDB_to_string('SELECT score FROM score WHERE name="q4_2"');
        $sql = $query->queryDB('UPDATE user SET user_quiz_score=user_quiz_score+'.$score.' WHERE user_id='.$user_id);
        $str .= ' <span class="text-green"> <i class="fa-regular fa-circle-check"></i> 600 มิลลิกรัม</span></p>';
    }
    else{
        $str .= ' <span class="text-red"> <i class="fa-regular fa-circle-xmark"></i> <b>เฉลย</b> 600 มิลลิกรัม </span></p>';
    }
}



$str = addslashes($str);
echo '<script>';
echo "$('#html_model').html('".$str."');";
echo "$('#model').appendTo('body').modal('show');";
echo '</script>';

?>