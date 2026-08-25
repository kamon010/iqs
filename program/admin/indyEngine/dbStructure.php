<?
class DBStructure{
	
	public static $host = '127.0.0.1';
	public static $db_user = 'root';
	public static $db_password = '';
	public static $db_name = 'iqstrc';
	
	public static $dbms = 'mysql';
	
	
	public static $table = array(
		'user'=>'user_id',
		'page'=>'id',
		'picture'=>'id',
		'university'=>'university_id',
		'faculty'=>'faculty_id '

	);
	public static $ai = array(
		
	);
	
	public static $reference_key = array(
		
	);
	
	public static function getHost(){
		return self::$host;
	}
	public static function getDBUser(){
		return self::$db_user;
	}
	public static function getDBPassword(){
		return self::$db_password;
	}
	public static function getDBName(){
		return self::$db_name;
	}
	public static function getDBMS(){
		return self::$dbms;
	}
	
	
	public static function getKey($table_name){
		return self::$table[$table_name];
	}
	public static function checkAI($table_name){
		if(in_array($table_name,self::$ai)){
			return true;
		}
		else return false;
	}
	public static function getRefkey($table_name){
		return self::$reference_key[$table_name];
	}
}
?>
