<?
include_once('dbControl.php');
include_once('dbStructure.php');
$query = new DBControl();

if($_POST['type']=='check_form'){
	$values = array_values(array_slice($_POST,2));
	$keys = array_keys(array_slice($_POST,2));
	$arrayKey = array();
	foreach($keys as $key => $value){
		$table_name = substr($value,0,strpos($value,"@"));
		$field_name = substr($value,strpos($value,"@")+1 );
		if($field_name == DBStructure::getKey($table_name)){
			$arrayKey[$table_name] = $values[$key];
		}
	}
	
	$chk_array = explode(",",substr($_POST['chk_attr'],0,-1));
	$table = array();
	$fields = array();
	$from = array();
	$input_name = array();
	foreach($chk_array as $key => $value){
		$tables[$key] = substr($value,0,strpos($value,"@"));
		$var = substr($value,strpos($value,"@")+1 );
		$fields[$key] = substr($var,0,strpos($var,"="));
		
		$input_name[$key] = substr($value,0,strpos($value,"="));
		$from[$key] = substr($value,strpos($value,"=")+1 );
	}
	
	$input_name = array_reverse($input_name);
	$from = array_reverse($from);
	$fields = array_reverse($fields);
	$lastCheck = '';
	$valueInput = '';
	foreach($input_name as $key => $value){
		$index = array_search($value,$keys);
		$valueInput = $values[$index];
		$chkFrom = $from[$key];
		
		$chk_array = explode(" ",$chkFrom);
		$chk_array = array_reverse($chk_array);
		$error = 0;
		$str_alert = '';
		foreach($chk_array as $key2 => $value2){
			if($chk_array[$key2]=='notNULL'){
				$pass = checkNotNull($valueInput);
				if(!$pass){
					$str_alert = 'กรุณากรอกข้อมูลให้ครบ';
				}
			}
			else if($chk_array[$key2]=='numeric'){
				$pass = checkNumeric($valueInput);
				if(!$pass){
					$str_alert = 'ข้อมูลต้องเป็นตัวเลข';
				}
			}
			else if($chk_array[$key2]=='email'){
				$pass = checkEmail($valueInput);
				if(!$pass){
					$str_alert = 'ข้อมูลต้องรูปแบบ email';
				}
			}
			else if($chk_array[$key2]=='notSameDB'){
				$pass = checkNotSame($tables[$key],$fields[$key],$valueInput,$arrayKey);
				if(!$pass){
					$str_alert = 'ข้อมูลนี้มีอยู่แล้ว';
				}
			}
			$error += !$pass;
		}
		if($error>0){
			getHtmlFalseCondition($input_name[$key],$str_alert);
			$lastCheck = $input_name[$key];
		}
		else{
			getHtmlTrueCondition($input_name[$key]);
		}
	}
	if($lastCheck){
		setFocus($lastCheck);
	}
	else{
		echo '<script> queryDB_form(); </script>';
	}
}
else if($_POST['type']=='insert_form'){
	$values = array_values(array_slice($_POST,2));
	$keys = array_keys(array_slice($_POST,2));
	
	$tables = array();
	$field = array();
	foreach($keys as $key => $value){
		$tables[$key] = substr($value,0,strpos($value,"@"));
		$field[$key] = substr($value,strpos($value,"@")+1 );
	}
	$table = array_unique($tables);
	
	if(sizeof($table)==1){
		$primaryKey = DBStructure::getKey($table[0]);
		if(DBStructure::checkAI($table[0]) && !in_array($primaryKey,$new_field)){
			array_unshift($field,$primaryKey);
			array_unshift($values,'');
		}
		$chk = $query->insertDB_array($table[0],$field,$values);
	}
	else{
		$new_tables = array();
		foreach($table as $key => $value){
			if(!in_array($value,$new_tables)){
				$new_tables[$value] = array();
			}
		}
		foreach($new_tables as $tkey => $tvalue){
			foreach($keys as $key => $value){
				if($tkey==$tables[$key]){
					$new_tables[$tkey][$field[$key]]=$values[$key];
				}
			}
		}
		
		foreach($new_tables as $tkey => $tvalue){
			$new_field = array();
			$new_value = array();
			foreach($tvalue as $key => $value){
				array_push($new_field,$key);
				array_push($new_value,$value);
			}

			$primaryKey = DBStructure::getKey($tkey);
			if(DBStructure::checkAI($tkey) && !in_array($primaryKey,$new_field)){
				array_unshift($new_field,$primaryKey);
				array_unshift($new_value,'');
			}
			$chk = $query->insertDB_array($tkey,$new_field,$new_value);
		}
	}
	if($chk==1){
		echo '<script> $().toastmessage("showSuccessToast", "บันทึกข้อมูลเรียบร้อยแล้ว"); </script>';
		echo '<script> window.setInterval("window.location.href = \''.$_POST['path_refresh'].'\'; ", 1000); </script>';
	}
}
else if($_POST['type']=='update_form'){
	$values = array_values(array_slice($_POST,2));
	$keys = array_keys(array_slice($_POST,2));
	
	$tables = array();
	$fields = array();
	foreach($keys as $key => $value){
		$tables[$key] = substr($value,0,strpos($value,"@"));
		$fields[$key] = substr($value,strpos($value,"@")+1 );
	}
	$tables = array_unique($tables);
	foreach($tables as $index => $val){	
		$update_text = '';
		$keyUpdate = '';
		$primaryKey = DBStructure::getKey($tables[$index]);
		$array_primaryKey = array();
		$array_valueKey = array();
		if(strpos($primaryKey,',') !== false) {
			$array_primaryKey = explode(",",$primaryKey);
		}
		foreach($fields as $key => $value){
			if($fields[$key] == $primaryKey){
				$keyUpdate = $values[$key];
			}
			else if(in_array($fields[$key],$array_primaryKey)){
				array_push($array_valueKey,$values[$key]);
			}
			else{
				$update_text .= $fields[$key]."='".$values[$key]."',";
			}
		}
		$update_text = substr($update_text,0,-1);
		
		if(count($array_primaryKey) > 1){
			if(count($array_primaryKey) == count($array_valueKey)){
				$update_text .= $fields[$key]."='".$values[$key]."',";
				$sql = "UPDATE ".$tables[$index]." SET ".$update_text." WHERE ";
				for($a=0;$a<count($array_primaryKey);$a++){
					$str_and = "";
					if($a > 0){
						$str_and = " AND ";
					}
					$sql .= $str_and.$array_primaryKey[$a]." = '".$array_valueKey[$a]."'";
				}	
			}
			else{
				echo '<script> alert("จำนวนของ primaryKey ต้องเป็นไปตามเงื่อนไขใน Database Structure"); </script>';
			}
		}
		else{	
			$sql = 'UPDATE '.$tables[$index].' SET '.$update_text.' WHERE '.$primaryKey." = '".$keyUpdate."'";
		}
		$chk = $query->queryDB($sql);
	}
	if($chk==1){
		echo '<script> $().toastmessage("showSuccessToast", "แก้ไขข้อมูลเรียบร้อยแล้ว"); </script>';
		echo '<script> window.setInterval("window.location.href = \''.$_POST['path_refresh'].'\'; ", 1000); </script>';
	}
}
else if($_POST['type']=='update_form_rows'){
	$values = array_values(array_slice($_POST,2));
	$names = array_keys(array_slice($_POST,2));
	
	$tables = array();
	$fields = array();
	$keys = array();
	foreach($names as $key => $value){
		$tables[$key] = substr($value,0,strpos($value,"@"));
		$fieldkey = substr($value,strpos($value,"@")+1 );
		$fields[$key] = substr($fieldkey,0,strpos($fieldkey,"{"));
		$keys[$key] = substr($fieldkey,strpos($fieldkey,"{")+1,-1);
	}
	$tables = array_unique($tables);
	foreach($tables as $index => $val){
		$update_text = '';
		$primaryKey = DBStructure::getKey($tables[$index]);
		$array_primaryKey = array();
		$array_primaryKey = explode(",",$primaryKey);
		foreach($fields as $key => $value){
			$keyUpdate = $keys[$key];
			$update_text = $fields[$key]."='".$values[$key]."'";
			$sql = '';
			if(count($array_primaryKey) > 0){
				$array_key = explode(",",$keyUpdate);
				if(count($array_primaryKey) == count($array_key)){
					$sql = "UPDATE ".$tables[$index]." SET ".$update_text." WHERE ";
					for($a=0;$a<count($array_primaryKey);$a++){
						$str_and = "";
						if($a > 0){
							$str_and = " AND ";
						}
						$sql .= $str_and.$array_primaryKey[$a]." = '".$array_key[$a]."'";
					}	
				}
				else{
					echo '<script> alert("จำนวนของ primaryKey ต้องเป็นไปตามเงื่อนไขใน DBStructure "); </script>';
				}
			}
			//echo $sql.'<br>';
			$chk = $query->queryDB($sql);
		}
		
	}
	if($chk==1 && $_POST['path_refresh'] != ''){
		echo '<script> $().toastmessage("showSuccessToast", "แก้ไขข้อมูลเรียบร้อยแล้ว"); </script>';
		echo '<script> window.setInterval("window.location.href = \''.$_POST['path_refresh'].'\'; ", 1000); </script>';
	}
}

