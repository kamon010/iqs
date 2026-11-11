<?php
require_once __DIR__ . '/../indyEngine/dbControl.php';
require_once __DIR__ . '/../indyEngine/sessionControl.php';

class HtmlCore{
	
	private static function getMenuActive($page, $activeName){
		if ($page == $activeName) {
			return 'class="active"';
		}

		return '';
	}
	
	private static function getMenuDropdownActive($page, $activeName){
		if ($page == $activeName) {
			return ' active';
		}

		return '';
	}
	private static function getTreeMenuActive($page, $activeName){
		if (!is_array($activeName)) {
			$activeName = array($activeName);
		}

		for ($a = 0; $a < count($activeName); $a++) {
			if ($page == $activeName[$a]) {
				return 'active';
			}
		}

		return '';
	}

	private static function getTreeMenusActive($page, $activeName){
		if (!is_array($activeName)) {
			$activeName = array($activeName);
		}

		for ($a = 0; $a < count($activeName); $a++) {
			if ($page == $activeName[$a]) {
				return 'style="display: block;"';
			}
		}

		return '';
	}
	
	public static function getTitle(){
		echo '<title>  :: TRC Admin :: </title>';
	}
	
	public static function getTextHeader()
	{
		echo '
			<a href="index.php" class="logo">
				<span class="logo-mini" style="font-size:18px; font-family: \'Kanit\', sans-serif;"><img src="../img/icon.png" height="40px"> TRC Admin <b></b></span>
				<span class="logo-lg" style="font-size:16px; font-family: \'Kanit\', sans-serif;"><img src="../img/icon.png" height="40px"> TRC Admin <b></b></span>
			</a>
		';
	}
	public static function getMenuHeader()
	{
		$user_id = intval(SessionControl::getUserID());

		if ($user_id <= 0) {
			return;
		}

		$query = new DBControl();
		$sql = "SELECT * FROM user WHERE user_id = " . $user_id;
		$user_data = $query->queryDB_to_row($sql);

		if (!is_array($user_data)) {
			$user_data = array();
		}

		$user_name = isset($user_data['user_name']) ? $user_data['user_name'] : '';
		$user_lastname = isset($user_data['user_lastname']) ? $user_data['user_lastname'] : '';

		echo'
		<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
		<div class="navbar-custom-menu">
			
			
            <ul class="nav navbar-nav">
				
				
				
              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                  <img src="images/avatar5.png" class="user-image" alt="User Image">
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs" style="font-family: \'Prompt\', sans-serif;">'.$user_name.' '.$user_lastname.'</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <li class="user-header">
					 <img src="images/avatar5.png" class="img-circle" alt="User Image">
                    <p style="font-family: \'Prompt\', sans-serif;">
						admin<br>
						'.$user_name.' '.$user_lastname.'
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="user_edit.php?id='.$user_id.'" class="btn btn-default btn-flat">ข้อมูลผู้ใช้</a>
                    </div>
                    <div class="pull-right">
                      <a href="indyEngine/sessionControl.php?logout=true" class="btn btn-default btn-flat">ลงชื่อออก</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
		</div>';
	}
	
	public static function getMenuFooter(){
		echo '<div class="pull-right hidden-xs">
				 <strong>ผู้ดูแลระบบ</strong></a>
				</div>
				<!-- Default to the left -->
				<strong> 
					<a href="#" style="color:##4682B4">
						 มหาวิทยาลัยนเรศวร
				 	</a> 
					<div id="show_error"></div> 
				 </strong>';
	}
	
