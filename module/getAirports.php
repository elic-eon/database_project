<?php

$airportOptions = '';

$__sql__ =  "SELECT ".
			"    airport.name AS name, ".
			"    airport.fullName AS fullName, ".
			"    country.fullName AS country ".
			"FROM ".
			"    airport JOIN country ON airport.country = country.name ".
			"ORDER BY country ";
$__sth__ = $db->prepare($__sql__);
$__sth__->execute();

$__airports__ = array();
while ($result = $__sth__->fetchObject()) {
	$__airports__[$result->country][] = array("name" => $result->name, "fullName" => $result->fullName);
}

foreach ($__airports__ as $country => $aps){
	$airportOptions .= '<optgroup label="'.$country.'">';
	foreach ($aps as $ap){
		$airportOptions .= '<option value="'.$ap["name"].'">'.$ap["name"].','.$ap["fullName"].'</option>';
	}
	$airportOptions .= '</optgroup>';
}

return $airportOptions;

?>