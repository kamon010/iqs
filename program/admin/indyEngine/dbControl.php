<?php

mysqli_report(MYSQLI_REPORT_OFF);
error_reporting(E_ALL ^ E_NOTICE);

include_once('dbStructure.php');

date_default_timezone_set('Asia/Bangkok');

class DBControl
{
	var $host;
	var $user;
	var $password;
	var $dbName;
	var $port;
	var $connect;

	function __construct($localhost = '', $user = '', $password = '', $dbName = '')
	{
		if ($localhost != '' && $user != '' && $password != '' && $dbName != '') {

			$this->host = $localhost;
			$this->user = $user;
			$this->password = $password;
			$this->dbName = $dbName;
			$this->port = DBStructure::getDBPort();

		} else {

			$this->host = DBStructure::getHost();
			$this->user = DBStructure::getDBUser();
			$this->password = DBStructure::getDBPassword();
			$this->dbName = DBStructure::getDBName();
			$this->port = DBStructure::getDBPort();
		}

		$this->setConnect();
	}

	private function setConnect()
	{
		$this->connect = new mysqli(
			$this->host,
			$this->user,
			$this->password,
			$this->dbName,
			$this->port
		);

		if ($this->connect->connect_error) {
			die(
				"Connection failed: " .
				$this->connect->connect_error .
				"<br>Host: " . $this->host .
				"<br>Port: " . $this->port
			);
		}

		$this->setUTF8mb4();
	}

	public function getInsertID()
	{
		return $this->connect->insert_id;
	}

	public function setUTF()
	{
		$this->connect->set_charset("utf8");
	}

	public function setUTF8mb4()
	{
		$this->connect->set_charset('utf8mb4');
	}

	public function queryDB($sql)
	{
		if (stripos(trim($sql), 'SELECT') === 0) {
			return $this->queryDB_to_array($sql);
		} else {
			return $this->chkQuery($sql);
		}
	}

	public function query($sql)
	{
		if (stripos(trim($sql), 'SELECT') === 0) {
			return $this->queryDB_to_array($sql);
		} else {
			return $this->chkQuery($sql);
		}
	}

	public function queryDB_num_row($sql)
	{
		$data = mysqli_query($this->connect, $sql);

		if (!$data) {
			return 0;
		}

		return mysqli_num_rows($data);
	}

	public function queryDB_to_string($sql)
	{
		$data = mysqli_query($this->connect, $sql);

		if ($data) {
			$row = mysqli_fetch_array($data);

			if ($row && isset($row[0])) {
				return $row[0];
			}
		}

		return '';
	}

	public function queryDB_to_array($sql)
	{
		$array = array();
		$index = 0;

		$data = mysqli_query($this->connect, $sql);

		if ($data) {
			while ($row = mysqli_fetch_assoc($data)) {
				$array[$index] = $row;
				$index++;
			}
		}

		return $array;
	}

	public function queryDB_to_row($sql)
	{
		$data = mysqli_query($this->connect, $sql);

		if ($data) {
			$array = mysqli_fetch_assoc($data);
			return $array;
		}

		return array();
	}

	public function insertDB_array_all($table, $array)
	{
		$valueArray = '';

		for ($i = 0; $i < sizeof($array); $i++) {

			if (gettype($array[$i]) == 'string') {

				$valueArray .= ",'" . $array[$i] . "'";

			} else if (empty($array[$i])) {

				$valueArray .= ",''";

			} else {

				if ($array[$i] == '') {
					$valueArray .= ",NULL";
				} else {
					$valueArray .= "," . $array[$i];
				}
			}
		}

		$valueArray = substr($valueArray, 1);

		$sql = "INSERT INTO $table VALUES ( $valueArray);";

		return $this->chkQuery($sql);
	}

	public function insertDB($table, $field, $value)
	{
		if (substr($value, 0, 1) == '(') {

			$sql = "INSERT INTO $table ( $field ) VALUES $value ;";

		} else {

			$sql = "INSERT INTO $table ( $field ) VALUES ( $value );";
		}

		return $this->chkQuery($sql);
	}

	public function insertDB_array($table, $field, $array)
	{
		$fieldArray = '';
		$valueArray = '';

		for ($i = 0; $i < sizeof($field); $i++) {

			if (gettype($array[$i]) == 'string') {

				$fieldArray .= ",`" . $field[$i] . "`";
				$valueArray .= ",'" . str_replace("&#44;", ",", $array[$i]) . "'";

			} else {

				$fieldArray .= "," . $field[$i];

				if ($array[$i] == '') {
					$valueArray .= ",NULL";
				} else {
					$valueArray .= "," . $array[$i];
				}
			}
		}

		$fieldArray = substr($fieldArray, 1);
		$valueArray = substr($valueArray, 1);

		$sql = "INSERT INTO `$table` ( $fieldArray ) VALUES ( $valueArray );";

		return $this->chkQuery($sql);
	}

