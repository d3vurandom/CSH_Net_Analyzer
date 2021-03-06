<?php
require ('database.php');

$result = dbQuery("SELECT * FROM `general`");
$num_rows = mysql_num_rows($result);
$scanID = ++$num_rows;

//vars
//$ipFrom = "129.21.49.1";
//$ipTo = "129.21.50.255";

$fileName = $nmapScanFileLocation;
//$fileName = "scan.txt";
$totalHosts;
$hostsUp;
$ipList;
$microsoftCount = 0;
$appleCount = 0;
$linuxCount = 0;
$otherCount = 0;
$comp = array(array());


$currentLoc = 0;
$currentSubLoc = 0;
$file=file($fileName);
foreach ($file as $line){
	if(preg_match('/^Network/', $line)){ // matches "Network"
		$comp[$currentLoc][$currentSubLoc] = $line;
		$currentLoc++;
		$currentSubLoc=0;
	}
	if(preg_match('/^Interesting/', $line) || preg_match('/^All/', $line) && $currentSubLoc > 2){ // matches "Interesting"
		$currentLoc++;
		$currentSubLoc=0;
	}
	if(!($currentSubLoc == '0')){
		if(preg_match('/^[0-9]/', $line)){ //line starts with a number
			$comp[$currentLoc][$currentSubLoc] = $line;
			$currentSubLoc++;
		}
		elseif(preg_match('/^No\sexact\sOS/', $line)){
			$comp[$currentLoc][$currentSubLoc] = $line;
			$currentSubLoc++;
		}
		elseif(preg_match('/^Running/', $line)){
			$comp[$currentLoc][$currentSubLoc] = $line;
			$currentSubLoc++;
		}
		elseif(preg_match('/^Aggressive\sOS\sguesses/', $line)){
			$comp[$currentLoc][$currentSubLoc] = $line;
			$currentSubLoc++;
		}
		elseif(preg_match('/^MAC/', $line)){
			$comp[$currentLoc][$currentSubLoc] = $line;
			$currentSubLoc++;
		}
	}
	if(preg_match('/^Interesting/', $line) || preg_match('/^All/', $line)){ // matches "Interesting" || "All"
		$comp[$currentLoc][$currentSubLoc] = $line;
		$currentSubLoc++;
	}
}
//set num of hosts
$search = "map done:";
foreach ($file as $line){
	if (strpos($line,$search)) {
		$results = explode(" ",$line);
		$totalHosts = $results[2];
		$hostsUp = ltrim($results[5],"(");
	}
}
//================ match "Interesting" and has a tailing ")"=====================//
foreach ($comp as $subArray){	
	if(preg_match('/^Interesting/', $subArray[0])){
		$openPorts = "";
		$hostname = "";
		$ip;
		$hops;
		$OS = "";
		$mac = "";
		foreach ($subArray as $line){
			if(preg_match('/^Interesting/', $line)&& preg_match('/\)/', $line)){
			//hostname
				$new = explode(" ",$line);
				$hostname = $new[3];
			//ip address
				$new[4] = preg_replace('/\(/',"", $new[4]);
				$new[4] = preg_replace('/\)/',"", $new[4]);
				$ip = preg_replace('/\:/',"", $new[4]);
			}
			if(preg_match('/^Interesting/', $line) && !(preg_match('/\)/', $line))){
			//echo "MATCH! WOOT!";
			//hostname
				$hostname = "None";
			//ip address
				$new = explode(" ",$line);
				$ip = preg_replace('/\:/',"", $new[3]);
			}
			//append open ports
			$new = explode(" ",$line);
			if(preg_match('/^[0-9]/', $new[0]) && $new[1] =="open"){
				$new[0] = preg_replace('/\//',"", $new[0]);
				$new[0] = preg_replace('/tcp/',"", $new[0]);
				$openPorts .= ($new[0] . " ");
			}
			//network hops
			if(preg_match('/^Network Distance/', $line)){
				$new = explode(" ",$line);
				$hops = $new[2];
			}
			//running OS
			if(preg_match('/^Running\s\(/', $line)){
				$new = explode(" ",$line);
				$OS = $new[4] . " " . $new[5] . " " . $new[6];
				if(preg_match('/^Microsoft\sWindows/', $OS)){
					$microsoftCount++;
				}
				elseif(preg_match('/^Linux/', $OS)){
					$linuxCount++;
				}
				elseif(preg_match('/^Apple/', $OS)){
					$linuxCount++;
				}
			}
			if(preg_match('/^Running:/', $line)){
				
				$new = explode(" ",$line);
				$OS = $new[1] . " " . $new[2];
				if(preg_match('/^Microsoft\sWindows/', $OS)){
					$microsoftCount++;
				}
				elseif(preg_match('/^Linux/', $OS)){
					$linuxCount++;
				}
				elseif(preg_match('/^Apple/', $OS)){
					$linuxCount++;
				}
			}
			if(preg_match('/^MAC/', $line)){
				$new = explode(" ",$line);
				$mac = $new[2];
			}
		}
		//enter stuff in database at this point
		/*
		echo "<br>";
		echo "Hostname: " . $hostname . "<br>";
		echo "IP Address: " . $ip . "<br>";
		echo "Mac Address: " . $mac . "<br>";
		echo "Open Ports: " . $openPorts;
		echo "<br>";
		echo "Operating System: " . $OS;
		echo "<br>";
		echo "Network Hops: " . $hops . "<br>";
		echo "<br>";
		*/
		$query = "INSERT INTO stats (scanID, ip, mac, os, openPorts, hostname, hops) VALUES ('$scanID','$ip','$mac','$OS','$openPorts','$hostname','$hops')";
		//hostname=$hostname,ip=$ip,mac=$mac,openPorts=$openPorts,os=$OS,hops=$hops,scanID=$scanID,dateTime=NOW()";
		//echo $query ."<br>";
		dbQuery($query);;
	}
	//==============match "All"================//
	if(preg_match('/^All/', $subArray[0])){
	//echo "LOL". "<br>";
		$openPorts = "";
		$hostname = "";
		$ip;
		$hops;
		$OS = "";
		$mac = "";
		foreach ($subArray as $line){
			if(preg_match('/^All/', $line) /*&& preg_match('/(^\()(^\))/', $line)*/){
			//hostname
				if(preg_match('/\(*\)|\)*\(/', $line)){
					$new = explode(" ",$line);
					$hostname = $new[5];
					$new[6] = preg_replace('/\(/',"", $new[6]);
					$new[6] = preg_replace('/\)/',"", $new[6]);
					$ip = preg_replace('/\:/',"", $new[6]);
				}else{
					$new = explode(" ",$line);
					$new[5] = preg_replace('/\(/',"", $new[5]);
					$new[5] = preg_replace('/\)/',"", $new[5]);
					$ip = preg_replace('/\:/',"", $new[6]);
					$ip = $new[5];
				}
			}
			if(preg_match('/^Network Distance/', $line)){
				$new = explode(" ",$line);
				$hops = $new[2];
			}
		}
	//enter stuff in database at this point
	//echo "<br>";
	//echo "----------ALL-------" . "<br>";
	/*
	echo "Hostname: " . $hostname . "<br>";
	echo "IP Address: " . $ip . "<br>";
	echo "Mac Address: " . $mac . "<br>";
	echo "Open Ports: " . $openPorts;
	echo "<br>";
	echo "Operating System: " . $OS;
	echo "<br>";
	echo "Network Hops: " . $hops . "<br>";
	echo "<br>";
	*/
	$query = "INSERT INTO stats (scanID, ip, mac, os, openPorts, hostname, hops) VALUES ('$scanID','$ip','$mac','$OS','$openPorts','$hostname','$hops')";
	//echo $query ."<br>";
	dbQuery($query);
	}
}


