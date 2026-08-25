<?
include_once('../indyEngine/dbControl.php');
include_once('../php/htmlCore.php');
$query = new DBControl();

$user_id = $_POST["user_id"];
$sql = "SELECT * FROM test_a WHERE user_id=".$user_id;
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
            <th class="text-center">คำถามที่ 6</th>
            <th class="text-center">คะแนนรวม</th>
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
            <td class="text-center">ข้อ '.$val['q6'].'</td>
            <td class="text-center">'.$val['score'].'</td>
        </tr>
    ';
    $i++;
}

echo '
    </tbody>
</table>
';
?>