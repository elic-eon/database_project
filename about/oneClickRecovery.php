<?php

require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();

require_once('../module/db.php');

// $sql = 	"DELETE FROM flight;".
// 		"DELETE FROM airport;".
// 		"DELETE FROM country;".
// 		"DELETE FROM user;".
// 		"".
// 		"INSERT INTO country VALUES('CHN', 'China');".
// 		"INSERT INTO country VALUES('HKG', 'Hong_Kong');".
// 		"INSERT INTO country VALUES('JPN', 'Japan');".
// 		"INSERT INTO country VALUES('KOR', 'Korea');".
// 		"INSERT INTO country VALUES('TWN', 'Taiwan');".
// 		"INSERT INTO country VALUES('USA', 'United_States');".
// 		"INSERT INTO country VALUES('ZZZ', 'Blablabla,範例國家');".
// 		"".
// 		"INSERT INTO airport VALUES('DOH', 'Ddoohh', 123, 456, 'ZZZ', 240);".
// 		"INSERT INTO airport VALUES('HKG', 'Hong_Kong,香港', 22.396428, 114.109497, 'HKG', 480);".
// 		"INSERT INTO airport VALUES('KHH', 'Kaohsiung,高雄', 23.010871, 120.666004, 'TWN', 480);".
// 		"INSERT INTO airport VALUES('MAN', 'Mmaann', 123, 456, 'ZZZ', 0);".
// 		"INSERT INTO airport VALUES('NYK', 'Newyork,紐約', 40.705631, -73.978003, 'USA', -300);".
// 		"INSERT INTO airport VALUES('OSA', 'Osaka,大阪', 34.693738, 135.502165, 'JPN', 540);".
// 		"INSERT INTO airport VALUES('PEK', 'Beijing,北京', 39.90403, 116.407526, 'CHN', 480);".
// 		"INSERT INTO airport VALUES('PHL', 'Philadelphia,費城', 39.952335, -75.163789, 'USA', -300);".
// 		"INSERT INTO airport VALUES('PUS', 'Pusan,釜山', 35.179554, 129.075642, 'KOR', 540);".
// 		"INSERT INTO airport VALUES('SEL', 'Seoul,漢城', 21.688297, 107.821098, 'KOR', 540);".
// 		"INSERT INTO airport VALUES('SHA', 'Shanghai,上海', 31.230416, 121.473701, 'CHN', 480);".
// 		"INSERT INTO airport VALUES('TPE', 'Taipei,台北', 25.091075, 121.559834, 'TWN', 480);".
// 		"INSERT INTO airport VALUES('TYO', 'Tokyo,東京', 35.689487, 139.691706, 'JPN', 540);".
// 		"".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('CX-420', 'KHH', 'OSA', '2014-05-06 01:00:00', '2014-05-06 15:10:00', 450);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('GE-605', 'NYK', 'TPE', '2014-05-11 09:30:00', '2014-05-12 16:20:00', 500);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('OZ-713', 'OSA', 'PUS', '2014-05-07 04:50:00', '2014-05-07 13:17:00', 340);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('BR-2149', 'PEK', 'KHH', '2014-05-07 21:08:00', '2014-05-08 04:45:00', 100);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('CI-910', 'PHL', 'TPE', '2014-05-08 13:03:00', '2014-05-09 09:50:00', 250);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('BR-398', 'PUS', 'SEL', '2014-05-08 23:20:00', '2014-05-09 03:10:00', 260);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('ZH-9095', 'SHA', 'PHL', '2014-05-09 17:46:00', '2014-05-09 20:37:00', 150);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('B7-7152', 'TYO', 'NYK', '2014-05-08 19:00:00', '2014-05-08 20:00:00', 420);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TE-483', 'TYO', 'TPE', '2014-05-09 19:00:00', '2014-05-10 08:05:00', 290);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TH-123', 'TPE', 'HKG', '2014-05-01 10:00:00', '2014-05-01 11:30:00', 1);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('HKD-123', 'HKG', 'DOH', '2014-05-01 15:00:00', '2014-05-01 23:00:00', 2);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('DM-123', 'DOH', 'MAN', '2014-05-02 02:00:00', '2014-05-02 06:30:00', 3);".
// 		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TM-123', 'TPE', 'MAN', '2014-05-01 10:00:00', '2014-05-01 19:00:00', 4);".
// 		"".
// 		"INSERT INTO `user` VALUES(69, 'admin', 'FuDnRi4QwLi9I', 1, '');".
// 		"INSERT INTO `user` VALUES(70, 'user', 'Fu85wN1xEgq9E', 0, '');";

