<?
include_once('../indyEngine/dbControl.php');
include_once('../php/htmlCore.php');
$query = new DBControl();

$user_id = $_POST["user_id"];
$sql = "SELECT * FROM screening WHERE user_id=".$user_id;
$data = $query->queryDB_to_array($sql);			
echo '
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-center">เมื่อ</th>
            <th class="text-center">คำตอบ</th>
        </tr>
    </thead>
    <tbody>
';
$i=1;
foreach($data as $key => $val){	
    echo '
        <tr>
            <td class="text-center">'.getTextDateTH($val['date']).'</td>
            <td class="text-center">'.getTest($val['aws']).'</td>
        </tr>
    ';
    $i++;
}

echo '
    </tbody>
</table>
';

function getTest($n){
    if($n==1){
        return 'สูบบุหรี่มวนสลับกับบุหรี่ไฟฟ้า';
    }
    else if($n==2){
        return 'สูบบุหรี่มวนอย่างเดียว';
    }
    else if($n==3){
        return 'สูบบุหรี่ไฟฟ้าอย่างเดียว';
    }
    else if($n==4){
        return 'ไม่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า';
    }
}
?>