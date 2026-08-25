<?
class HtmlControl{
	//return part แบบมี hostname
	public static function getHostName(){
		//return $host = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."/";
		//$_SERVER['REQUEST_URI']
	}

	/*
		Helper สำหรับ PHP 8+
		ป้องกัน Fatal error เวลาใช้ count() กับ string/null
	*/
	private static function safeCount($value){
		if (is_array($value) || $value instanceof Countable) {
			return count($value);
		}
		return 0;
	}

	/*
		Helper สำหรับ PHP 8+
		ป้องกัน array_key_exists() กับตัวแปรที่ไม่ใช่ array
	*/
	private static function hasKey($key, $array){
		return is_array($array) && array_key_exists($key, $array);
	}

	/*
		Helper สำหรับอ่านค่า array แบบไม่ให้เกิด Undefined array key
	*/
	private static function arrGet($array, $key, $default = ''){
		if (is_array($array) && array_key_exists($key, $array)) {
			return $array[$key];
		}
		return $default;
	}

	/* 	------------------------------
			    ### getImage ###
		------------------------------
		@Description >> return html tag รูปภาพ 
		@Output >> <img scr="[part]" id="[id]" class="[class]" css="[scc]">
		@Parameters >> getImage(array $input);
		@Input >> 
		
		HtmlControl::getImage( array(
			'part' => 'images/logo.png',
			'id' => 'image',
			'class' => 'no-border',
			'css' => ' margin=10px; ',
		));
		
		## OR ##
		HtmlControl::getImage(array('images/logo.png');
		
		## OR ##
		getImage(array('images/logo.png','css' => ' margin=10px; '));
		
		------------------------------
	*/
	public static function getImage($input){
		$str = '';
		$part = self::getArrayValue(self::arrGet($input, 'part'), self::arrGet($input, 0));
		$id = self::getArrayValue(self::arrGet($input, 'id'), self::arrGet($input, 1));
		$class = self::getArrayValue(self::arrGet($input, 'class'), self::arrGet($input, 2));
		$css = self::getArrayValue(self::arrGet($input, 'css'), self::arrGet($input, 3));

		if ($part !== '') {
			$str .= '<img src="'.HtmlControl::getHostName().$part.'" ';
		}
		if ($id !== '') {
			$str .= 'id="'.$id.'" ';
		}
		if ($class !== '') {
			$str .= 'class="'.$class.'" ';
		}
		if ($css !== '') {
			$str .= 'style="'.$css.'" ';
		}
		$str .= '>';
		return $str;
	}



	//return html tag link
	/*	
		getLink( array(
			'text' => 'aaa',
			'part' => 'index.php',
			'onClick' => 'reset()',
		));
		
		getLink(array('aaa'));
		
		getLink(array('aaa','index.php'));
		
	link เป็นรูปภาพ
	
		getLink( array(
			'text' => getImage(array('images/logo.png')),
			'part' => 'index.php',
			'onClick' => 'reset()',
		));
		
		getLink(array( getImage(array('images/logo.png')) ));
		
		getLink(array( getImage(array('images/logo.png')) , 'index.php'));

	*/
	public static function getLink($input){
		$str = '';
		$onClick = self::getArrayValue(self::arrGet($input, 'onClick'), self::arrGet($input, 2));
		$part = self::getArrayValue(self::arrGet($input, 'part'), self::arrGet($input, 1));
		$text = self::getArrayValue(self::arrGet($input, 'text'), self::arrGet($input, 0));

		if ($onClick !== '') {
			$str = ' onClick="'.$onClick.'"';
		}
		
		if ($part !== '') {
			return '<a href="'.HtmlControl::getHostName().$part.'"'.$str.'>'.$text.'</a>';
		}
		else {
			return '<a href="#" '.$str.'>'.$text.'</a>';
		}
	}


