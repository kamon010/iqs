<?
include_once('../indyEngine/dbControl.php');
include_once('../php/htmlCore.php');
$query = new DBControl();

$user_id = $_POST["user_id"];
$sql = "SELECT * FROM day WHERE user_id=".$user_id;
$data = $query->queryDB_to_array($sql);			
echo '
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-center">วันที่</th>
            <th class="text-center">จำนวนมวน</th>
            <th class="text-center">เมื่อ</th>
        </tr>
    </thead>
    <tbody>
';
$i=1;
foreach($data as $key => $val){	
    echo '
        <tr>
            <td class="text-center">วันที่ '.$val['day'].'</td>
            <td class="text-center">'.$val['n'].' มวน</td>
            <td class="text-center">'.getTextDateTH($val['date']).'</td>
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