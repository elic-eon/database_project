<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();

if (!$_SESSION['isAuth'] || !$_SESSION['isAdmin']) {
	$redirectURL = PATH_ROOT_URL.'/error/403.php';
	header('Location: '.$redirectURL);
	exit;
}



require_once('../module/db.php');

// Get session_id before the user is removed
$sql = "SELECT session_id FROM user WHERE id = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_GET['id']));
$result = $sth->fetchObject();
$session_id = $result->session_id;

$sql = "DELETE FROM user WHERE id = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_GET['id']));

if ($session_id) {
	// Switch session
	$mySessionId = session_id();
	session_write_close();
	session_save_path(PATH_SESSION_STORE);
	session_id($session_id);
	session_start();
	$_SESSION['isAuth'] = false;
	$_SESSION['isDelete'] = true;
	session_write_close();
	session_save_path(PATH_SESSION_STORE);
	session_id($mySessionId);
	session_start();
}

$_SESSION['msg'] = 'Delete user successfully.';
$redirectURL = './';

header('Location: '.$redirectURL);
?>
