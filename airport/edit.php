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

// Get countries
$sql = "SELECT * FROM country";
$sth = $db->prepare($sql);
$sth->execute();
$countries = '';
while ($result = $sth->fetchObject()) {
	$countries .= '<option value="'.$result->name.'">'.$result->fullName.'</option>';
}

$sql = "SELECT * FROM airport WHERE name = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_GET['name']));
$result = $sth->fetchObject();
require_once('../layout/header.php');
require_once('../layout/msg.php');
?>

<div class="row">
    <div class="col-lg-12">
    	<h1 class="page-header">Edit Airport</h1>
		<form action="edit_func.php" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<input name="oldName" type="hidden" value="<?php echo $result->name ?>">
			</div>			
			<div class="form-group">
				<label class="col-sm-2 control-label">Name</label>
				<div class="col-sm-4">
					<input name="newName" type="text" class="form-control" value="<?php echo $result->name ?>" required></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Full Name</label>
				<div class="col-sm-4">
					<input name="fullName" type="text" class="form-control" value="<?php echo $result->fullName ?>" required></p>
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
				<label class="col-sm-2 control-label">Country</label>
				<div class="col-sm-4">
					<select name="country" class="form-control">
						<?php echo $countries; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Timezone</label>
				<div class="col-sm-1">
					<select name="timezone_type" class="form-control">
						<option value="1">+</option>
						<option value="-1">-</option>
					</select>
				</div>
				<div class="col-sm-2">
					<select name="timezone_hour" class="form-control">
						<option value="0">00</option>
						<option value="1">01</option>
						<option value="2">02</option>
						<option value="3">03</option>
						<option value="4">04</option>
						<option value="5">05</option>
						<option value="6">06</option>
						<option value="7">07</option>
						<option value="8">08</option>
						<option value="9">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
					</select>
				</div>
				<label class="control-label pull-left">:</label>
				<div class="col-sm-2">
					<select name="timezone_minute" class="form-control">
						<option value="0">00</option>
						<option value="15">15</option>
						<option value="30">30</option>
						<option value="45">45</option>
					</select>
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

<?php
	$timezone_minute = $result->timezone_minute;
	$type = ($timezone_minute>=0? '1': '-1');
	$hour = floor($timezone_minute / 60);
	$minute = $timezone_minute % 60;
?>
<script type="text/javascript">
	$(function () {
		$('select[name=country] option[value=<?php echo $result->country ?>]').attr('selected', 'selected');
		$('select[name=timezone_type] option[value=<?php echo $type ?>]').attr('selected', 'selected');
		$('select[name=timezone_hour] option[value=<?php echo $hour ?>]').attr('selected', 'selected');
		$('select[name=timezone_minute] option[value=<?php echo $minute ?>]').attr('selected', 'selected');

	})
</script>
<?php require_once('../layout/footer.php'); ?>
