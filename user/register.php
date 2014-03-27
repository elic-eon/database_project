<?php require_once('../layout/header_general.php') ?>
<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();

if ($_SESSION['isAuth']) {
	$redirectURL = PATH_ROOT_URL.'/schedule/index.php';
	header('Location: '.$redirectURL);
	exit;
}
?>
<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <h2>Register a New Account</h2>
    <?php require_once('../layout/msg.php') ?>
    <form action="register_func.php" method="post" role="form">
      <div class="form-group">
        <label for="account" class="control-label">Account</label>
        <div>
          <input name="account" type="text" class="form-control" id="account" placeholder="Your account" required>
        </div>
      </div>
      <div class="form-group">
        <label for="password" class="control-label">Password</label>
        <input name="password" type="password" class="form-control" id="form-group" placeholder="Your password" required>
      </div>
      <div class="checkbox">
        <input name="is_admin" type="checkbox">Register as admin
      </div>
      <div class="form-group">
        <input type="submit" value="Register" class="btn btn-default"></p>
      </div>
    </form>
    <a href="../">Go Home</a>
  </div>
</div>
<?php require_once('../layout/footer_general.php') ?>