	//return html tag ตาราง
	/*
			
		$array = array(
				array(a,b,c,d),
				array(1,2,3,4),
				array(x,y,array(z,'col',2)), 
			);
			
		getTable(array($array));
		
		getTable(array($array,'t1',1));
		
		getTable( array(
			'array' => $array,
			'border' => 1,
			'id' => 't1',
			'class' => 'table',
		));

		$sql = 'SELECT * FROM  `db1` ';
		$data = $query->queryDB_to_array($sql);
		getTable( array($data,'t1') );
		
		
	*/
	// $inputBody is array of raw data from query db with primary-key field
	// $inputHead is array of field name
	// $inputFoot is array of field at bottom of table
	// $tableAttribute is array of table's attributes
	//getDBTable(array(),array(),array(),array());
	public static function getDBTable($inputBody,$tableAttribute,$tdValues)
	{
		$str = '';
		$str = '<table id="'.$tableAttribute['id'].'"';
		if(self::hasKey('class',$tableAttribute))
		{
			$str .= ' class="'.$tableAttribute['class'].'"';
		}
		if(self::hasKey('style',$tableAttribute))
		{
			$str .= ' style="'.$tableAttribute['style'].'"';
		}
		$str .= '>';
	
		for($a=0;$a < self::safeCount($inputBody);$a++)
		{
			
			$id = '';
			if(self::safeCount($inputBody[$a][0]) > 1)
			{
				if(self::hasKey('value',$inputBody[$a][0]))
					$id .= $inputBody[$a][0]['value'];
			}
			else
				$id .= $inputBody[$a][0];
			
			$str .= '<tr key="'.$id.'">'; // index 0 is primary-key
			for($b=1;$b < self::safeCount($inputBody[$a]);$b++)
			{
				$str .= '<td';
				if(self::safeCount($inputBody[$a][$b]) > 1)
				{
					if(self::hasKey('colspan',$inputBody[$a][$b]))
					{
						$str .= ' colspan="'.$inputBody[$a][$b]['colspan'].'"';
					}
					if(self::hasKey('rowspan',$inputBody[$a][$b]))
					{
						$str .= ' rowspan="'.$inputBody[$a][$b]['rowspan'].'"';
					}
					if(self::hasKey('style',$inputBody[$a][$b]))
					{
						$str .= ' style="'.$inputBody[$a][$b]['style'].'"';
					}
				}
				$str .= '>';
				if(self::safeCount($inputBody[$a][$b]) > 1)
				{
					if(self::hasKey('value',$inputBody[$a][$b])){
						$str .= $inputBody[$a][$b]['value'];
					}
					else
					{
						$str .= $inputBody[$a][$b];
					}
				}
				else
					$str .= $inputBody[$a][$b];
				$str .= '</td>';
			}
			if($tdValues){
				foreach($tdValues as $key => $value){
					$td = str_replace('{key}',$id,$tdValues[$key]);
					$td = str_replace('{table}',$tableAttribute['table_name'],$td);
					$str .= '<td>'.$td.'</td>';
				}
			}
			$str .= '</tr>';
		}
		
		$str .= '</table>';
		return $str;
	}
	
	//-- ควร เพิ่ม id , class ใน tr td ได้---
	public static function getTable($inputBody,$tableAttribute){
		$str = '';
		$str = '<table id="'.$tableAttribute['id'].'"';
		if(self::hasKey('class',$tableAttribute))
		{
			$str .= ' class="'.$tableAttribute['class'].'"';
		}
		if(self::hasKey('name',$tableAttribute))
		{
			$str .= ' name="'.$tableAttribute['name'].'"';
		}
		if(self::hasKey('style',$tableAttribute))
		{
			$str .= ' style="'.$tableAttribute['style'].'"';
		}
		$str .= '>';
	
		for($a=0;$a < self::safeCount($inputBody);$a++)
		{
			
			$id = '';
			if(self::safeCount($inputBody[$a][0]) > 1)
			{
				if(self::hasKey('value',$inputBody[$a][0]))
					$id .= $inputBody[$a][0]['value'];
			}
			else
				$id .= $inputBody[$a][0];
			
			$str .= '<tr key="'.$id.'">'; // index 0 is primary-key
			for($b=0;$b < self::safeCount($inputBody[$a]);$b++)
			{
				$str .= '<td';
				if(self::safeCount($inputBody[$a][$b]) > 1)
				{
					if(self::hasKey('colspan',$inputBody[$a][$b]))
					{
						$str .= ' colspan="'.$inputBody[$a][$b]['colspan'].'"';
					}
					if(self::hasKey('rowspan',$inputBody[$a][$b]))
					{
						$str .= ' rowspan="'.$inputBody[$a][$b]['rowspan'].'"';
					}
					if(self::hasKey('style',$inputBody[$a][$b]))
					{
						$str .= ' style="'.$inputBody[$a][$b]['style'].'"';
					}
				}
				$str .= '>';
				if(self::safeCount($inputBody[$a][$b]) > 1)
				{
					if(self::hasKey('value',$inputBody[$a][$b])){
						$str .= $inputBody[$a][$b]['value'];
					}
					else
					{
						$str .= $inputBody[$a][$b];
					}
				}
				else
					$str .= $inputBody[$a][$b];
				$str .= '</td>';
			}
			$str .= '</tr>';
		}
		
		$str .= '</table>';
		return $str;
	}
	
