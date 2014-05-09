<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();
?>

<?php require_once('../layout/header.php') ?>
<?php require_once('../layout/msg.php') ?>
<div class="row">
    <div class="col-lg-12">
		<h1 class="page-header">About</h1>
		<h3>Reference</h3>
		<ol>
			<li><a href="http://zh.wikipedia.org/wiki/ISO_3166-1">Country Names</a></li>
			<li><a href="http://www.backpackers.com.tw/guide/index.php/%E4%B8%96%E7%95%8C%E5%90%84%E5%9C%8B%E6%99%82%E5%8D%80">Timezone</a></li>
			<li><a href="http://www.ting.com.tw/tour-info/air-name.htm">Airport Names</a></li>
			<li><a href="http://card.url.com.tw/realads/map_latlng.php">Location Search</a></li>
		</ol>
		<h3>API</h3>
		<ol>
			<li><a href="../api/airport">Airport List</a></li>
		</ol>
		<h3>Sample Data</h3>
		<ol>
			<li>
				<h4>Country</h4>
				<p>
					<pre>CREATE TABLE IF NOT EXISTS country (
  `name` char(3) NOT NULL,
  fullName varchar(255) NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO country VALUES('CHN', 'China');
INSERT INTO country VALUES('HKG', 'Hong_Kong');
INSERT INTO country VALUES('JPN', 'Japan');
INSERT INTO country VALUES('KOR', 'Korea');
INSERT INTO country VALUES('TWN', 'Taiwan');
INSERT INTO country VALUES('USA', 'United_States');
INSERT INTO country VALUES('ZZZ', 'Blablabla,範例國家');</pre>
				</p>
			</li>
			<li>
				<h4>Airport</h4>
				<p>
					<pre>CCREATE TABLE IF NOT EXISTS airport (
  `name` varchar(255) NOT NULL,
  fullName varchar(255) NOT NULL,
  longitude double NOT NULL,
  latitude double NOT NULL,
  country char(3) NOT NULL,
  timezone_minute int(11) NOT NULL,
  PRIMARY KEY  (`name`),
  KEY country (country)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO airport VALUES('DOH', 'Ddoohh', 123, 456, 'ZZZ', 240);
INSERT INTO airport VALUES('HKG', 'Hong_Kong,香港', 22.396428, 114.109497, 'HKG', 480);
INSERT INTO airport VALUES('KHH', 'Kaohsiung,高雄', 23.010871, 120.666004, 'TWN', 480);
INSERT INTO airport VALUES('MAN', 'Mmaann', 123, 456, 'ZZZ', 0);
INSERT INTO airport VALUES('NYK', 'Newyork,紐約', 40.705631, -73.978003, 'USA', -300);
INSERT INTO airport VALUES('OSA', 'Osaka,大阪', 34.693738, 135.502165, 'JPN', 540);
INSERT INTO airport VALUES('PEK', 'Beijing,北京', 39.90403, 116.407526, 'CHN', 480);
INSERT INTO airport VALUES('PHL', 'Philadelphia,費城', 39.952335, -75.163789, 'USA', -300);
INSERT INTO airport VALUES('PUS', 'Pusan,釜山', 35.179554, 129.075642, 'KOR', 540);
INSERT INTO airport VALUES('SEL', 'Seoul,漢城', 21.688297, 107.821098, 'KOR', 540);
INSERT INTO airport VALUES('SHA', 'Shanghai,上海', 31.230416, 121.473701, 'CHN', 480);
INSERT INTO airport VALUES('TPE', 'Taipei,台北', 25.091075, 121.559834, 'TWN', 480);
INSERT INTO airport VALUES('TYO', 'Tokyo,東京', 35.689487, 139.691706, 'JPN', 540);</pre>
				</p>
			</li>
			<li>
				<h4>Flight</h4>
				<p>
					<pre>CREATE TABLE IF NOT EXISTS flight (
  id int(10) unsigned NOT NULL auto_increment,
  flight_number varchar(10) NOT NULL,
  departure varchar(255) NOT NULL,
  destination varchar(255) NOT NULL,
  departure_date datetime NOT NULL,
  arrival_date datetime NOT NULL,
  price int(10) unsigned NOT NULL,
  PRIMARY KEY  (id),
  KEY departure (departure),
  KEY destination (destination),
  KEY flight_number (flight_number)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('CX-420', 'KHH', 'OSA', '2014-05-06 01:00:00', '2014-05-06 15:10:00', 450);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('GE-605', 'NYK', 'TPE', '2014-05-11 09:30:00', '2014-05-12 16:20:00', 500);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('OZ-713', 'OSA', 'PUS', '2014-05-07 04:50:00', '2014-05-07 13:17:00', 340);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('BR-2149', 'PEK', 'KHH', '2014-05-07 21:08:00', '2014-05-08 04:45:00', 100);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('CI-910', 'PHL', 'TPE', '2014-05-08 13:03:00', '2014-05-09 09:50:00', 250);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('BR-398', 'PUS', 'SEL', '2014-05-08 23:20:00', '2014-05-09 03:10:00', 260);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('ZH-9095', 'SHA', 'PHL', '2014-05-09 17:46:00', '2014-05-09 20:37:00', 150);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('B7-7152', 'TYO', 'NYK', '2014-05-08 19:00:00', '2014-05-08 20:00:00', 420);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TE-483', 'TYO', 'TPE', '2014-05-09 19:00:00', '2014-05-10 08:05:00', 290);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TH-123', 'TPE', 'HKG', '2014-05-01 10:00:00', '2014-05-01 11:30:00', 1);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('HKD-123', 'HKG', 'DOH', '2014-05-01 15:00:00', '2014-05-01 23:00:00', 2);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('DM-123', 'DOH', 'MAN', '2014-05-02 02:00:00', '2014-05-02 06:30:00', 3);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TM-123', 'TPE', 'MAN', '2014-05-01 10:00:00', '2014-05-01 19:00:00', 4);</pre>
				</p>
			</li>
			<li>
				<h4>Quick Recovery</h4>
				<p><a href="oneClickRecovery.php" class="btn btn-danger">One Click To Recovery Database</a></p>
				<textarea>DELETE FROM flight;
DELETE FROM airport;
DELETE FROM country;

INSERT INTO country VALUES('CHN', 'China');
INSERT INTO country VALUES('HKG', 'Hong_Kong');
INSERT INTO country VALUES('JPN', 'Japan');
INSERT INTO country VALUES('KOR', 'Korea');
INSERT INTO country VALUES('TWN', 'Taiwan');
INSERT INTO country VALUES('USA', 'United_States');
INSERT INTO country VALUES('ZZZ', 'Blablabla,範例國家');

INSERT INTO airport VALUES('DOH', 'Ddoohh', 123, 456, 'ZZZ', 240);
INSERT INTO airport VALUES('HKG', 'Hong_Kong,香港', 22.396428, 114.109497, 'HKG', 480);
INSERT INTO airport VALUES('KHH', 'Kaohsiung,高雄', 23.010871, 120.666004, 'TWN', 480);
INSERT INTO airport VALUES('MAN', 'Mmaann', 123, 456, 'ZZZ', 0);
INSERT INTO airport VALUES('NYK', 'Newyork,紐約', 40.705631, -73.978003, 'USA', -300);
INSERT INTO airport VALUES('OSA', 'Osaka,大阪', 34.693738, 135.502165, 'JPN', 540);
INSERT INTO airport VALUES('PEK', 'Beijing,北京', 39.90403, 116.407526, 'CHN', 480);
INSERT INTO airport VALUES('PHL', 'Philadelphia,費城', 39.952335, -75.163789, 'USA', -300);
INSERT INTO airport VALUES('PUS', 'Pusan,釜山', 35.179554, 129.075642, 'KOR', 540);
INSERT INTO airport VALUES('SEL', 'Seoul,漢城', 21.688297, 107.821098, 'KOR', 540);
INSERT INTO airport VALUES('SHA', 'Shanghai,上海', 31.230416, 121.473701, 'CHN', 480);
INSERT INTO airport VALUES('TPE', 'Taipei,台北', 25.091075, 121.559834, 'TWN', 480);
INSERT INTO airport VALUES('TYO', 'Tokyo,東京', 35.689487, 139.691706, 'JPN', 540);

INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('CX-420', 'KHH', 'OSA', '2014-05-06 01:00:00', '2014-05-06 15:10:00', 450);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('GE-605', 'NYK', 'TPE', '2014-05-11 09:30:00', '2014-05-12 16:20:00', 500);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('OZ-713', 'OSA', 'PUS', '2014-05-07 04:50:00', '2014-05-07 13:17:00', 340);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('BR-2149', 'PEK', 'KHH', '2014-05-07 21:08:00', '2014-05-08 04:45:00', 100);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('CI-910', 'PHL', 'TPE', '2014-05-08 13:03:00', '2014-05-09 09:50:00', 250);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('BR-398', 'PUS', 'SEL', '2014-05-08 23:20:00', '2014-05-09 03:10:00', 260);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('ZH-9095', 'SHA', 'PHL', '2014-05-09 17:46:00', '2014-05-09 20:37:00', 150);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('B7-7152', 'TYO', 'NYK', '2014-05-08 19:00:00', '2014-05-08 20:00:00', 420);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TE-483', 'TYO', 'TPE', '2014-05-09 19:00:00', '2014-05-10 08:05:00', 290);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TH-123', 'TPE', 'HKG', '2014-05-01 10:00:00', '2014-05-01 11:30:00', 1);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('HKD-123', 'HKG', 'DOH', '2014-05-01 15:00:00', '2014-05-01 23:00:00', 2);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('DM-123', 'DOH', 'MAN', '2014-05-02 02:00:00', '2014-05-02 06:30:00', 3);
INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES('TM-123', 'TPE', 'MAN', '2014-05-01 10:00:00', '2014-05-01 19:00:00', 4);</textarea>
			</li>
		</ol>

    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<?php require_once('../layout/footer.php') ?>