$sql = 	"DELETE FROM flight;".
		"DELETE FROM airport;".
		"DELETE FROM country;".
		"DELETE FROM user;".
		"".
		"INSERT INTO country (name, fullName) VALUES".
		"('CN', 'China'),".
		"('DOH', 'Doha'),".
		"('JP', 'Japan'),".
		"('RUS', 'Russian_Federation'),".
		"('SIN', 'Singapore'),".
		"('TWN', 'Taiwan'),".
		"('UK', 'United_Kingdom'),".
		"('US', 'United_State');".
		"".
		"INSERT INTO airport (name, fullName, longitude, latitude, country, timezone_minute) VALUES".
		"('DOH', 'Doha_International_Airport', 123, 456, 'DOH', 240),".
		"('HKG', 'Hong_Kong_International_Airport', 123, 456, 'CN', 480),".
		"('HND', 'Tokyo_International_Airport', 123, 456, 'JP', 540),".
		"('KHH', 'Kaohsiung_International_Airport', 123, 456, 'TWN', 480),".
		"('LCY', 'London_City_Airport', 123, 456, 'UK', 0),".
		"('LED', 'Aeroport_Pulkovo', 123, 456, 'RUS', 240),".
		"('LHR', 'London_Heathrow_Airport', 123, 456, 'UK', 0),".
		"('LTN', 'London_Luton_Airport', 123, 456, 'UK', 0),".
		"('MAN', 'Manchester_Airport', 123, 456, 'UK', 0),".
		"('NGO', 'Chūbu_Centrair_International_Airport', 123, 456, 'JP', 540),".
		"('NRT', 'Narita_International_Airport', 123, 456, 'JP', 540),".
		"('SIN', 'Singapore_International_Airport', 123, 456, 'SIN', 480),".
		"('TCH', 'Taichung_Airport', 123, 456, 'TWN', 480),".
		"('TPE', 'Taipei_Touyuan_International_Airport', 123, 456, 'TWN', 480);".
		"".
		"INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES".
		"('JP-123','TPE','HND','2014-05-01 10:00:00','2014-05-01 12:00:00',6000),".
		"('TM-123','TPE','MAN','2014-05-01 10:00:00','2014-05-01 19:00:00',20000),".
		"('TH-123','TPE','HKG','2014-05-01 10:00:00','2014-05-01 11:30:00',4000),".
		"('THK-123','TPE','HKG','2014-05-01 13:00:00','2014-05-01 20:22:00',13000),".
		"('JP-124','TPE','NGO','2014-04-13 09:00:00','2014-04-13 12:27:00',1200),".
		"('HG-128','TPE','HKG','2014-04-15 06:10:00','2014-04-15 07:41:00',2980),".
		"('HK-228','KHH','HKG','2014-04-16 14:20:00','2014-04-16 15:51:00',6000),".
		"('HN-225','KHH','HND','2014-04-10 12:17:00','2014-04-10 16:44:00',3998),".
		"('HK-328','TCH','HKG','2014-04-10 15:22:00','2014-04-10 16:53:00',3500),".
		"('SI-327','TCH','SIN','2014-04-27 10:00:00','2014-04-27 14:10:00',8000),".
		"('HN-325','TCH','HND','2014-04-16 14:20:00','2014-04-16 18:27:00',6999),".
		"('DH-429','NGO','DOH','2014-04-20 12:00:00','2014-04-20 19:00:00',20000),".
		"('LC-413','NGO','LCY','2014-04-20 10:00:00','2014-04-20 14:00:00',21355),".
		"('TP-421','NGO','TPE','2014-04-30 17:00:00','2014-04-30 19:27:00',5123),".
		"('HK-428','NGO','HKG','2014-04-21 11:07:00','2014-04-21 14:07:00',6543),".
		"('JPM-123','HND','MAN','2014-05-01 14:00:00','2014-05-01 23:00:00',15000),".
		"('JHK-123','HND','HKG','2014-05-01 15:08:00','2014-05-01 19:00:00',12000),".
		"('TP-521','HND','TPE','2014-04-24 10:00:00','2014-04-24 13:07:00',1630),".
		"('NR-526','HND','NRT','2014-04-15 10:15:00','2014-04-15 11:16:00',1980),".
		"('KH-722','SIN','KHH','2014-04-29 16:13:00','2014-04-29 20:23:00',12377),".
		"('JP-725','SIN','HND','2014-04-17 06:00:00','2014-04-17 13:00:00',4830),".
		"('HK-728','SIN','HKG','2014-04-18 07:25:00','2014-04-18 11:07:00',13333),".
		"('TP-721','SIN','TPE','2014-05-01 17:01:00','2014-05-01 21:32:00',16875),".
		"('HKD-123','HKG','DOH','2014-05-01 15:00:00','2014-05-01 23:00:00',10000),".
		"('SI-827','HKG','SIN','2014-04-13 17:00:00','2014-04-13 20:42:00',16999),".
		"('TP-821','HKG','TPE','2014-04-23 10:50:00','2014-04-23 12:21:00',6111),".
		"('TP-822','HKG','KHH','2014-05-01 12:00:00','2014-05-01 13:35:00',3999),".
		"('LE-814','HKG','LED','2014-04-20 13:00:00','2014-04-20 19:00:00',16875),".
		"('TC-823','HKG','TCH','2014-04-26 10:12:00','2014-04-26 11:47:00',4895),".
		"('DM-123','DOH','MAN','2014-05-02 02:00:00','2014-05-02 06:30:00',8000),".
		"('SI-927','DOH','SIN','2014-04-25 10:00:00','2014-04-25 20:15:00',19387),".
		"('HG-148','LED','HKG','2014-04-27 09:00:00','2014-04-27 22:07:00',14960),".
		"('NR-146','LED','NRT','2014-04-25 04:00:00','2014-04-25 19:32:00',17992);".
		"".
		"INSERT INTO `user` VALUES(69, 'admin', 'FuDnRi4QwLi9I', 1, '');".
		"INSERT INTO `user` VALUES(70, 'user', 'Fu85wN1xEgq9E', 0, '');";

$db->query($sql);

$_SESSION['msg'] = 'The database is fixed.';
$redirectURL = './';
header('Location: '.$redirectURL);
?>