	/*	------------------------------  
			### addHeadTable ###
		------------------------------
		@Description >> Insert <thead> in <table>
		@Output >>	<table>
						<thead>
							<tr>
								<td>...</td>
							</tr>
						<thead>
						.
						.
					</table>
		@Parameters >> HtmlControl::addHeadTable(string $tableText,array $array,boolean $isAddThead,string $type)
		@Input >>
		
					$tableText = '<table>
									<tr>
										<td>1</td>
										<td>2</td>
										<td>3</td>
									</tr>
								</table>';
								
					$arrayHead = array('id','name','surname');
					
					## OR ##
					$arrayHead = array(array(
											'value'=>'name',
											'id'=>'th_01', 
											'colspan'=>2,
											'rowspan'=>'1',
											'style'=>'color:red;text-align:center'
											),
									   'surname'
									   );
									   
					## OR ##
					$arrayHead = array(array('name','col',2),'surname');
									 
					$type = 'th' OR 'td';
					
					$str = HtmlControl::addHeadTable($tableText,array($arrayHead));
					## OR Add <thead> ##
					$str = HtmlControl::addHeadTable($tableText,array($arrayHead),true);
					## OR ##
					$str = HtmlControl::addHeadTable($tableText,array($arrayHead),true,'td');
					## OR Add Multi Rows ##
					$str = HtmlControl::addHeadTable($tableText,array($arrayHead1,$arrayHead2));
		
		------------------------------
	*/
	public static function addHeadTable($tableText,$array,$isAddThead=FALSE,$type='th')
	{
		$str = '';
		if($isAddThead)
			$str .= '<thead>';
		for($i=0;$i<self::safeCount($array);$i++){
			$str  .= '<tr>';
			$value = '';
			for($a = 0;$a < self::safeCount($array[$i]);$a++){
				$str .= '<'.$type;
				if(self::safeCount($array[$i][$a]) > 1){
					if(self::hasKey('id',$array[$i][$a])){
						$str .= ' id="'.$array[$i][$a]['id'].'"';
					}
					if(self::hasKey('colspan',$array[$i][$a])){
						$str .= ' colspan="'.$array[$i][$a]['colspan'].'"';
					}
					else if($array[$i][$a][1] =='col'){
						$str .= ' colspan="'.$array[$i][$a][2].'"';
					}
					if(self::hasKey('rowspan',$array[$i][$a])){
						$str .= ' rowspan="'.$array[$i][$a]['rowspan'].'"';
					}
					else if($array[$i][$a][1] =='row'){
						$str .= ' rowspan="'.$array[$i][$a][2].'"';
					}
					if(self::hasKey('style',$array[$i][$a])){
					$str .= ' style="'.$array[$i][$a]['style'].'"';
				}
				}
				$str .= '>';
				if(self::safeCount($array[$i][$a]) > 1){
					$value = self::getArrayValue($array[$i][$a]['value'],$array[$i][$a][0]);
				}
				else{
					$value = $array[$i][$a];
				}
				$str .= $value.'</'.$type.'>';
			}
			$str .= '</tr>';
		}
		if($isAddThead)
			$str .= '</thead>';
		$pos = stripos($tableText,'>')+1; // position after <table
		$tableText = substr_replace($tableText, $str, $pos, 0);
		return $tableText;
	}
	
