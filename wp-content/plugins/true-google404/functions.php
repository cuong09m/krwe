<?php
function testPROXY($proxy)//of form ip:port
{
	if($proxy=="")return 0;
  $splited = explode(':',$proxy); // Separate IP and port
  if($con = @fsockopen($splited[0], $splited[1], $eroare, $eroare_str, 3)) 
  {
	//It works!!
    fclose($con); // Close the socket handle
    return 1;
  }
  return 0;
}
function getIP()
{
	if (!empty($_SERVER["HTTP_CLIENT_IP"]))
	{
	 //check for ip from share internet
	 $ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
	{
	 // Check for the Proxy User
	 $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else
	{
	 $ip = $_SERVER["REMOTE_ADDR"];
	}	
	// This will print user's real IP Address
	// does't matter if user using proxy or not.
	return $ip;
}

?>