<?
ob_start();
include_once('admin/indyEngine/dbControl.php');

function getHead(){
$style_path = __DIR__ . '/../css/style.css';
$ran = file_exists($style_path) ? filemtime($style_path) : time();
echo '
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta http-equiv="ScreenOrientation" content="autoRotate:disabled">

	<title> TRC </title>
	<link rel="shortcut icon" type="image/png" href="img/icon.png" />
    <meta property="og:image" content="img/icon.png">
	
	<!-- PWA -->
	<link rel="manifest" href="manifest.json">
	<meta name="application-name" content="รู้ทันโซเดียม">
	<meta name="apple-mobile-web-app-title" content="รู้ทันโซเดียม">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="apple-touch-icon" href="img/icon.png">
	<meta name="msapplication-TileImage" content="img/icon.png">
	<meta name="msapplication-TileColor" content="#fff">

	<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Orbitron" rel="stylesheet">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

	<script src="https://kit.fontawesome.com/b68f3ac064.js" crossorigin="anonymous"></script>

	<script src="js/sweetalert2.all.js"></script>
	<link rel="stylesheet" href="css/sweetalert2.css" />

	<link rel="stylesheet" href="css/notific.css" />
	<script src="js/notific.js"></script>
	
	<link rel="shortcut icon" type="image/png" href="img/icon.png" />
	<link href="css/style.css?r='.$ran.'" rel="stylesheet" type="text/css" media="all" />

	
	<style>
        .modal-dialog{
            z-index: 100002;
        }
        .modal-backdrop{
            z-index: 100000;
        }
        .modal {
            z-index: 100001;
		}

		.swal2-container {
			z-index: 100002;
		}

		.swal2-popup {
			z-index: 100003;
		}

	</style>	
';	
}

function getFooter(){
echo '

<div class="container pt-2">
	<div id="show_error" style="color:#333;"></div>
</div>	

<div class="background"></div>
<div class="loading">
	<div class="loader">Loading...</div>
</div>

<script>
	$(function(){
		$(\'a[href="#"]\').click(function(){
			event.preventDefault();
		}); 

		$(\'a[href]:not([href="#"]\').click(function(){
			myGreeting();
			const myTimeout = setTimeout(myStopFunction, 2000);
		}); 

	});
	function myGreeting() {
		$(".background").fadeIn();
		$(".loading").fadeIn();
		$(".loader").fadeIn();
	}
	function myStopFunction() {
		$(".background").hide();
		$(".loading").hide();
		$(".loader").hide();
	}
	
	function getMobileOperatingSystem() {
		var userAgent = navigator.userAgent || navigator.vendor || window.opera;
		  if (/windows phone/i.test(userAgent)) {
			 return "Windows Phone";
		}
		  if (/android/i.test(userAgent)) {
			return "Android";
		}
		  if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
			return "iOS";
		}
		  return "unknown";
	}
	
</script>
';
}

function chkLogin(){
	if(!isset($_COOKIE['user_id'])){
		echo '<script> location.href = "login.php"; </script>';
	}
}
function getCopyRight(){
echo '
<small>
Copy right @ <br>
Chakkraphan Phetphum, Phatsavee Ongruk,<br>
Orawan Keeratisiroj & Tobacco Control Research <br>
and Knowledge Management Center,<br>
(TRC.)
</small>
';
}


function getTextTime($date){
	if($date!=""){
		return substr($date,-8,5);
	}
	else{
		return "";
	}
}
function getTextDate($date,$str){
	if($date!=""){
		list($year, $month, $day)= explode("-",$date);
		$year = intval($year)+543;
		$day = substr($day,0,2);
		if(substr($day,0,1)=='0'){
			$day = substr($day,1);
		}
		$newdate = $day.$str.$month.$str.$year ;
		return $newdate;
	}
	else{
		return "";
	}
}
function getTextDateIos($date){
	if($date!=""){
		list($year, $month, $day)= explode("-",$date);
		$time = getTextTime($date);
		$day = substr($day,0,2);
		return $year.'-'.$month.'-'.$day.'T'.$time.':00';
	}
	else{
		return "";
	}
}
function getTextDateFull($date){
	if($date!=""){
		list($year, $month, $day)= explode("-",$date);
		$year = substr(intval($year)+543,-2);
		$day = substr($day,0,2);
		if(substr($day,0,1)=='0'){
			$day = substr($day,1);
		}
		$newdate = $day ." ". getMonthUpdate($month)." ".$year ;
		return $newdate;
	}
	else{
		return "";
	}

}
function getTextDateTH($date){
	if($date!=""){
		list($year, $month, $day)= explode("-",$date);
		$year = substr(intval($year)+543,-2);
		$day = substr($day,0,2);
		if(substr($day,0,1)=='0'){
			$day = substr($day,1);
		}
		$newdate = $day ." ". getMonthUpdate($month)." ".$year ;
		return $newdate;
	}
	else{
		return "";
	}

}
function getMonthUpdate($mou){
	if($mou=="01"){
		$mou = "ม.ค.";
	}
	else if($mou=="02"){
		$mou = "ก.พ.";
	}
	else if($mou=="03"){
		$mou = "มี.ค.";
	}
	else if($mou=="04"){
		$mou = "เม.ย.";
	}
	else if($mou=="05"){
		$mou = "พ.ค.";
	}
	else if($mou=="06"){
		$mou = "มี.ย.";
	}
	else if($mou=="07"){
		$mou = "ก.ค.";
	}
	else if($mou=="08"){
		$mou = "ส.ค.";
	}
	else if($mou=="09"){
		$mou = "ก.ย.";
	}
	else if($mou=="10"){
		$mou = "ต.ค.";
	}
	else if($mou=="11"){
		$mou = "พ.ย.";
	}
	else if($mou=="12"){
		$mou = "ธ.ค.";
	}
	return $mou;
}


function chkMission1($user_id,$query){
	$chk1 = intval($query->queryDB_to_string('SELECT COUNT(user_id) FROM healthinfo WHERE user_id = '.$user_id));
	$chk2 = intval($query->queryDB_to_string('SELECT COUNT(user_id) FROM pressure WHERE user_id = '.$user_id));
	if($chk1>0 && $chk2>0){
		return true;
	}
	else{
		return false;
	}
}
function chkMission2($user_id,$query){
	$chk1 = intval($query->queryDB_to_string('SELECT COUNT(DISTINCT date) FROM view WHERE user_id = '.$user_id.' AND type="book"'));
	$chk2 = intval($query->queryDB_to_string('SELECT COUNT(DISTINCT date) FROM sodium_record WHERE user_id = '.$user_id));
	if($chk1>=3 && $chk2>=3){
		return true;
	}
	else{
		return false;
	}
}
function chkMission3($user_id,$query){
	$chk = intval($query->queryDB_to_string('SELECT COUNT(id) FROM view WHERE user_id = '.$user_id.' AND type="video"'));
	if($chk>2){
		return true;
	}
	else{
		return false;
	}
}
function chkMission4($user_id,$query){
	$chk = chkMission1($user_id,$query) + chkMission2($user_id,$query) + chkMission3($user_id,$query);
	if($chk==3){
		return true;
	}
	else{
		return false;
	}
}

?>
