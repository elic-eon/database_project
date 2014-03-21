<? session_start(); ?>
<? require_once('layout/header.php') ?>
<h1>403 Forbidden</h1>
<p>
	<? if ($_SESSION['isAuth']): ?>
		You don't have permission to access.
	<? else: ?>
		Please <a href="login.php">login</a> first.
	<? endif; ?>
</p>
<a href="./">Go Home</a>
<? require_once('layout/footer.php') ?>