	/*  ------------------------------
			 ### addFootTable ###
		------------------------------
		@Description >> Insert <tfoot> in <table>
		@Output >>	<table>
						.
						.
						<tfoot>
							<tr>
								<td>...<td>
							</tr>
						</tfoot>
					</table>
		@Parameters >> HtmlControl::addFootTable(string $tableText,array $array,boolean $isAddTfoot,string $type)
		@Input >>
					$tableText = '<table>
									<tr>
										<td>1</td>
										<td>2</td>
										<td>3</td>
									</tr>
								</table>';
								
					$arrayHead = array('id','name','surname');
					
					## OR ##
					$arrayHead = array(array(
											 'value'=>'name',
											 'id'=>'tfoot_01', 
											 'colspan'=>2,
											 'rowspan'=>'1',
											 'style'=>'color:red;text-align:center'
											),
									   'surname'
									  );
									   
					## OR ##
					$arrayFoot = array(array('name','cols',2),'surname');
					
					$type = 'th' OR 'td';
					
					HtmlControl::addFootTable($tableText,array($arrayFoot));
					## OR ##
					HtmlControl::addFootTable($tableText,array($arrayFoot),true);
					## OR ##
					HtmlControl::addFootTable($tableText,array($arrayFoot),true,$type);
					## OR Add Multi Rows ##
					HtmlControl::addFootTable($tableText,array($arrayFoot1,$arrayFoot2));
		------------------------------
	*/
	public static function addFootTable($tableText,$array,$isAddTfoot=FALSE,$type='td'){
		$str = '';
		if($isAddTfoot)
			$str .= '<tfoot>';
		for($i=0;$i<self::safeCount($array);$i++){
			$str  .= '<tr>';
			$value = '';
			for($a = 0;$a < self::safeCount($array[$i]);$a++){
				
				$str .= '<'.$type;
				if(self::safeCount($array[$i][$a]) > 1){
					if(self::hasKey('id',$array[$i][$a])){
						$str .= ' id="'.$array[$i][$a]['id'].'"';
					}
					if(self::hasKey('colspan',$array[$i][$a])){
						$str .= ' colspan="'.$array[$i][$a]['colspan'].'"';
					}
					else if($array[$i][$a][1] =='col'){
						$str .= ' colspan="'.$array[$i][$a][2].'"';
					}
					if(self::hasKey('rowspan',$array[$i][$a])){
						$str .= ' rowspan="'.$array[$i][$a]['rowspan'].'"';
					}
					else if($array[$i][$a][1] =='row'){
						$str .= ' rowspan="'.$array[$i][$a][2].'"';
					}
					if(self::hasKey('style',$array[$i][$a])){
						$str .= ' style="'.$array[$i][$a]['style'].'"';
					}
				}
				$str .= '>';
				if(self::safeCount($array[$i][$a]) > 1){
					$value = self::getArrayValue($array[$i][$a]['value'],$array[$i][$a][0]);
				}
				else{
					$value = $array[$i][$a];
				}
				$str .= $value.'</'.$type.'>';
			}
			$str .= '</tr>';
		}
		if($isAddTfoot)
			$str .= '</tfoot>';
		
		$pos = stripos($tableText,'</table>'); 
		$tableText = substr_replace($tableText, $str, $pos, 0);
		echo $tableText;
		//return $tableText;
	}
	