	public function updateDB($table, $key, $id, $field, $value)
	{
		$array_primaryKey = explode(",", $key);
		$array_keys = explode(",", $id);
		$array_field = explode(",", $field);
		$array_value = explode(",", $value);

		$str = '';

		for ($i = 0; $i < count($array_field); $i++) {

			$str .= $array_field[$i] .
				" = '" .
				str_replace("&#44;", ",", $array_value[$i]) .
				"',";
		}

		$str = substr($str, 0, -1);

		$sql = "UPDATE " . $table . " SET " . $str . " WHERE ";

		for ($a = 0; $a < count($array_primaryKey); $a++) {

			$str = '';

			if ($a > 0) {
				$str = " AND ";
			}

			$sql .= $str .
				$array_primaryKey[$a] .
				" = '" .
				$array_keys[$a] .
				"'";
		}

		return $this->chkQuery($sql);
	}

	public function updateDB_array($table, $key, $id, $array_field, $array_value)
	{
		$array_primaryKey = explode(",", $key);
		$array_keys = explode(",", $id);

		$str = '';

		for ($i = 0; $i < count($array_field); $i++) {

			$str .= $array_field[$i] .
				" = '" .
				$array_value[$i] .
				"',";
		}

		$str = substr($str, 0, -1);

		$sql = "UPDATE " . $table . " SET " . $str . " WHERE ";

		for ($a = 0; $a < count($array_primaryKey); $a++) {

			$str = '';

			if ($a > 0) {
				$str = " AND ";
			}

			$sql .= $str .
				$array_primaryKey[$a] .
				" = '" .
				$array_keys[$a] .
				"'";
		}

		return $this->chkQuery($sql);
	}

	public function updateDB_condition($table, $field, $value, $condition)
	{
		$array_field = explode(",", $field);
		$array_value = explode(",", $value);

		$str = '';

		for ($i = 0; $i < count($array_field); $i++) {

			$str .= $array_field[$i] .
				" = '" .
				str_replace("&#44;", ",", $array_value[$i]) .
				"',";
		}

		$str = substr($str, 0, -1);

		$sql = "UPDATE " .
			$table .
			" SET " .
			$str .
			" WHERE " .
			$condition .
			";";

		return $this->chkQuery($sql);
	}

	public function updateDB_array_condition(
		$table,
		$array_field,
		$array_value,
		$condition
	) {
		$str = '';

		for ($i = 0; $i < count($array_field); $i++) {

			$str .= $array_field[$i] .
				" = '" .
				str_replace("&#44;", ",", $array_value[$i]) .
				"',";
		}

		$str = substr($str, 0, -1);

		$sql = "UPDATE " .
			$table .
			" SET " .
			$str .
			" WHERE " .
			$condition .
			";";

		return $this->chkQuery($sql);
	}

	public function deleteDB($table, $condition)
	{
		$sql = "DELETE FROM " .
			$table .
			" WHERE " .
			$condition .
			";";

		return $this->chkQuery($sql);
	}

	public function chkQuery($sql)
	{
		$chk = mysqli_query($this->connect, $sql);

		if (!$chk) {

			$error =
				mysqli_errno($this->connect) .
				": " .
				mysqli_error($this->connect);

			return $error;
		}

		return true;
	}

	public function closeDB()
	{
		mysqli_close($this->connect);
	}

	public function close()
	{
		mysqli_close($this->connect);
	}

	public function commit()
	{
		$this->connect->commit();
	}

	public function rollback()
	{
		$this->connect->rollback();
	}

	public function begin_transaction($flag)
	{
		$this->connect->begin_transaction($flag);
	}
}

function encode($string, $key = "")
{
	$string =
		generateRandomString() .
		' ' .
		$string .
		' ' .
		generateRandomString();

	$key = sha1($key);

	$strLen = strlen($string);
	$keyLen = strlen($key);

	$j = 0;
	$hash = '';

	for ($i = 0; $i < $strLen; $i++) {

		$ordStr = ord(substr($string, $i, 1));

		if ($j == $keyLen) {
			$j = 0;
		}

		$ordKey = ord(substr($key, $j, 1));

		$j++;

		$hash .= strrev(
			base_convert(
				dechex($ordStr + $ordKey),
				16,
				36
			)
		);
	}

	return $hash;
}

function decode($string, $key = "")
{
	$key = sha1($key);

	$strLen = strlen($string);
	$keyLen = strlen($key);

	$j = 0;
	$hash = '';

	for ($i = 0; $i < $strLen; $i += 2) {

		$ordStr = hexdec(
			base_convert(
				strrev(substr($string, $i, 2)),
				36,
				16
			)
		);

		if ($j == $keyLen) {
			$j = 0;
		}

		$ordKey = ord(substr($key, $j, 1));

		$j++;

		$hash .= chr($ordStr - $ordKey);
	}

	$str = explode(' ', $hash);

	return $str[1] ?? '';
}

function generateRandomString($length = 5)
{
	$characters =
		'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	$charactersLength = strlen($characters);

	$randomString = '';

	for ($i = 0; $i < $length; $i++) {

		$randomString .=
			$characters[
				rand(0, $charactersLength - 1)
			];
	}

	return $randomString;
}

?>