<?
require_once('config.php');
$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME";
try {
	$db = new PDO($dsn, "$DB_USER", "$DB_PASSWORD");
} catch(PDOException $e) {
	die('Could not connect to the database:<br/>' . $e);
}
?>