	/*	------------------------------
		### addTbodyTable ###
		------------------------------
		@Description >> Insert <tbody> in <table>
		@Output >>	<table>
						.
						.
						<tbody>
							.
							.
						</tbody>
						.
						.
					</table>
		@Parameters >> HtmlControl::addTbodyTable(string &$tableText)
		@Input >>
					$tableText = '<table>
									<tr>
										<td>1</td>
										<td>2</td>
										<td>3</td>
									</tr>
								</table>';
					HtmlControl::addTbodyTable(&$tableText);
		
		------------------------------
	*/
	public static function addTbodyTable($tableText){
		$pos = 0;
		if(stripos($tableText,'</thead>') !== false){ // add after </thead>
			$pos = stripos($tableText,'</thead>')+8;
			$tableText = substr_replace($tableText,'<tbody>', $pos, 0);
		}
		else{ // add after <table>
			$pos = stripos($tableText,'>')+1;
			$tableText = substr_replace($tableText,'<tbody>', $pos, 0);
		}
		
		if(stripos($tableText,'<tfoot') !== false){ // add before </tfoot>
			$pos = stripos($tableText,'<tfoot')-1;
			$tableText = substr_replace($tableText,'</tbody>', $pos, 0);
		}
		else{ // add before </table>
			$pos = stripos($tableText,'</table>')-1;
			$tableText = substr_replace($tableText,'</tbody>', $pos, 0);
		}
		return $tableText;
	}
	/*	------------------------------
		  ### insertAttributeTR ###
		------------------------------
		@Description >> Insert html attributes in <tr> 
		@Output >>	## With assign index ##
						$arrayData = array(
								array(array('id'=>'row0','class'=>'rowTest'),1,'x','y'),
								array(2,'a','b'),
								array(3,'i','j'),
						 );
					## Insert all when not assign index ##
						$arrayData = array(
								array(array('id'=>'row0','class'=>'rowTest'),1,'x','y'),
								array(array('id'=>'row0','class'=>'rowTest'),2,'a','b'),
								array(array('id'=>'row0','class'=>'rowTest'),3,'i','j'),
						 );
		@Parameters >> HtmlControl::insertAttributeTR(ref array &$arrayData,array $arrayAttribute,int $index)
		@Input >>
					$arrayData = array(
										array('1','x','y'),
										array('2','a','b'),
										array('3','i','j'),
								 );
					$arrayAttribute = array(
											'id' => 'row0',
											'class' => 'rowTest'
					);
					HtmlControl::insertAttributeTR(&$arrayData,$arrayAttribute); ## insert All rows ##
					## OR ##
					HtmlControl::insertAttributeTR(&$arrayData,$arrayAttribute,0);
		
		@Using case  >> - use with HtmlControl::getTable($arrayData)
						- must use before function 'getTable'
						- !! don't use with 'getDBTable'
						## Example ##
						$arrayData = array(
										array('1','x','y'),
										array('2','a','b'),
										array('3','i','j'),
								 );
						$arrayAttribute = array(
												'id' => 'row0',
												'class' => 'rowTest',
						);
					
						HtmlControl::insertAttributeTR(&$arrayData,$arrayAttribute);
						$str = HtmlControl::getTable(array($arrayData));
						echo $str;
		------------------------------
	*/
	public static function insertAttributeTR(&$arrayData,$arrayAttribute,$index = -1){
		
		if($index > -1) // has index assign
		{
			if(self::safeCount($arrayData)>0 && $index < self::safeCount($arrayData)){
				array_unshift($arrayData[$index],$arrayAttribute);
			}
		}
		else if($index == -1){ // insert all
			$max = self::safeCount($arrayData);
			for($a=0;$a<$max;$a++){
				array_unshift($arrayData[$a],$arrayAttribute);
			}
		}
	}
	
	/*	------------------------------
		   ### insertAttributeTD ###
		------------------------------
		@Description >> Insert html attributes in specify <td> 
		@Output >>	$arrayData = array(
								array('1','x',array('value'=>'y','id'=>'tdID','class'=>'tdClass','style'=>'color:red'),
								array('2','a','b'),
								array('3','i','j'),
						 );
		@Parameters >> HtmlControl::insertAttributeTD(ref array &$arrayData,array $arrayAttribute,int $colIndex,int $rowIndex)
		@Input >>
					$arrayData = array(
										array('1','x','y'),
										array('2','a','b'),
										array('3','i','j'),
								 );
					$arrayAttribute = array(
											'id' => 'tdID',
											'class'=>'tdClass'
									  );
									  
					HtmlControl::insertAttributeTD(&$arrayData,$arrayAttribute,3,0); ## insert colum 3 in row 0 ##
					## OR ##
					HtmlControl::insertAttributeTD(&$arrayData,$arrayAttribute,3); ## insert col 3 in ALL rows ##
					
		@Using case  >> use for insert attribute in array of data
						& must use before function 'insertAttributeTR'
						## Example ##
						$arrayData = array(
										array('1','x','y'),
										array('2','a','b'),
										array('3','i','j'),
								 );
						$arrayAttribute = array(
												'id' => 'tdID',
												'class'=>'tdClass',
												'style'=>'color:red'
										  );
						HtmlControl::insertAttributeTD(&$arrayData,$arrayAttribute,3,0);
						$str = HtmlControl::getTable(array($arrayData));
						echo $str;
						
						## OR ##
						$str = HtmlControl::getDBTable(array($arrayData),array());
						echo $str;
		------------------------------
	*/
	public static function insertAttributeTD(&$arrayData,$arrayAttribute,$colIndex,$rowIndex = -1)
	{
		if($rowIndex > -1)
		{
			if($rowIndex < self::safeCount($arrayData) && $colIndex < self::safeCount($arrayData[$rowIndex]))
			{
				$value = $arrayData[$rowIndex][$colIndex];
				$array = array('value'=>$value);
				$arrayAttribute = $array + $arrayAttribute;
				$arrayData[$rowIndex][$colIndex] = $arrayAttribute;
			}
		}
		else if($rowIndex == -1)
		{
			$rowCount = self::safeCount($arrayData);
			for($a = 0;$a < $rowCount;$a++){
				if($colIndex < self::safeCount($arrayData[$a])){
					$value = $arrayData[$a][$colIndex];
					$array = array('value'=>$value);
					$arrayAttribute = $array + $arrayAttribute;
					$arrayData[$a][$colIndex] = $arrayAttribute;
				}
			}
		}
	}
	
