<?php

// ini_set('display_errors', 1);
// error_reporting(~0);

require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();

require_once('../module/db.php');

$sql = 	"DELETE FROM flight;".
		"DELETE FROM airport;".
		"DELETE FROM country;".
		"DELETE FROM user;".
		"".
		"INSERT INTO country VALUES('CHN', 'China');".
		"INSERT INTO country VALUES('HKG', 'Hong_Kong');".
		"INSERT INTO country VALUES('JPN', 'Japan');".
		"INSERT INTO country VALUES('KOR', 'Korea');".
		"INSERT INTO country VALUES('TWN', 'Taiwan');".
		"INSERT INTO country VALUES('USA', 'United_States');".
		"INSERT INTO country VALUES('ZZZ', 'Blablabla,範例國家');".
		"".
		"INSERT INTO airport VALUES('DOH', 'Ddoohh', 123, 456, 'ZZZ', 240);".
		"INSERT INTO airport VALUES('HKG', 'Hong_Kong,香港', 22.396428, 114.109497, 'HKG', 480);".
		"INSERT INTO airport VALUES('KHH', 'Kaohsiung,高雄', 23.010871, 120.666004, 'TWN', 480);".
		"INSERT INTO airport VALUES('MAN', 'Mmaann', 123, 456, 'ZZZ', 0);".
		"INSERT INTO airport VALUES('NYK', 'Newyork,紐約', 40.705631, -73.978003, 'USA', -300);".
		"INSERT INTO airport VALUES('OSA', 'Osaka,大阪', 34.693738, 135.502165, 'JPN', 540);".
		"INSERT INTO airport VALUES('PEK', 'Beijing,北京', 39.90403, 116.407526, 'CHN', 480);".
		"INSERT INTO airport VALUES('PHL', 'Philadelphia,費城', 39.952335, -75.163789, 'USA', -300);".
		"INSERT INTO airport VALUES('PUS', 'Pusan,釜山', 35.179554, 129.075642, 'KOR', 540);".
		"INSERT INTO airport VALUES('SEL', 'Seoul,漢城', 21.688297, 107.821098, 'KOR', 540);".
		"INSERT INTO airport VALUES('SHA', 'Shanghai,上海', 31.230416, 121.473701, 'CHN', 480);".
		"INSERT INTO airport VALUES('TPE', 'Taipei,台北', 25.091075, 121.559834, 'TWN', 480);".
		"INSERT INTO airport VALUES('TYO', 'Tokyo,東京', 35.689487, 139.691706, 'JPN', 540);".
		"".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('CX-420', 'KHH', 'OSA', '2014-05-06 01:00:00', '2014-05-06 15:10:00', 450);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('GE-605', 'NYK', 'TPE', '2014-05-11 09:30:00', '2014-05-12 16:20:00', 500);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('OZ-713', 'OSA', 'PUS', '2014-05-07 04:50:00', '2014-05-07 13:17:00', 340);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('BR-2149', 'PEK', 'KHH', '2014-05-07 21:08:00', '2014-05-08 04:45:00', 100);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('CI-910', 'PHL', 'TPE', '2014-05-08 13:03:00', '2014-05-09 09:50:00', 250);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('BR-398', 'PUS', 'SEL', '2014-05-08 23:20:00', '2014-05-09 03:10:00', 260);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('ZH-9095', 'SHA', 'PHL', '2014-05-09 17:46:00', '2014-05-09 20:37:00', 150);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('B7-7152', 'TYO', 'NYK', '2014-05-08 19:00:00', '2014-05-08 20:00:00', 420);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TE-483', 'TYO', 'TPE', '2014-05-09 19:00:00', '2014-05-10 08:05:00', 290);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TH-123', 'TPE', 'HKG', '2014-05-01 10:00:00', '2014-05-01 11:30:00', 1);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('HKD-123', 'HKG', 'DOH', '2014-05-01 15:00:00', '2014-05-01 23:00:00', 2);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('DM-123', 'DOH', 'MAN', '2014-05-02 02:00:00', '2014-05-02 06:30:00', 3);".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TM-123', 'TPE', 'MAN', '2014-05-01 10:00:00', '2014-05-01 19:00:00', 4);".
		"".
		"INSERT INTO `user` VALUES(69, 'admin', 'FuDnRi4QwLi9I', 1, '');".
		"INSERT INTO `user` VALUES(70, 'user', 'Fu85wN1xEgq9E', 0, '');";

$db->query($sql);

$_SESSION['msg'] = 'The database is fixed.';
$redirectURL = './';
header('Location: '.$redirectURL);
?>