else if($_POST['type']=='insertDB'){
	$table = $_POST['table'];
	$fields = $_POST['fields'];
	$values = $_POST['values'];
	$primaryKey = DBStructure::getKey($table);
	
	$values = str_replace("{{indy_schar}}","&",$values);
	
	$array_fields = explode(",",$fields);
	$array_values = explode(",",$values);
	
	if(count($array_values)>1){
		$chk = $query->insertDB_array($table,$array_fields,$array_values);
	}
	else{
		$chk = $query->insertDB($table,$fields,$values);
	}

	if($chk==1){
		if($_POST['path_refresh']!='noting'){	
			echo '<script> $().toastmessage("showSuccessToast", "เพิ่มข้อมูลเรียบร้อยแล้ว"); </script>';
			if(!empty($_POST['path_refresh'])){
				echo '<script> window.setInterval("window.location.href = \''.$_POST['path_refresh'].'\'; ", 1000); </script>';
			}
		}
	}
}
else if($_POST['type']=='insertDBMultitable'){
	$table = $_POST['table'];
	$fields = $_POST['fields'];
	$values = $_POST['values'];
	$primaryKey = DBStructure::getKey($table);
	
	$array_table = explode(",",$table);
	$array_fields = explode("$",$fields);
	$array_values = explode("$",$values);
	
	if(count($array_fields) == count($array_table) && count($array_values) == count($array_table)){
		for($a = 0;$a < count($array_table);$a++){
			//echo $array_table[$a].$array_fields[$a].$array_values[$a].'<br>';
			$chk = $query->insertDBMultiValue($array_table[$a],$array_fields[$a],$array_values[$a]);
		}
		
		if($chk==1){
			if($_POST['path_refresh']!=''){	
				echo '<script> $().toastmessage("showSuccessToast", "เพิ่มข้อมูลเรียบร้อยแล้ว"); </script>';
				if(!empty($_POST['path_refresh'])){
					echo '<script> window.setInterval("window.location.href = \''.$_POST['path_refresh'].'\'; ", 1000); </script>';
				}
			}
		}
	}
	
}
else if($_POST['type']=='queryDB'){
	$sql = $_POST['sql'];
	$chk = $query->queryDB($sql);
	if($chk==1){
		if($_POST['path_refresh']!='noting'){	
			echo '<script> $().toastmessage("showSuccessToast", "อัพเดทข้อมูลนี้เรียบร้อยแล้ว"); </script>';
			if(!empty($_POST['path_refresh'])){
				echo '<script> window.setInterval("window.location.href = \''.$_POST['path_refresh'].'\'; ", 1000); </script>';
			}
		}
	}
}
else if($_POST['type']=='deleteDB'){
	$table = $_POST['table'];
	$key = $_POST['key'];
	$chk = deleteDB_recursive($table,$key);
	if($chk==1){
		if($_POST['path_refresh']!='noting'){	
			echo '<script> $().toastmessage("showSuccessToast", "ลบข้อมูลนี้เรียบร้อยแล้ว"); </script>';
			if(!empty($_POST['path_refresh'])){
				echo '<script> window.setInterval("window.location.href = \''.$_POST['path_refresh'].'\'; ", 1000); </script>';
			}
		}
	}
}
else if($_POST['type']=='updateDB'){
	$table = $_POST['table'];
	$key = $_POST['key'];
	$field = $_POST['field'];
	$value = $_POST['value'];
	$primaryKey = DBStructure::getKey($table);
	
	$value = str_replace("{{indy_schar}}","&",$value);
	
	$array_primaryKey = explode(",",$primaryKey);
	$array_keys = explode(",",$key);
	
	
	$chk = 0;
	if(count($array_primaryKey) == count($array_keys)){
		$chk = $query->updateDB($table,$primaryKey,$key,$field,$value);
	}
	else{
		echo '<script> alert("จำนวนของ primaryKey ต้องเป็นไปตามเงื่อนไขใน DBStructure "); </script>';
	}
	
	if($chk==1){
		if($_POST['path_refresh']!='noting'){	
			echo '<script> $().toastmessage("showSuccessToast", "แก้ไขข้อมูลเรียบร้อยแล้ว"); </script>';
			if(!empty($_POST['path_refresh'])){
				echo '<script> window.setInterval("window.location.href = \''.$_POST['path_refresh'].'\'; ", 1000); </script>';
			}
		}
	}
}
else if($_POST['type']=='updateDB_checksame'){
	$table = $_POST['table'];
	$key = $_POST['key'];
	$field = $_POST['field'];
	$value = $_POST['value'];
	$primaryKey = DBStructure::getKey($table);
	
	$value = str_replace("{{indy_schar}}","&",$value);
	
	$count = $query->queryDB_num_row("SELECT ".$primaryKey." FROM ".$table." WHERE ".$primaryKey."='".$key."'");
	if($count>0){	
		$chk = $query->updateDB($table,$primaryKey,$key,$field,$value);
		if($chk==1){
			if($_POST['path_refresh']!=''){	
				echo '<script> $().toastmessage("showSuccessToast", "แก้ไขข้อมูลเรียบร้อยแล้ว"); </script>';
				if(!empty($_POST['path_refresh'])){
					echo '<script> window.setInterval("window.location.href = \''.$_POST['path_refresh'].'\'; ", 1000); </script>';
				}
			}
		}
	}
	else{
		$array_field = array($primaryKey,$field);
		$array_value = array($key,$value);
		$chk = $query->insertDB_array($table,$array_field,$array_value);
		if($chk==1){
			if($_POST['path_refresh']!=''){	
				echo '<script> $().toastmessage("showSuccessToast", "แก้ไขข้อมูลเรียบร้อยแล้ว"); </script>';
				if(!empty($_POST['path_refresh'])){
					echo '<script> window.setInterval("window.location.href = \''.$_POST['path_refresh'].'\'; ", 1000); </script>';
				}
			}
		}
	}
}
$query->closeDB();

