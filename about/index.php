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
					<pre></pre>
				</p>
			</li>
		</ol>

    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<?php require_once('../layout/footer.php') ?>
