<?
ob_start();

include_once(__DIR__ . '/dbControl.php');

class SessionControl
{
	public static $host_path = '';
	public static $table = 'user';
	public static $key_field = 'user_id';
	public static $username_field = 'user_username';
	public static $password_field = 'user_password';
	public static $encode = '';
	public static $alert_id = 'alertlogin';
	public static $user_type_field = 'user_type';

	public static function getUser()
	{
		return $_COOKIE["user"] ?? '';
	}

	public static function getUserID()
	{
		return $_COOKIE["user"] ?? '';
	}

	public static function getHostPath()
	{
		return self::$host_path;
	}

	public static function getUserType()
	{
		$user_id = $_COOKIE["user"] ?? '';

		if ($user_id == '') {
			return '';
		}

		$query = new DBControl();

		$sql = 'SELECT ' . self::$user_type_field . '
				FROM ' . self::$table . '
				WHERE ' . self::$key_field . ' = "' . addslashes($user_id) . '"';

		$user_type = $query->queryDB_to_string($sql);

		return $user_type;
	}

	public static function login($username, $password)
	{
		$query = new DBControl();

		$username = addslashes(trim($username));
		$password = addslashes(trim($password));

		if ($username == '' || $password == '') {
			echo '<script>
				document.getElementById("' . self::$alert_id . '").innerHTML = "กรุณากรอกข้อมูลให้ครบ";
			</script>';
			return false;
		}

		$sql = 'SELECT ' . self::$key_field . '
				FROM ' . self::$table . '
				WHERE ' . self::$username_field . ' = "' . $username . '"
				AND ' . self::$password_field . ' = ' . self::$encode . '("' . $password . '")';

		$user_id = $query->queryDB_to_string($sql);
		$num = $query->queryDB_num_row($sql);

		if ($num != 0) {
			setcookie("user", $user_id, time() + (3600 * 3), '/' . self::$host_path);
			setcookie("recall", $user_id, time() + ((3600 * 24) * 90), '/' . self::$host_path);

			return true;
		} else {
			echo '<script>
				document.getElementById("' . self::$alert_id . '").innerHTML = "ไม่พบข้อมูลสมาชิกของท่าน";
			</script>';

			return false;
		}
	}

	public static function logout()
	{
		setcookie("user", '', time() - 3600, '/' . self::$host_path);

		echo '<script>
			window.location = "../login.php";
		</script>';

		exit;
	}

	public static function clearRecall()
	{
		setcookie("recall", '', time() - 3600, '/' . self::$host_path);

		echo '<script>
			window.location = "../login.php";
		</script>';

		exit;
	}
}

/* logout */
if (isset($_GET['logout']) && $_GET['logout'] != '') {
	SessionControl::logout();
}

/* clear remembered account */
if (isset($_GET['unrecall']) && $_GET['unrecall'] != '') {
	SessionControl::clearRecall();
}

/* login submit */
$uumm = trim($_POST['uumm'] ?? '');
$ppww = trim($_POST['ppww'] ?? '');

if ($uumm != '' || $ppww != '') {

	if ($uumm != '' && $ppww != '') {

		$login = SessionControl::login($uumm, $ppww);

		if ($login) {
			$str_location = 'index.php';
			echo '<script>
				window.location = "' . $str_location . '";
			</script>';
			exit;
		}

	} else {
		echo '<script>
			document.getElementById("' . SessionControl::$alert_id . '").innerHTML = "กรุณากรอกข้อมูลให้ครบ";
		</script>';
		exit;
	}
}
?>