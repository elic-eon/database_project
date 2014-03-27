<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();

if (!$_SESSION['isAuth'] || !$_SESSION['isAdmin']) {
	$redirectURL = PATH_ROOT_URL.'/error/403.php';
	header('Location: '.$redirectURL);
	exit;
}
?>

<?php
require_once('../layout/header.php');
require_once('../layout/msg.php');
?>

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Add User</h1>
		<form action="add_func.php" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<label for="account" class="col-sm-2 control-label">Account</label>
				<div class="col-sm-4">
					<input name="account" type="text" class="form-control" id="account" placeholder="Your account" required>
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-2 control-label">Password</label>
				<div class="col-sm-4">
					<input name="password" type="password" class="form-control" id="form-group" placeholder="Your password" required>
				</div>
			</div>
			<div class="form-group">
				<label for="is_admin" class="col-sm-2 control-label">Authority</label>
				<div class="col-sm-4">
					<input name="is_admin" type="checkbox">
					Is Admin
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="Add" class="btn btn-default"></p>
				</div>
			</div>
		</form>
	</div>
		<!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<?php require_once('../layout/footer.php'); ?>
