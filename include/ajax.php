<?php

	require ('database.php');
	require('functions.php');
	
	
	$result=dbQuery("SELECT serverID FROM `server` WHERE `isActive`='1'");
	$num_rows = mysql_num_rows($result);
	$serveIDs = array();
	
	while($row = mysql_fetch_array($result)){
		$serverIDs[] = $row['serverID'];
		 
	}
echo "function loadPage(){
	getLastSeen$serverIDs[0]();
	}";

echo "setInterval( \"getLastSeen$serverIDs[0]()\",5000 );\n";

	if($num_rows > 0){
		
		for($i = 0; $i < count($serverIDs); $i++){
		
			echo "function getLastSeen$serverIDs[$i](){";
			echo "var myurl = \"get/getLastSeen.php?junk=\"+Math.random()+\"&serverID=$serverIDs[$i]\";\n";
			echo "http = getHTTPObject();\n";
			echo "http.open(\"GET\", myurl , true);\n";
			echo "http.onreadystatechange = writeLastSeen$serverIDs[$i];\n";
			echo "http.send(null);\n";
			echo "var getLastSeen$serverIDs[$i] = document.getElementById('lastSeen$serverIDs[$i]');\n";
			//echo "getLastSeen$serverID.innerHTML = '<center><img src=\"include/images/ajax-loader.gif\"/></center>';\n";
			echo "}\n\n";
			
			echo "function writeLastSeen$serverIDs[$i](){\n";
			echo "if(http.readyState == 4) {\n";
			echo "var putItHere = document.getElementById('lastSeen$serverIDs[$i]');\n";
			echo "putItHere.innerHTML = http.responseText;\n";
			echo "new Effect.Highlight(putItHere.parentNode, { startcolor: '#ffff99',endcolor: '#ffffff' });\n";
			if($i+1 != count($serverIDs)){
			
				echo "getLastSeen" . $serverIDs[$i+1] . "();";
			}
			echo "return true;";
			echo "}\n";
			echo "}\n\n";
		}
	}

echo "function getHTTPObject() {
   if (typeof XMLHttpRequest != 'undefined') {
        return new XMLHttpRequest();
    }
    try {
        return new ActiveXObject(\"Msxml2.XMLHTTP\");
    } catch (e) {
        try {
            return new ActiveXObject(\"Microsoft.XMLHTTP\");
        } catch (e) {}
    }
    return false;
}
";
echo "
var http = getHTTPObject();
function getServerStatus(){ // Call
	var myurl = \"get/getServerStatus.php?junk=\"+Math.random();
	http = getHTTPObject();
	http.open(\"GET\", myurl , true);
	http.onreadystatechange = writeServerStatus;
	http.send(null);
	var serverStatus = document.getElementById('serverStatus');
	//serverStatus.innerHTML = '<center><img src=\"ajax-loader.gif\"/></center>';
}
	
function writeServerStatus(){ //Returm
	if(http.readyState == 4) {
		var putItHere = document.getElementById('serverStatus');
		var light = document.getElementById('lastSeen');
		//ajaxStatus.innerHTML = '<img src=\"check.gif\"/>';
		putItHere.innerHTML = http.responseText;
		new Effect.Highlight(this.parentNode, { startcolor: '#ffff99',endcolor: '#ffffff' });";
		if($num_rows > 0){
			//echo "getServerStatus();\n";
		}
		
		echo "return true;
	}	
}";
?>

