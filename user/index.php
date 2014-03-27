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
		<h1 class="page-header">User</h1>
		<div class="well">
			<a class="btn btn-default" href="#"><i class="fa fa-trash-o"></i> Delete</a>
		</div>
		<?php
			require_once('../module/db.php');

			$order = 'id ASC';
			$sth = null;

			// If user defines some kind of sort
			if (isset($_GET['orderKey']) && isset($_GET['orderDirection'])) {
				$orderKey = addslashes($_GET['orderKey']);
				$orderDirection = addslashes($_GET['orderDirection']);
				$order = "$orderKey $orderDirection, $order";
			}
			$sql = "SELECT * FROM user ORDER BY $order";
			$sth = $db->prepare($sql);
			$sth->execute();	
		?>

		<table class="table table-condensed table-hover" id="schedule">
			<thead id="schedule_head">
				<tr>
					<th>ID<?php echo generateOrderHtml('id') ?></th>
					<th>Account<?php echo generateOrderHtml('account') ?></th>
					<th>Authority<?php echo generateOrderHtml('is_admin') ?></th>
					<th>Operation</th>
				</tr>
			</thead>
			<tbody>
				<?php
					while ($result = $sth->fetchObject()) {
				?>
						<tr>
							<td><?php echo $result->id ?></td>
							<td><?php echo $result->account ?></td>
							<td><?php echo ($result->is_admin)? 'Admin': 'User' ?></td>
							<td>
								<a class="btn btn-xs btn-warning" href="edit.php?id=<?php echo $result->id ?>">Edit</a>
								<a class="btn btn-xs btn-danger" href="delete_func.php?id=<?php echo $result->id ?>">Delete</a>
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

<style type="text/css">
	@media(min-width:767px) {
		.affix-top {
			/*position: inherit;*/
		}

		.affix {
			position: fixed;
			top: 51px;
			left: 281px;
			right: 30px;
			background-color: #fff;
			border-bottom: 2px solid #ddd;
		}

		.affix>tr>th {
			border-bottom: 0px solid #ddd !important;
		}

		.affix-bottom {
			position: absolute;
		}
	}
</style>

<script type="text/javascript">
	$(function () {
		// top: 51 + $('#schedule_head').outerHeight(true),
		$('#schedule_head').affix({
			offset: {
				top: $('#schedule_head').position().top,
				bottom: $('#page-wrapper').height() - $('#schedule').position().top - $('#schedule').outerHeight()
			}
		});
	});
</script>
<?php require_once('../layout/footer.php') ?>
