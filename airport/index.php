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

<?php require_once('../module/generateOrderHtml.php') ?>
<?php require_once('../layout/header.php') ?>
<?php require_once('../layout/msg.php') ?>
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Airport Management</h1>
		<div class="well">
			<a class="btn btn-default" href="add.php"><i class="fa fa-plus"></i> Add New Airport</a>
		</div>
		<?php
			require_once('../module/db.php');

			$order = 'name ASC';
			$sth = null;

			// If user defines some kind of sort
			if (isset($_GET['orderKey']) && isset($_GET['orderDirection'])) {
				$orderKey = addslashes($_GET['orderKey']);
				$orderDirection = addslashes($_GET['orderDirection']);
				$order = "$orderKey $orderDirection, $order";
			}
			$sql = "SELECT * FROM airport ORDER BY $order";
			$sth = $db->prepare($sql);
			$sth->execute();	
		?>

		<table class="table table-condensed table-hover" id="datalist">
			<thead id="datalist_head">
				<tr>
					<th style="width: 150px;">Name<?php echo generateOrderHtml('name') ?></th>
					<th style="width: 150px;">Full Name<?php echo generateOrderHtml('fullName') ?></th>
					<th style="width: 150px;">Longitude<?php echo generateOrderHtml('longitude') ?></th>
					<th style="width: 150px;">Latitude<?php echo generateOrderHtml('latitude') ?></th>
					<th style="width: 150px;">Country<?php echo generateOrderHtml('country') ?></th>
					<th style="width: 150px;">Timezone<?php echo generateOrderHtml('timezone_minute') ?></th>
					<th>Operation</th>
				</tr>
			</thead>
			<tbody>
				<?php
					while ($result = $sth->fetchObject()) {
				?>
						<tr>
							<td style="width: 150px;"><?php echo $result->name ?></td>
							<td style="width: 150px;"><?php echo $result->fullName ?></td>
							<td style="width: 150px;"><?php echo $result->longitude ?></td>
							<td style="width: 150px;"><?php echo $result->latitude ?></td>
							<td style="width: 150px;"><?php echo $result->country ?></td>
							<td style="width: 150px;">
								<?php
									$timezone_minute = $result->timezone_minute;
									$type = ($timezone_minute>=0? '+': '-');
									$hour = abs($timezone_minute) / 60;
									$minute = $timezone_minute % 60;
									printf("UTC %s%02d:%02d", $type, $hour, $minute);
								?>
							</td>
							<td>
								<a class="btn btn-xs btn-default" href="edit.php?name=<?php echo $result->name ?>" title="Edit"><i class="fa fa-pencil"></i></a>
								<a class="btn btn-xs btn-danger" href="delete_func.php?name=<?php echo $result->name ?>" title="Delete"><i class="fa fa-trash-o"></i></a>
							</td>
						</tr>
				<?php
					}
				?>
			</tbody>
		</table>	
		</div>
		<!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<link href="<?php echo PATH_ROOT_URL; ?>/asset/css/table.css" rel="stylesheet">
<script src="<?php echo PATH_ROOT_URL; ?>/asset/js/table.js"></script>

<?php require_once('../layout/footer.php') ?>
