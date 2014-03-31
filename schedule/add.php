<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();

if (!$_SESSION['isAuth'] || !$_SESSION['isAdmin']) {
	$redirectURL = PATH_ROOT_URL.'/error/403.php';
	header('Location: '.$redirectURL);
	exit;
}



require_once('../layout/header.php');
require_once('../layout/msg.php');

require_once('../module/db.php');
$sql = "SELECT id, name FROM airport";
$sth = $db->prepare($sql);
$sth->execute();

$airports = '';

while ($result = $sth->fetchObject()) {
	$airports .= '<option value="'.$result->id.'">'.$result->name.'</option>';
}
?>

<div class="row">
    <div class="col-lg-12">
    	<h1 class="page-header">Add Schedule</h1>
		<form action="add_func.php" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-sm-2 control-label">Flight Number</label>
				<div class="col-sm-4">
					<input name="flightNumber" type="text" class="form-control" required></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Departure</label>
				<div class="col-sm-4">
					<select name="departure_id" class="form-control">
						<?php echo $airports; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Destination</label>
				<div class="col-sm-4">
					<select name="destination_id" class="form-control">
						<?php echo $airports; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Departure Date</label>
				<div class="col-sm-4">
					<input name="departureDate" type="datetime-local" class="form-control" required></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Arrival Date</label>
				<div class="col-sm-4">
					<input name="arrivalDate" type="datetime-local" class = "form-control" required></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Price</label>
				<div class="col-sm-4">
					<input name="price" type="text" class = "form-control" required></p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-8"><input type="submit" class="btn btn-default" value="Create">  |  <a href="./">Cancel</a></div>
			</div>
		</form>
	</div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<?php require_once('../layout/footer.php'); ?>