//set OtherCount
$otherCount = ($hostsUp - $microsoftCount - $appleCount - $linuxCount);
//echo stuff
echo "Total Scanned= " . $totalHosts;
echo "<br>";
echo "Alive Hosts = " . $hostsUp;
echo "<br>";
echo "Definitely Running Microsoft = " . $microsoftCount; 
echo "<br>";
echo "Definitely Running OSX = " . $appleCount;
echo "<br>";
echo "Definitely Running Linux = " . $linuxCount;
echo "<br>";
echo "Undetermined = " . $otherCount;
echo "<br>";
$query = "INSERT INTO general (microsoft, apple, linux, other, hosts, hostsScanned, scanID, scanTime) VALUES ('$microsoftCount','$appleCount','$linuxCount','$otherCount','$hostsUp','$totalHosts','$scanID',NOW())";
//echo $query ."<br>";
dbQuery($query);


/*
// generate ip addrs
$arry1 = explode(".",$ipFrom);
$arry2 = explode(".",$ipTo);
$a1 = $arry1[0]; $b1 = $arry1[1];	$c1 = $arry1[2]; $d1 = $arry1[3];
$a2 = $arry2[0]; $b2 = $arry2[1];	$c2 = $arry2[2]; $d2 = $arry2[3];
while( $d2 >= $d1 || $c2 > $c1 || $b2 > $b1 || $a2 > $a1){
	if($d1 > 255){
		$d1 = 1;
		$c1 ++;
	}
	if($c1 > 255){
		$c1 = 1;
		$b1 ++;
	}	
	if($b1 > 255){
		$b1 = 1;
		$a1 ++;
	}
	$ipList[] =  $a1 . "." . $b1 . "." . $c1 . "." . $d1 ;
	$d1 ++;
}
*/
echo "Success?";

?>
