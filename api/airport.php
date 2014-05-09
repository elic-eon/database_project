<?php
	header('Content-Type: application/json; charset=utf-8');
	require_once('../config.php');
	require_once('../module/db.php');

	$sql = "SELECT name, fullName, country FROM airport ORDER BY country";
	$sth = $db->prepare($sql);
	$sth->execute();

	$airports = array();
	while ($result = $sth->fetchObject()) {
		$airports[$result->country][] = array("name" => $result->name, "fullName" => $result->fullName);
	}

	echo json_encode($airports);
?>