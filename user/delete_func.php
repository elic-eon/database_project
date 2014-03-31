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

$sql = "DELETE FROM user WHERE id = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_GET['id']));

$sql = "SELECT session_id FROM user WHERE id = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_GET['id']));
$result = $sth->fetchObject();
if ($result->session_id) {
	$mySessionId = session_id();
	session_id($result->session_id);
	session_start();
	$_SESSION['isDelete'] = true;
	session_id($mySessionId);
	session_start();
}

$_SESSION['msg'] = 'Delete user successfully.';
$redirectURL = './';

header('Location: '.$redirectURL);
?>
