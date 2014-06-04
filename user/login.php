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

<?php require_once('../layout/header_general.php') ?>
<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <h2>Please Login</h2>
    <?php require_once('../layout/msg.php') ?>
    <form action="login_func.php" method="post" role="form" >
      <div class="form-group">
        <label for="account" class="control-label">Account</label>
        <div>
          <input name="account" type="text" class="form-control" id="account" placeholder="Your account" required>
        </div>
        <span class="help-block">帳號是"admin"或是"user"</span>
      </div>
      <div class="form-group">
        <label for="password" class="control-label">Password</label>
        <div>
          <input name="password" type="password" class="form-control" id="password" placeholder="Your password"required>
        </div>
        <span class="help-block">密碼與帳號相同</span>
      </div>
      <div class="form-group">
        <input type="submit" value="Login" class="btn btn-default"> or <a href="register.php">Register a new account</a>
      </div>
    </form>
    <p> </p>
    <a href="../">Go Home</a>
  </div>
</div>
<?php require_once('../layout/footer_general.php') ?>
