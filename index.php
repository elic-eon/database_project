<?php require_once('layout/header_general.php') ?>

<div class="col-sm-4 col-sm-offset-4">
	<?php require_once('layout/msg.php') ?>
	<H2>Airline Company</H2>
</div>
<div class="col-sm-4 col-sm-offset-4">
	<ul class="nav nav-pills nav-stacked">
		<?php if (!$_SESSION['isAuth']): ?>
			<li><a href="ticket/search.php"><i class="fa fa-search fa-fw"></i> Ticket Search</a></li>
			<li><a href="user/register.php"><i class="fa fa-user fa-fw"></i> Register</a></li>
			<li><a href="user/login.php"><i class="fa fa-sign-in fa-fw"></i> Login</a></li>
		<?php else:?>
			<li><a href="schedule/"><i class="fa fa-plane fa-fw"></i> Management System</a></li>
			<li><a href="user/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
		<?php endif;?>
	</ul>
</div>

<?php require_once('layout/footer_general.php') ?>
