<?php

function isalphanumeric($data,$strict=false) { //strict makes '-' and '_' not count as being 'alphanumeric'
	for($a=0; $a<strlen($data); $a++) {
		$b=ord(substr($data,$a,1));

		if($strict==false) {
			if(!((($b>=48)&&($b<=57))||(($b>=65)&&($b<=90))||(($b>=97)&&($b<=122))||($b==95)||($b==45))) {
				return(false);
			}
		}
		else {
			if(!((($b>=48)&&($b<=57))||(($b>=65)&&($b<=90))||(($b>=97)&&($b<=122)))) {
				return(false);
			}
		}
	}

	return(true);
}

function isIPValid($ip,$blankvalid=false) {
	if(($ip=="")&&($blankvalid==false))
		return(false);
	elseif(($ip=="")&&($blankvalid==true))
		return(true);

	$octet=explode(".",$ip);

	if(count($octet)!=4)
		return(false);

	for($a=0;$a<4;$a++) {
		if(is_numeric($octet[$a])==false)
			return(false);

		if(($octet[$a]<0)||($octet[$a]>255))
			return(false);
	}

	return(true);
}

function macAddressValid($mac) {
	if(strlen($mac)!=17)
		return(false);

	if(!$tmp=explode(':',$mac))
		return(false);

	if(count($tmp)!=6)
		return(false);

	foreach($tmp as $macSection) {
		if(isalphanumeric($macSection)==false)
			return(false);
	}

	return(true);
}

?>