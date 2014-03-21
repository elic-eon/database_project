<?
session_start();
require_once('config.php');
require_once('db.php');
require_once('module/checkData.php');

$key = isDataInvalid();
if ($key) {
	$_SESSION['msg'] = "$key cannot be empty.";
	$redirectURL = 'register.php';
} else {
	$sql = "SELECT * FROM user WHERE account = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_POST['account']));
	if ($sth->fetchObject()) {
		$_SESSION['msg'] = 'Account exists.';
		$redirectURL = 'register.php';
	} else {
		$isAdmin = $_POST['is_admin']? true: false;
		$sql = "INSERT INTO user (account, password, is_admin) VALUES(?, ?, ?)";
		$sth = $db->prepare($sql);
		$sth->execute(array($_POST['account'], crypt($_POST['password'], $PW_SALT), $isAdmin));
		$_SESSION['msg'] = 'Register successfully.';
		$redirectURL = 'login.php';
	}
}

header('Location: '.$redirectURL);
?>