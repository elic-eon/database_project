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

INSERT INTO country (`name`, fullName) VALUES
('CHN', 'China'),
('JPN', 'Japan'),
('KOR', 'Korea'),
('TWN', 'Taiwan'),
('USA', 'United_States');</pre>
				</p>
			</li>
			<li>
				<h4>Airport</h4>
				<p>
					<pre>CREATE TABLE IF NOT EXISTS airport (
  `name` varchar(255) NOT NULL,
  fullName varchar(255) NOT NULL,
  longitude double NOT NULL,
  latitude double NOT NULL,
  country char(3) NOT NULL,
  timezone_minute int(11) NOT NULL,
  PRIMARY KEY  (`name`),
  KEY country (country)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO airport (`name`, fullName, longitude, latitude, country, timezone_minute) VALUES
('KHH', 'Kaohsiung,高雄', 23.010871, 120.666004, 'TWN', 480),
('NYK', 'Newyork,紐約', 40.705631, -73.978003, 'USA', -300),
('OSA', 'Osaka,大阪', 34.693738, 135.502165, 'JPN', 540),
('PEK', 'Beijing,北京', 39.90403, 116.407526, 'CHN', 480),
('PHL', 'Philadelphia,費城', 39.952335, -75.163789, 'USA', -300),
('PUS', 'Pusan,釜山', 35.179554, 129.075642, 'KOR', 540),
('SEL', 'Seoul,漢城', 21.688297, 107.821098, 'KOR', 540),
('SHA', 'Shanghai,上海', 31.230416, 121.473701, 'CHN', 480),
('TPE', 'Taipei,台北', 25.091075, 121.559834, 'TWN', 480),
('TYO', 'Tokyo,東京', 35.689487, 139.691706, 'JPN', 540);</pre>
				</p>
			</li>
			<li>
				<h4>Flight</h4>
				<p>
					<pre>CREATE TABLE IF NOT EXISTS flight (
  id int(10) unsigned NOT NULL auto_increment,
  flight_number varchar(255) NOT NULL,
  departure varchar(255) NOT NULL,
  destination varchar(255) NOT NULL,
  departure_date datetime NOT NULL,
  arrival_date datetime NOT NULL,
  price int(10) unsigned NOT NULL,
  PRIMARY KEY  (id),
  KEY departure (departure),
  KEY destination (destination)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES
('CX-420', 'KHH', 'OSA', '2014-05-06 01:00:00', '2014-05-07 15:10:00', 450),
('GE-605', 'NYK', 'TPE', '2014-05-07 01:30:00', '2014-05-07 14:20:00', 300),
('OZ-713', 'OSA', 'PUS', '2014-05-07 04:50:00', '2014-05-07 13:17:00', 340),
('BR-2149', 'PEK', 'KHH', '2014-05-07 20:08:00', '2014-05-08 21:45:00', 100),
('CI-910', 'PHL', 'TPE', '2014-05-08 01:03:00', '2014-05-08 09:50:00', 250),
('BR-398', 'PUS', 'SEL', '2014-05-08 23:20:00', '2014-05-09 17:10:00', 260),
('ZH-9095', 'SHA', 'PHL', '2014-05-08 21:46:00', '2014-05-09 19:37:00', 150),
('B7-7152', 'TYO', 'NYK', '2014-05-10 10:00:00', '2014-05-10 20:55:00', 420);
</pre>
				</p>
			</li>
		</ol>

    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<?php require_once('../layout/footer.php') ?>
