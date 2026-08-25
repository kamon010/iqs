<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$username = $_POST['xxyy'] ;
$password =  $_POST['ppww'];
	
if($username=='' || $password==''){
    echo 'กรุณากรอกข้อมูลให้ครบ !';
}
else{
    $sql = 'SELECT user_id FROM user WHERE user_username ="'.$username.'" and user_password = "'.$password.'" AND user_type = "member"';	
    $user_id = $query->queryDB_to_string($sql);
    if($user_id!=''){
        setcookie("user_id",$user_id ,time()+((3600*3)),'/');
        echo '<script> window.location="index.php"; </script>';	
    }
    else{
        echo 'ไม่พบข้อมูลสมาชิกของท่าน !';
    }
}
?>