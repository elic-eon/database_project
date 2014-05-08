<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();
unset($_SESSION['departure']);
unset($_SESSION['destination']);
unset($_SESSION['maxTransfer']);

header('Location: ./advanceSearch.php');
?>