	/*	------------------------------
		   ### getListUL ###
		------------------------------
		@Description >> create html 'List'
		@Output >>	<ul id="ulID" class="ulClass">
						<li>a</li>
						<li>b</li>
						<li>c</li>
						<li>d</li>
					</ul>
		@Parameters >> HtmlControl::getListUL(array $input)
		@Input >>
					$arrayData = array('a','b','c','d');
					$arrayAttribute = array(
											'id' => 'ulID',
											'class'=>'ulClass',
											'style'=>'list-style-type:none'
									  );
					$str = HtmlControl::getListUL(array(
									'data'=>$arrayData,
									'attribute'=>$arrayAttribute
								));
					## OR ##
					$str = HtmlControl::getListUL(array($arrayData,$arrayAttribute));
	
		------------------------------
	*/
	public static function getListUL($input)
	{
		$arrayData = self::getArrayValue(self::arrGet($input, 'data'), self::arrGet($input, 0));
		$arrayULAttribute = array();

		if (self::safeCount($input) > 1) {
			$arrayULAttribute = self::getArrayValue(self::arrGet($input, 'attribute'), self::arrGet($input, 1));
		}

		if (!is_array($arrayData)) {
			$arrayData = array();
		}
		if (!is_array($arrayULAttribute)) {
			$arrayULAttribute = array();
		}

		$str = "<ul";

		if (self::safeCount($arrayULAttribute) > 0) {
			if (self::hasKey('id', $arrayULAttribute)) {
				$str .= " id='".$arrayULAttribute['id']."'";
			}
			if (self::hasKey('class', $arrayULAttribute)) {
				$str .= " class='".$arrayULAttribute['class']."'";
			}
			if (self::hasKey('style', $arrayULAttribute)) {
				$str .= " style='".$arrayULAttribute['style']."'";
			}
		}
		
		$str .= ">";
		
		for ($a = 0; $a < self::safeCount($arrayData); $a++) {
			$str .= "<li";

			if (is_array($arrayData[$a]) && self::safeCount($arrayData[$a]) > 1) {
				if (self::hasKey('id', $arrayData[$a])) {
					$str .= " id='".$arrayData[$a]['id']."'";
				}
				if (self::hasKey('class', $arrayData[$a])) {
					$str .= " class='".$arrayData[$a]['class']."'";
				}
			}

			$str .= ">";

			if (is_array($arrayData[$a]) && self::safeCount($arrayData[$a]) > 1 && self::hasKey('value', $arrayData[$a])) {
				$str .= $arrayData[$a]['value']."</li>";
			}
			else {
				$str .= $arrayData[$a]."</li>";
			}
		}

		$str .= "</ul>";
		return $str;
	}

