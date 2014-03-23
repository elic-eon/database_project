<?php
require_once('./config.php');
session_save_path($SESSION_STORE);
session_start();
unset($_SESSION['isAuth']);
unset($_SESSION['isAdmin']);
$_SESSION['msg'] = 'Logout successfully.';

$redirectURL = 'index.php';
header('Location: '.$redirectURL);
?>
