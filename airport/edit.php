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
$sql = "SELECT * FROM airport WHERE id = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_GET['id']));
$result = $sth->fetchObject();
require_once('../layout/header.php');
require_once('../layout/msg.php');
?>

<div class="row">
    <div class="col-lg-12">
    	<h1 class="page-header">Edit Airport</h1>
		<form action="edit_func.php" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<input name="id" type="hidden" value="<?php echo $result->id ?>">
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Name</label>
				<div class="col-sm-4">
					<input name="name" type="text" class="form-control" value="<?php echo $result->name ?>" required></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Longitude</label>
				<div class="col-sm-4">
					<input name="longitude" type="text" class="form-control" value="<?php echo $result->longitude ?>" required></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Latitude</label>
				<div class="col-sm-4">
					<input name="latitude" type="text" class="form-control" value="<?php echo $result->latitude ?>" required></p>
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
