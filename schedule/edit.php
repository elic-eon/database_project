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

// Get airports
$sql = "SELECT id, name FROM airport";
$sth = $db->prepare($sql);
$sth->execute();
$airports = '';
while ($result = $sth->fetchObject()) {
	$airports .= '<option value="'.$result->id.'">'.$result->name.'</option>';
}

// Get schedule
$sql = "SELECT * FROM flight WHERE id = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_GET['id']));
$result = $sth->fetchObject();
require_once('../layout/header.php');
require_once('../layout/msg.php');
?>

<div class="row">
    <div class="col-lg-12">
    	<h1 class="page-header">Edit Schedule</h1>
		<form action="edit_func.php" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<input name="id" type="hidden" value="<?php echo $result->id ?>">
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Flight Number</label>
				<div class="col-sm-4">
					<input name="flightNumber" type="text" class="form-control" value="<?php echo $result->flight_number ?>" required></p>
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
					<input name="departureDate" type="datetime-local" class="form-control" value="<?php echo strftime('%Y-%m-%dT%H:%M:%S', strtotime($result->departure_date)) ?>" required></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Arrival Date</label>
				<div class="col-sm-4">
					<input name="arrivalDate" type="datetime-local" class = "form-control" value="<?php echo strftime('%Y-%m-%dT%H:%M:%S', strtotime($result->arrival_date)) ?>" required></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Price</label>
				<div class="col-sm-4">
					<input name="price" type="text" class = "form-control" value="<?php echo $result->price ?>" required></p>
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

<script type="text/javascript">
	$(function () {
		$('select[name=destination_id] option[value=<?php echo $result->destination_id ?>]').attr('selected', 'selected');
		$('select[name=departure_id] option[value=<?php echo $result->departure_id ?>]').attr('selected', 'selected');
	})
</script>
<?php require_once('../layout/footer.php'); ?>
