<?php
//This file is used to parse the output from the nmap dump. "scan.txt"
require ('database.php');

$result = dbQuery("SELECT * FROM `general`");
$num_rows = mysql_num_rows($result);
$scanID = ++$num_rows;

$fileName = $nmapScanFileLocation;
//echo $fileName . "<br>";

//$fileName = "/var/www/htdocs/scan.txt";
$totalHosts = 0;
$hostsUp = 0;
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
	if(preg_match('/^Nmap\sscan/', $line) && $currentSubLoc > 2){ 
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
	if(preg_match('/^Nmap/', $line)){
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
//================ match "Nmap" and has a tailing ")"=====================//
foreach ($comp as $subArray){	
	echo "sub array = " . $sunArray[0] . "<br>";
	if(preg_match('/^Nmap\sscan/', $subArray[0])){
		$openPorts = "";
		$hostname = "";
		$ip = "";
		$hops = "";
		$OS = "";
		$mac = "";
		foreach ($subArray as $line){
			if(preg_match('/^Nmap/', $line)&& preg_match('/\)/', $line)){
			//hostname
				$new = explode(" ",$line);
				$hostname = $new[4];
			//ip address
				$new[5] = preg_replace('/\(/',"", $new[5]);
				$new[5] = preg_replace('/\)/',"", $new[5]);
				$ip = preg_replace('/\:/',"", $new[5]);
			}
			if(preg_match('/^Nmap/', $line) && !(preg_match('/\)/', $line))){
			//echo "MATCH! WOOT!";
			//hostname
				$hostname = "";
			//ip address
				$new = explode(" ",$line);
				$ip = preg_replace('/\:/',"", $new[4]);
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
		$query = "INSERT INTO stats (`scanID`, `ip`, `mac`, `os`, `openPorts`, `hostname`, `hops`) VALUES ('$scanID','$ip','$mac','$OS','$openPorts','$hostname','$hops')";
		dbQuery($query);;
	}
	//==============match "All"================//
	if(preg_match('/^All/', $subArray[0])){
		$openPorts = "";
		$hostname = "";
		$ip = "";
		$hops = "";
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
	$query = "INSERT INTO stats (`scanID`, `ip`, `mac`, `os`, `openPorts`, `hostname`, `hops`) VALUES ('$scanID','$ip','$mac','$OS','$openPorts','$hostname','$hops')";
	//echo "REALLY? ===" . $query ."<br>";
	dbQuery($query);
	}
}
//set OtherCount
$otherCount = ($hostsUp - $microsoftCount - $appleCount - $linuxCount);

$query = "INSERT INTO general (`microsoft`, `apple`, `linux`, `other`, `hosts`, `hostsScanned`, `scanID`, `scanTime`) VALUES ('$microsoftCount','$appleCount','$linuxCount','$otherCount','$hostsUp','$totalHosts','$scanID',NOW())";
//echo $query ."<br>";
dbQuery($query);

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

?>
