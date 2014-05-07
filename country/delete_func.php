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

$sql = "DELETE FROM country WHERE name = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_GET['name']));

$_SESSION['msg'] = 'Delete country successfully.';
$redirectURL = './';

header('Location: '.$redirectURL);
?>