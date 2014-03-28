<?php require_once('layout/header_general.php') ?>

<div class="col-sm-4 col-sm-offset-4">
	<?php require_once('layout/msg.php') ?>
	<H2>Flight Schedule</H2>
</div>
<div class="col-sm-4 col-sm-offset-4">
	<ul class="nav nav-pills nav-stacked">
		<?php if ($_SESSION['isAuth']): ?>
			<li><a href="schedule/">Flight Management</a></li>
			<li><a href="user/logout.php">Logout</a></li>
		<?php else:?>
			<li><a href="user/register.php">Register</a></li>
			<li><a href="user/login.php">Login</a></li>
		<?php endif;?>
	</ul>
</div>

<?php require_once('layout/footer_general.php') ?>
