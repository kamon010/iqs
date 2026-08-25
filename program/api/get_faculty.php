<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$university_id = $_POST["university_id"];
$sql = 'SELECT * FROM faculty WHERE university_id ='.$university_id;
$data = $query->queryDB_to_array($sql);
$html = '';
foreach($data as $key => $val){
    $html .='<option value="'.$val['faculty_id'].'">'.$val['faculty_name'].'</option>';
}

echo addslashes($html);



?>