	/*	------------------------------
		   ### insertAttributeLI ###
		------------------------------
		@Description >> Insert html attributes in specify <li> 
		@Output >>	$arrayData = array(array('value'=>'a','id'=>'li01','class'=>'liTest'),'b','c','d');
					## HTML ##
					<ul>
						<li id='li01' class='liTest'>a</li>
						<li>b</li>
						<li>c</li>
						<li>d</li>
					</ul>
		@Parameters >> HtmlControl::insertAttributeLI(ref array &$arrayData,array $arrayAttribute,int $index)
		@Input >>
					$arrayData = array('a','b','c','d');
					$arrayLIAttribute = array(
											'id' => 'li01',
											'class'=>'liTest',
									  );
					
					HtmlControl::insertAttributeLI(&$arrayData,$arrayLIAttribute); ## insert All li ##
					## OR ##
					HtmlControl::insertAttributeLI(&$arrayData,$arrayLIAttribute,0);
		@Using case  >> must use before function 'getListUL'
		
						## Example ##
						$arrayData = array('a','b','c','d');
						$arrayLIAttribute = array(
											'id' => 'li01',
											'class'=>'liTest',
									  );
					
						HtmlControl::insertAttributeLI(&$arrayData,$arrayLIAttribute);
						
						$str = HtmlControl::getListUL(array($arrayData));
		------------------------------
	*/
	public static function insertAttributeLI(&$arrayData,$arrayAttribute,$index=-1){
		if($index > -1){
			if($index < self::safeCount($arrayData)){
				$array = array('value'=>$arrayData[$index]);
				$array = $array + $arrayAttribute;
				$arrayData[$index] = $array;
			}
		}
		else if($index == -1){
			for($a=0;$a<self::safeCount($arrayData);$a++){
				$array = array('value'=>$arrayData[$a]);
				$array = $array + $arrayAttribute;
				$arrayData[$a] = $array;
			}
		}
	}
	
	/*
		$id = getArrayValue($input['id'],$input[0]);
		$value = getArrayValue($input['value'],$input[1]);
	*/
	public static function getArrayValue($valueName = '', $valueNum = ''){
		if ($valueName !== '' && $valueName !== null) {
			return $valueName;
		}
		if ($valueNum !== '' && $valueNum !== null) {
			return $valueNum;
		}
		return '';
	}	

	public static function addTax($array,$col){
		foreach($array as $key => $value){
			$array[$key][$col] = HtmlControl::changeDateForm($array[$key][$col]);
		}
		return $array;
	}	
	public static function changeDateForm($input){
		list($year, $month, $day)= explode("-",$input);
		$day = substr($day,0,2);
		$year = intval($year);
		return $day."/".$month."/".$year;
	}
	public static function changeDateFormArray($array,$col){
		foreach($array as $key => $value){
			$array[$key][$col] = HtmlControl::changeDateForm($array[$key][$col]);
		}
		return $array;
	}
	public static function changeTextForm($input,$from){
		foreach($from as $key => $value){
				if($input == $key){
					$input = $value;
				}
			}
		return $input;
	}
	public static function changeTextFormArray($array,$col,$from){
		foreach($array as $key => $value){
			$array[$key][$col] = HtmlControl::changeTextForm($array[$key][$col],$from);
			
		}
		return $array;
	}
	
	public static function showHtml($value){
		echo htmlspecialchars($value,ENT_QUOTES,'UTF-8');
	}
	
	public static function getDifDate($dateTime){
		$current_time = date("Y-m-d H:i:s");
		$array = explode(' ',$dateTime);
		$arrayDate = explode('-',$array[0]);
		
		$arrayCur = explode(' ',$current_time);
		$arrayCurDate = explode('-',$arrayCur[0]);
		
		$sum_date = self::calculateDateToDay($arrayCurDate,$arrayDate);
		return $sum_date;
	}
	
	public static function getDifTime($dateTime){
		$current_time = date("Y-m-d H:i:s");
		$array = explode(' ',$dateTime);
		$arrayTime = explode(':',$array[1]);
		
		$arrayCur = explode(' ',$current_time);
		$arrayCurTime = explode(':',$arrayCur[1]);
		
		$sum_time = self::calculateTimeToMinute($arrayCurTime,$arrayTime);
		return $sum_time;
	}
	
	public static function getDifDateTimeToString($dateTime){
		
		$sum_date = self::getDifDate($dateTime);
		$sum_time = self::getDifTime($dateTime);
		
		//------- Set date text -------
		if($sum_date >= 360){// >= 1 years
			$y = intval($sum_date/360);
			$strDate = $y.' ปี';
		}
		else if($sum_date >= 30){// >= 1 month
			$m = intval($sum_date/30);
			$strDate = $m.' เดือน';
		}
		else if($sum_date > 1){// > 1 days
			$week = intval($sum_date/7);
			$d = $sum_date%7;
			if($week > 0 && $d == 0){
				$strDate = $week.' อาทิตย์';
			}
			else{
				$strDate = $sum_date.' วัน';
			}
		}
		else if($sum_date == 1){ // = 1 days
			$strDate = 'เมื่อวาน';
		}
		else{ // today
			$h = intval($sum_time/60); // change minutes to hours
			if($h > 8){
				$strDate = 'วันนี้';
			}
			else if($h > 0){
				$strDate = $h.'ชั่วโมง';
			}
			else{
				$strDate = $sum_time.' นาที';
			}
		}
		//----- End set date text -----
		
		return $strDate;
	}
	