function deleteDB_recursive($table,$key,$ref_field=''){
	$query = new DBControl();
	if($ref_field == ''){
		$primaryKey = DBStructure::getKey($table);
		$chk = $query->deleteDB($table,$primaryKey.' = "'.$key.'"');
	}
	else{
		$chk = $query->deleteDB($table,$ref_field.' = "'.$key.'"');
	}
	if(DBStructure::getRefkey($table)){
		$ref_data = DBStructure::getRefkey($table);
		$array_ref_data = explode(",",$ref_data);
		foreach($array_ref_data as $ref_value){
			$ref_table = substr($ref_value,0,strpos($ref_value,"@"));
			$ref_field = substr($ref_value,strpos($ref_value,"@")+1 );
			deleteDB_recursive($ref_table,$key,$ref_field);
		}
	}
	if($chk==1){
		return true;
	}else return false;
	
}

function setFocus($name){
	echo '
		<script>
			$("input[name=\''.$name.'\']").focus();
		</script>
	';
}
function getHtmlFalseCondition($name,$word){
	echo '
		<script>
			$("input[name=\''.$name.'\']").addClass("border-red");
			$("label[name=\''.$name.'\']").html("'.$word.'").addClass("color-red");
		</script>
	';
}
function getHtmlTrueCondition($name){
	echo '
		<script>
			$("input[name=\''.$name.'\']").focusout();
			$("input[name=\''.$name.'\']").removeClass("border-red");
			$("label[name=\''.$name.'\']").html("").removeClass("color-red");
		</script>
	';
}

function checkNotNull($value){
	if($value!=''){
		return true;
	}
	else return false;
}
function checkNumeric($value){
	if(is_numeric($value)){
		return true;
	}
	else return false;
}
function checkEmail($value){
	if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $value)) {
		return true;
	}
	else return false;
}
function checkNotSame($tabla_name,$fields,$value,$arrayKey){
	$query = new DBControl();
	if(!empty($arrayKey[$tabla_name])){
		$sql = 'SELECT '.$fields.' FROM '.$tabla_name.' WHERE '.DBStructure::getKey($tabla_name).'="'.$arrayKey[$tabla_name].'"';
		$valueDB = $query->queryDB_to_string($sql);
		if($valueDB!=$value){
			$sql = 'SELECT COUNT('.$fields.') FROM '.$tabla_name.' WHERE '.$fields.'="'.$value.'"';
		}
		else return true;
	}
	else{
		$sql = 'SELECT COUNT('.$fields.') FROM '.$tabla_name.' WHERE '.$fields.'="'.$value.'"';
	}
	$num = $query->queryDB_to_string($sql);
	if($num==0){
		return true;
	}
	else return false;
}
?>