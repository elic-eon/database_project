<?php session_start(); ?>
<?php require_once('layout/header.php') ?>
<h1>403 Forbidden</h1>
<p>
	<?php if ($_SESSION['isAuth']): ?>
		You don't have permission to access.
	<?php else: ?>
		Please <a href="login.php">login</a> first.
	<?php endif; ?>
</p>
<a href="./">Go Home</a>
<?php require_once('layout/footer.php') ?>