	public static function getDifDateToString($date){
		
		$current_time = date("Y-m-d");
		$array = explode(' ',$date);
		$arrayDate = explode('-',$array[0]);
		
		$arrayCur = explode(' ',$current_time);
		$arrayCurDate = explode('-',$arrayCur[0]);
		
		$sum_date = self::calculateDateToDay($arrayCurDate,$arrayDate);
		
		//------- Set date text -------
		if($sum_date >= 360){// >= 1 years
			$y = intval($sum_date/360);
			$strDate = $y.' ปี';
		}
		else if($sum_date >= 30){// >= 1 month
			$m = intval($sum_date/30);
			$strDate = $m.' เดือน';
		}
		else if($sum_date > 1){// > 1 days
			$week = intval($sum_date/7);
			$d = $sum_date%7;
			if($week > 0 && $d == 0){
				$strDate = $week.' อาทิตย์';
			}
			else{
				$strDate = $sum_date.' วัน';
			}
		}
		else if($sum_date == 1){ // = 1 days
			$strDate = 'เมื่อวาน';
		}
		else{ // today
			$strDate = 'วันนี้';
		}
		//----- End set date text -----
		
		return $strDate;
	}
	
	public static function calculateDateToDay($arrayDate1,$arrayDate2){
		$year = $arrayDate1[0]-$arrayDate2[0];
		$month = $arrayDate1[1]-$arrayDate2[1];
		$day = $arrayDate1[2]-$arrayDate2[2];
		$sum = ($year*12*30)+($month*30)+($day);
		return $sum;
	}
	
	public static function calculateTimeToMinute($arrayTime1,$arrayTime2){
		$hour = $arrayTime1[0]-$arrayTime2[0];
		$minute = $arrayTime1[1]-$arrayTime2[1];
		$sum = ($hour*60)+$minute;
		return $sum;
	}
	
	public static function getHTMLSelect($options,$input=array(),$same_value=false){
		if (!is_array($options)) {
			$options = array();
		}
		if (!is_array($input)) {
			$input = array();
		}

		$name = self::arrGet($input, 'name', '');
		$id = self::arrGet($input, 'id', '');
		$class = self::arrGet($input, 'class', '');
		$selected = self::arrGet($input, 'select', '');

		if (self::safeCount($input) > 0) {
			$str = '<select ';
			if ($name !== '') {
				$str .= 'name="'.$name.'" ';
			}
			if ($id !== '') {
				$str .= 'id="'.$id.'" ';
			}
			if ($class !== '') {
				$str .= 'class="'.$class.'" ';
			}
			$str .= '>';
		}
		else {
			$str = '<select name="'.$name.'">';
		}

		foreach ($options as $key => $value) {
			if (!is_array($value)) {
				$optionValue = $same_value ? $key : $value;
				$str .= '<option value="'.$optionValue.'"';
				if ($value == $selected) {
					$str .= ' selected';
				}
				$str .= '>'.$value.'</option>';
			}
			else {
				$optionValue = $same_value ? self::arrGet($value, 1) : self::arrGet($value, 0);
				$optionText = self::arrGet($value, 1);
				$str .= '<option value="'.$optionValue.'"';
				if ($optionValue == $selected || $optionText == $selected) {
					$str .= ' selected';
				}
				$str .= '>'.$optionText.'</option>';
			}
		}

		$str .= '</select>';
		return $str;
	}	
	
	public static function getHTMLradio($options,$checked,$name,$br=false){
		$str = '';
		foreach($options as $key=>$value){
			$str .= '<input type="radio" name="'.$name.'" ';
			$str .= 'value="'.$value.'" ';
			if($value==$checked){
				$str .= 'checked';
			}
			$str .= '>'.$value;
		}
		return $str;
	}	
	public static function addTextCover($array,$col,$strat,$end){
		foreach($array as $key => $value){
			if($array[$key][$col]!=''){
				$array[$key][$col] = $strat.$array[$key][$col].$end;
			}
		}
		return $array;
	}
}
?>