	public static function getMenu($page){
		$user_id = intval($_COOKIE["user"] ?? 0);
		$query = new DBControl();

		$user_type = '';
		if ($user_id > 0) {
			$sql = "SELECT user_type FROM user WHERE user_id = " . $user_id;
			$user_type = $query->queryDB_to_string($sql);
		}

		echo '<ul class="sidebar-menu">
				<!-- Optionally, you can add icons to the links -->
		';
				if($user_type=="admin"){
					echo '
					<li '.self::getMenuActive($page,"index").'><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li '.self::getMenuActive($page,"youth_survey_report").'><a href="youth_survey_report.php"><i class="fa fa-list-alt"></i> <span>แบบสอบถามเด็กและเยาวชน</span></a></li>
					
					<li class="treeview '.self::getTreeMenuActive($page,array('report_a','report_b','report_c','report_d','report_e','report_1','report_2','report_3','report_4','report_5')).'">
						<a href="#"><i class="fa fas fa-file"></i> <span>รายงานข้อมูลสมาชิก</span> <i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu">
							<li '.self::getMenuActive($page,"report_a").'><a href="report_a.php"><i class="fa fa-caret-right"></i>ข้อมูลสมาชิก</a></li>
							<li class="treeview" style="height: auto;">
								<a href="#"><i class="fa fa-caret-right"></i> 
									<span class="pull-right-container"> คำถามคัดกรอง
									<i class="fa fa-angle-left pull-right"></i> </span>
								</a>
								<ul class="treeview-menu" '.self::getTreeMenusActive($page,array('report_1','report_2','report_3','report_4','report_e')).'">
									<li '.self::getMenuActive($page,"report_1").'><a href="report_1.php"><i class="fa fa-caret-right"></i>สูบบุหรี่มวนสลับกับบุหรี่ไฟฟ้า</a></li>
									<li '.self::getMenuActive($page,"report_2").'><a href="report_2.php"><i class="fa fa-caret-right"></i>สูบบุหรี่มวนอย่างเดียว</a></li>
									<li '.self::getMenuActive($page,"report_3").'><a href="report_3.php"><i class="fa fa-caret-right"></i>สูบบุหรี่ไฟฟ้าอย่างเดียว</a></li>
									<li '.self::getMenuActive($page,"report_4").'><a href="report_4.php"><i class="fa fa-caret-right"></i>ไม่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า</a></li>
									<li '.self::getMenuActive($page,"report_e").'><a href="report_e.php"><i class="fa fa-caret-right"></i>การเลิกบุหรี่</a></li>
								</ul>
							</li>
						</ul>
					</li>

					<li '.self::getMenuActive($page,"ผลกระทบต่อสุขภาพ").'><a href="page.php?type=ผลกระทบต่อสุขภาพ"><i class="fa fa-book"></i> <span>ผลกระทบต่อสุขภาพ</span></a></li>
					<li '.self::getMenuActive($page,"กฎหมายที่ควรรู้เกี่ยวกับบุหรี่").'><a href="page.php?type=กฎหมายที่ควรรู้เกี่ยวกับบุหรี่"><i class="fa fa-book"></i> <span>กฎหมายที่ควรรู้เกี่ยวกับบุหรี่</span></a></li>
					<li '.self::getMenuActive($page,"สมุนไพรช่วยเลิกบุหรี่").'><a href="page.php?type=สมุนไพรช่วยเลิกบุหรี่"><i class="fa fa-book"></i> <span>สมุนไพรช่วยเลิกบุหรี่</span></a></li>
					<li '.self::getMenuActive($page,"แบนเนอร์").'><a href="picture.php?type=แบนเนอร์"><i class="fa fa-picture-o"></i> <span>แบนเนอร์</span></a></li>
					<li '.self::getMenuActive($page,"e-learning").'><a href="e-learning.php"><i class="fa fa-video-camera"></i> <span>e-learning</span></a></li>
					<li '.self::getMenuActive($page,"user_data").'><a href="user_data.php"><i class="fa fa-user"></i> <span>ข้อมูลสมาชิก</span></a></li>
					<li '.self::getMenuActive($page,"admin_data").'><a href="admin_data.php"><i class="fa fa-vcard"></i> <span>ผู้ดูแลระบบ</span></a></li>
					<li '.self::getMenuActive($page,"university").'><a href="university.php"><i class="fa fa-bank"></i> <span>มหาวิทยาลัย</span></a></li>
					
					';
				}
				
		echo '
			</ul>
		';
	}
	
}

	function cutPic($picture){
		if($picture!=''){
			$start = strpos($picture,'style=',0);
			if($start){
				$picture = substr($picture,0,$start).' />';
			}
			
			$start = strpos($picture,'src="',0);
			$end = strpos($picture,'"',$start+5);
				
			return addslashes(substr($picture,$start+5,($end-$start)-5));
		}
		else{
			return 'images/nopic.png';
		}
	}
	function cutPics($picture){
		if($picture!=''){
			$pictures = '';
			while(strpos($picture,'src="',0)){

				$start = strpos($picture,'src="',0);
				$end = strpos($picture,'"',$start+5);
				
				$pictures = $pictures.addslashes(substr($picture,$start+5,($end-$start)-5)).'][';
				$picture = cutText($picture,'src=');
			}
			return $pictures;	
		}
		else{
			return 'images/nopic.png';
		}
	}
	function showPic($picture){
		//$files = glob("/*.php", GLOB_BRACE);
		//var_dump($files);
		if($picture!=''){
			//if(glob("../uploads/images/1536045300.png")){
			if(!strpos($picture,'style=',0)){
				return $picture;
			}
			else{
				return 'images/nopic.png';
			}
		}
		
		else{
			return 'images/nopic.png';
		}
	}
	
	function cutText($text,$str){
		$lenText = strlen($text);
		$lenStr = strlen($str);
		$posStr = strpos($text,$str,0);
		return substr($text,-1*($lenText-($lenStr+$posStr)));
	}
	
	function getTextTime($date){
		if($date!=""){
			return substr($date,-8,5);
		}
		else{
			return "";
		}
	}
	function getTextDateTime($date){
		if($date!=""){
			list($year, $month, $day)= explode("-",$date);
			$time = substr($date,-8,5);
			$year = substr(intval($year)+543,-2);
			$day = substr($day,0,2);
			if(substr($day,0,1)=='0'){
				$day = substr($day,1);
			}
			$newdate = $day ." ". getMonthShot($month)." ".$year." <small>".$time." น</small>" ;
			return $newdate;
		}
		else{
			return "";
		}

	}			
	function getTextDateTH($date){
		if ($date == '') {
			return '';
		}

		$parts = explode('-', $date);
		if (count($parts) < 3) {
			return '';
		}

		list($year, $month, $day) = $parts;
		$year = intval($year) + 543;
		$day = substr($day, 0, 2);

		if (substr($day, 0, 1) == '0') {
			$day = substr($day, 1);
		}

		$newdate = $day . " " . getMonthShot($month) . " " . $year;
		return $newdate;
	}
	function getMonthFull($mou){
		if($mou=="01"){
			$mou = "มกราคม";
		}
		else if($mou=="02"){
			$mou = "กุมภาพันธ์";
		}
		else if($mou=="03"){
			$mou = "มีนาคม";
		}
		else if($mou=="04"){
			$mou = "เมษายน";
		}
		else if($mou=="05"){
			$mou = "พฤษภาคม";
		}
		else if($mou=="06"){
			$mou = "มีถุนายน";
		}
		else if($mou=="07"){
			$mou = "กรกฎาคม";
		}
		else if($mou=="08"){
			$mou = "สิงหาคม";
		}
		else if($mou=="09"){
			$mou = "กันยายน";
		}
		else if($mou=="10"){
			$mou = "ตุลาคม";
		}
		else if($mou=="11"){
			$mou = "พฤศจิกายน";
		}
		else if($mou=="12"){
			$mou = "ธันวาคม";
		}
		return $mou;
	}
	function getMonthShot($mou){
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
	function getPageURL() {
		$https = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on";
		$pageURL = $https ? 'https' : 'http';
		$pageURL .= "://";

		$serverName = $_SERVER["SERVER_NAME"] ?? 'localhost';
		$serverPort = $_SERVER["SERVER_PORT"] ?? '80';
		$requestUri = $_SERVER["REQUEST_URI"] ?? '';

		if ($serverPort != "80") {
			$pageURL .= $serverName . ":" . $serverPort . $requestUri;
		} else {
			$pageURL .= $serverName . $requestUri;
		}

		return $pageURL;
	}
