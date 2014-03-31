<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();
unset($_SESSION['searchField']);
unset($_SESSION['searchKeyword']);

header('Location: ./');
?>
