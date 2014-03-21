<?
session_start();
unset($_SESSION['isAuth']);
unset($_SESSION['isAdmin']);
$_SESSION['msg'] = 'Logout successfully.';

$redirectURL = 'index.php';
header('Location: '.$redirectURL);
?>