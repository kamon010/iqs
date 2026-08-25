<?
include_once('../indyEngine/dbControl.php');
include_once('../php/htmlCore.php');
$query = new DBControl();

$user_id = $_POST["user_id"];
$sql = "SELECT * FROM test_b WHERE user_id=".$user_id;
$data = $query->queryDB_to_array($sql);			
echo '
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-center">ครั้งที่</th>
            <th class="text-center">เมื่อ<br>&nbsp;</th>
            <th class="text-center">คำถามที่ 1</th>
            <th class="text-center">คำถามที่ 2</th>
            <th class="text-center">คำถามที่ 3</th>
            <th class="text-center">คำถามที่ 4</th>
            <th class="text-center">คำถามที่ 5</th>
            <th class="text-center">โอกาสเสี่ยง<br>ที่จะสูบแบบมวน</th>
            <th class="text-center">โอกาสเสี่ยง<br>ที่จะสูบบุหรีไฟฟ้า</th>
        </tr>
    </thead>
    <tbody>
';
$i=1;
foreach($data as $key => $val){	
    echo '
        <tr>
            <th class="text-center">'.$i.'</th>
            <td class="text-center">'.getTextDateTH($val['date']).'</td>
            <td class="text-center">ข้อ '.$val['q1'].'</td>
            <td class="text-center">ข้อ '.$val['q2'].'</td>
            <td class="text-center">ข้อ '.$val['q3'].'</td>
            <td class="text-center">ข้อ '.$val['q4'].'</td>
            <td class="text-center">ข้อ '.$val['q5'].'</td>
            <td class="text-center">'.getTest($val).'</td>
            <td class="text-center">'.getTest2($val).'</td>
        </tr>
    ';
    $i++;
}

echo '
    </tbody>
</table>
';

function getTest($data){
    if($data['q1']==4 && $data['q2']==4){
        return 'ไม่มีโอกาสเสี่ยง<br>ที่จะสูบบุหรี่แบบมวนในอนาคต ';
    }
    else{
        return 'มีโอกาสเสี่ยงที่<br>จะสูบบุหรี่แบบมวนในอนาคต';
    }
}
    
function getTest2($data){
    if($data['q3']==4 && $data['q4']==4 && $data['q5']==4){
        return 'ไม่มีโอกาสเสี่ยง<br>ที่จะสูบบุหรี่ไฟฟ้าในอนาคต';
    }
    else{
        return 'มีโอกาสเสี่ยงที่<br>จะสูบบุหรี่ไฟฟ้าในอนาคต';
    }
}
?>