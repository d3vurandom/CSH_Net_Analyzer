<?php
	require ('../include/database.php');
	require('../include/functions.php');

	$result=dbQuery("SELECT * FROM `server`");
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
			$lastSeenSeconds = (strtotime("now") - strtotime($row['lastSeen']));
			echo "<tr>";
			echo "<td>". $row['hostname'] . "</td>";
			echo "<td>" . $row['remoteIP'] . "</td>";
			echo "<td>" . $row['comment'] . "</td>";
			echo "<td>" . sec_to_time($row['uptime']) . "</td>";
			echo "<td>" . "<div id=\"lastSeen\">" .sec_to_time($lastSeenSeconds) . " ago </div></td>";
			echo "<td>" . $status . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
?>