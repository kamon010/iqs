<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"];
$q = 'q'.$_POST["q"];
$val = $_POST["val"];

$sql = 'UPDATE user SET '.$q.' = "'.$val.'" WHERE user_id ='.$user_id;
$query->queryDB($sql);

echo '
<script> 
   
</script>
';

?>