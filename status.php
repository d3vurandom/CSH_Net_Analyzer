<?php
	require ('include/database.php');
	require('include/functions.php');
	$serverID = $_GET['serverID'];
?>
<html>
<head>
<title>Computer Science House Network and Server Status</title>
 <link href="include/style.css" rel="stylesheet" type="text/css">
 <script language="javascript" src="include/ajax.php"></script>
 <script language="javascript" src="include/prototype.js"></script>
 <script language="javascript" src="include/scriptaculous.js"></script>
 <script language="javascript" src="include/effects.js"></script>

</head>
<body">
<div id="header">
	<div id="headerContainer">
		<h1>Computer Science House Network and Server Status</h1>
	</div>
</div>
<div id="bar">
</div>
<br />
<center>
<div id="container">
	<div id="Status">
		<?php
		$result=dbQuery("SELECT * FROM `server` WHERE serverID = $serverID");
		$num_rows = mysql_num_rows($result);
		
		if($num_rows > 0){
			echo "<h3><center>CSH Server Status</center></h3>";
			echo "<table border=\"0\">";
			echo "<tr>";
			echo "<td><center><h2>Hostname</h2></center></td>";
			echo "<td><center><h2>IP</h2></center></td>";
			echo "<td><center><h2>Description</h2></center></td>";
			echo "<td><center><h2>Uptime</h2></center></td>";
			echo "<td><center><h2>lastSeen</h2></center></td>";
			echo "<td><center><h2>Status</h2></center></td>";
			echo "</tr>";
			while($row = mysql_fetch_array($result)){
				$status = "";
				if($row['alertCount'] > 0){
					$status = "<div id=\"offline\">Offline</div>";
				}
				else{
					$status = "<div id=\"online\">Online</div>";
					
					if(isFaulty($row['serverID'])){
						$status = "<div id=\"faulty\">Faulty</div>";
					}
				}
				echo "<div id=\"server" . $row['serverID'] . "\">";
				$lastSeenSeconds = (strtotime("now") - strtotime($row['lastSeen']));
				echo "<tr>";
				echo "<td>". $row['hostname'] . "</td>";
				echo "<td>" . $row['remoteIP'] . "</td>";
				echo "<td>" . $row['comment'] . "</td>";
				echo "<td>" . sec_to_time($row['uptime']) . "</td>";
				echo "<td>" . "<div id=\"lastSeen" . $row['serverID'] ."\"></div>". "</td>";
				//echo "<td>" . "<div id=\"lastSeen\">" .sec_to_time($lastSeenSeconds) . " ago </div></td>";
				echo "<td>" . $status . "</td>";
				echo "</tr>";
				echo "</div>\n";
			}
			echo "</table>";
		}
		?>
	</div>
</div>
</center>
</body>
</html>