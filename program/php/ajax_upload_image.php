<?php
ob_start();
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$path = "../uploads/images/";

$valid_formats = array("jpg","jpeg", "png", "gif", "bmp","JPG","JPEG", "PNG", "GIF", "BMP","svg","SVG");
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
	$name = $_FILES['file']['name'];
	$size = $_FILES['file']['size'];
	
	$name = str_replace('_','',$name);
	$name = str_replace('(','',$name);
	$name = str_replace(')','',$name);
	$name = str_replace(' ','',$name);
	
	if(strlen($name)){
		list($txt, $ext) = explode(".", $name);
		if(in_array($ext,$valid_formats)){
			if($size<(1024*1024*20)){
				$new_name = time();
				$actual_image_name = $new_name.".".$ext;
				$tmp = $_FILES['file']['tmp_name'];
				if(move_uploaded_file($tmp, $path.$actual_image_name)){
					chmod($path.$actual_image_name,0777);
					echo '
						<script>
							$("#img").val("uploads/images/'.$actual_image_name.'");
							$("#upload").attr("src","uploads/images/'.$actual_image_name.'");
						</script>
					';
					if($_GET['type']=='edit'){
						$user_id = $_COOKIE["user_id"];
						$sql = 'UPDATE user SET user_img = "uploads/images/'.$actual_image_name.'" WHERE user_id ='.$user_id;
						$query->queryDB($sql);
					}
					else if($_GET['type']=='other'){
						$user_id = $_COOKIE["user_id"];
						$sql = 'UPDATE user SET user_other_img = "uploads/images/'.$actual_image_name.'" WHERE user_id ='.$user_id;
						$query->queryDB($sql);
					}
				}
				else{
					echo "failed";
				}
			}
			else{
				echo "Image file size max 20 MB";					
			}
		}
		else{
			echo "Invalid file format..";	
		}
	}	
	else{
		echo "Please select image..!";
	}
	exit;
}

?>