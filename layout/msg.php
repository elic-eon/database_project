<?php
if (isset($_SESSION['msg'])) {
?>
	<div class="row clear-padding">
		<div class="alert alert-info alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Hint</strong> <?php echo $_SESSION['msg'] ?>
		</div>
	</div>
<?php
	unset($_SESSION['msg']);
}
?>
