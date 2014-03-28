<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();
unset($_SESSION['isAuth']);
unset($_SESSION['uid']);
unset($_SESSION['isAdmin']);
$_SESSION['msg'] = 'Logout successfully.';

header('Location: '.PATH_ROOT_URL);
?>
