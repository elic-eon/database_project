<? require_once('layout/header.php') ?>
<? require_once('layout/msg.php') ?>
<ul>
	<li><a href="register.php"><h3>Register</h3></a></li>
	<li>
		<? if ($_SESSION['isAuth']): ?>
			<a href="logout.php"><h3>Logout</h3></a>
		<? else:?>
			<a href="login.php"><h3>Login</h3></a>
		<? endif;?>
	</li>
	<li><a href="flight.php"><h3>Flight</h3></a></li>
</ul>
<? require_once('layout/footer.php') ?>
