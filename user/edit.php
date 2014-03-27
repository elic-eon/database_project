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
require_once('../module/db.php');
$sql = "SELECT * FROM user WHERE id = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_GET['id']));
$result = $sth->fetchObject();
require_once('../layout/header.php');
require_once('../layout/msg.php');
?>

<div class="row">
    <div class="col-lg-12">
    	<h1 class="page-header">Edit User</h1>
		<form action="edit_func.php" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<input name="id" type="hidden" value="<?php echo $result->id ?>">
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Account</label>
				<div class="col-sm-4">
					<p class="form-control-static"><?php echo $result->account ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Authority</label>
				<div class="col-sm-4">
					<input name="is_admin" type="checkbox" <?php if ($result->is_admin) echo 'checked' ?> >
					Is Admin
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-8"><input type="submit" class="btn btn-default"value="Update">  |  <a href="./">Cancel</a></div>
			</div>
		</form>
	</div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<?php require_once('../layout/footer.php'); ?>
