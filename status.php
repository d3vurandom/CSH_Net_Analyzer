<?php
	require ('include/database.php');
	require('include/functions.php');
	$serverID = $_GET['serverID'];
?>
<html>
<head>
<title>Computer Science House Network and Server Status</title>
 <link href="include/style.css" rel="stylesheet" type="text/css">
 <script language="javascript" src="include/prototype.js"></script>
 <script language="javascript" src="include/scriptaculous.js"></script>
 <script language="javascript" src="include/effects.js"></script>
</head>
<body>
<div id="header">
	<div id="headerContainer">
		<h1><a href="index.php">Computer Science House Network and Server Status</a></h1>
	</div>
</div>
<div id="bar">
</div>
<br />
<center>
<div id="container">
	<div id="status">
		
		<?php
		$result=dbQuery("SELECT * FROM `server` WHERE serverID = $serverID");
		$num_rows = mysql_num_rows($result);
		$row = mysql_fetch_array($result);
		
		$lastSeenSeconds = (strtotime("now") - strtotime($row['lastSeen']));
		
		if($num_rows > 0 && $num_rows < 2){
			echo "<center>";
			echo "<h1>" . $row['hostname'] . "'s Status</h1>";
			echo "<table width=\"400\"";
			echo "<tr>";
				echo "<td><h2>Hostname:</h2></td>";
				echo "<td>" . $row['hostname'] . "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td><h2>IP Address:</h2></td>";
				echo "<td>" . $row['remoteIP'] . "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td><h2>MAC Address:</h2></td>";
				echo "<td>" . $row['primaryNicMac'] . "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td><h2>Description:</h2></td>";
				echo "<td>" . $row['comment'] . "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td><h2>Server Uptime:</h2></td>";
				echo "<td>" . sec_to_time($row['uptime']) . "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td><h2>Last Checkin:</h2></td>";
				echo "<td>" . sec_to_time($lastSeenSeconds) . " ago</td>";
			echo "</tr>";
			echo "</table>";
			echo "</center>";

			
		}
		$result = dbQuery("SELECT `smartID` FROM `smartStatus` WHERE serverID = $serverID ORDER by timestamp DESC LIMIT 1");
		$row = mysql_fetch_array($result);
		$smartID = $row['smartID'];
		$result = dbQuery("SELECT * FROM `smartStatus` WHERE `smartID` = '$smartID' ORDER by `deviceName` ASC");
		$num_rows = mysql_num_rows($result);
		echo "<br><h3>Smart Status</h3><br>";
		
		if($num_rows < 1){
			echo "No Smart Status data or this server does not support smart";
		}
		else{
			echo "<table>";
			echo "<tr>";
				echo "<td><b>Drive</b></td>";
				echo "<td><b>Read Errors</b></td>";
				echo "<td><b>Reallocated Sectors</b></td>";
				echo "<td><b>Seek Errors</b></td>";
				echo "<td><b>Power On Hours</b></td>";
				echo "<td><b>Power Cycles</b></td>";
				echo "<td><b>Temperature</b></td>";
				echo "<td><b>Pending Sectors</b></td>";
				echo "<td><b>Uncorrectable Sectors</b></td>";
				echo "<td><b>CRC Errors</b></td>";
				echo "<td><b>Multi Zone Errors</b></td>";
			echo "</tr>";
			
			while($row = mysql_fetch_array($result)){
				echo "<tr>";
					echo "<td><b>" . $row['deviceName'] . "</b></td>";
					echo "<td>";
						if($row['Raw_Read_Error_Rate'] > 0){
							echo "<div class=\"badSmartStatusValue\">" . $row['Raw_Read_Error_Rate'] . "</div>";
						}
						else{
							echo "<div class=\"goodSmartStatusValue\">" . $row['Raw_Read_Error_Rate'] . "</div>";
							}
					echo "</td>";
					echo "<td>";
						if($row['Reallocated_Sector_Ct'] > 0){
							echo "<div class=\"badSmartStatusValue\">" . $row['Reallocated_Sector_Ct'] . "</div>";
						}
						else{
							echo "<div class=\"goodSmartStatusValue\">" . $row['Reallocated_Sector_Ct'] . "</div>";
							}
					echo "</td>";
					echo "<td>";
						if($row['Seek_Error_Rate'] > 0){
							echo "<div class=\"badSmartStatusValue\">" . $row['Seek_Error_Rate'] . "</div>";
						}
						else{
							echo "<div class=\"goodSmartStatusValue\">" . $row['Seek_Error_Rate'] . "</div>";
							}
					echo "</td>";
					echo "<td>";
							echo "<div class=\"goodSmartStatusValue\">" . $row['Power_On_Hours'] . "</div>";
					echo "</td>";
					echo "<td>";
						if($row['Power_Cycle_Count'] > 1000){
							echo "<div class=\"badSmartStatusValue\">" . $row['Power_Cycle_Count'] . "</div>";
						}
						else{
							echo "<div class=\"goodSmartStatusValue\">" . $row['Power_Cycle_Count'] . "</div>";
							}
					echo "</td>";
					echo "<td>";
						if($row['Temperature_Celsius'] > 44){
							echo "<div class=\"badSmartStatusValue\">" . $row['Temperature_Celsius'] . "</div>";
						}
						else{
							echo "<div class=\"goodSmartStatusValue\">" . $row['Temperature_Celsius'] . "</div>";
							}
					echo "</td>";
					echo "<td>";
						if($row['Current_Pending_Sector'] > 0){
							echo "<div class=\"badSmartStatusValue\">" . $row['Current_Pending_Sector'] . "</div>";
						}
						else{
							echo "<div class=\"goodSmartStatusValue\">" . $row['Current_Pending_Sector'] . "</div>";
							}
					echo "</td>";
					echo "<td>";
						if($row['Offline_Uncorrectable'] > 0){
							echo "<div class=\"badSmartStatusValue\">" . $row['Offline_Uncorrectable'] . "</div>";
						}
						else{
							echo "<div class=\"goodSmartStatusValue\">" . $row['Offline_Uncorrectable'] . "</div>";
							}
					echo "</td>";
					echo "<td>";
						if($row['UDMA_CRC_Error_Count'] > 0){
							echo "<div class=\"badSmartStatusValue\">" . $row['UDMA_CRC_Error_Count'] . "</div>";
						}
						else{
							echo "<div class=\"goodSmartStatusValue\">" . $row['UDMA_CRC_Error_Count'] . "</div>";
							}
					echo "</td>";
					echo "<td>";
						if($row['Multi_Zone_Error_Rate'] > 0){
							echo "<div class=\"badSmartStatusValue\">" . $row['Multi_Zone_Error_Rate'] . "</div>";
						}
						else{
							echo "<div class=\"goodSmartStatusValue\">" . $row['Multi_Zone_Error_Rate'] . "</div>";
							}
					echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
		?>
		<br>
	</div>
</div>
</center>
</body>
</html>