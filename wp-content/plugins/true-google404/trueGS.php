<?php

/*

Script Name: true Google Search (trueGS)

Description: This script returns google search results as they would actually show up when the search is executed manually. The returned results include title, url and description as shown in the search results.

Version: 1.1

Author: technofreak777@gmail.com

Author URI: http://thetechnofreak.com

NOTES:

* This code is free to use in the plugin true Google 404 only. It shall not be removed/copied/edited from this plugin and/or used somewhere else.

* The author (technofreak777@gmail.com or http://thetechnofreak.com or the actual human author) will not be responsible for any consequences of using this script.

* By using this script, you agree to not increase the search load on google's servers.

*/

require("functions.php");

function trueGS($query="sample query")
{	
	$results=array();
	
	$gg_url = 'http://www.google.com/search?hl=en&q=' . urlencode($query) . "&start=00";
	
	$results['search_url']=$gg_url;
	
	$ch = curl_init($gg_url.$page.'0');
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_HEADER,false);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
	curl_setopt($ch,CURLOPT_ENCODING, "");
	curl_setopt($ch,CURLOPT_AUTOREFERER, true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 120);
	curl_setopt($ch,CURLOPT_TIMEOUT, 120);
	curl_setopt($ch,CURLOPT_MAXREDIRS,10);
	curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3");
	curl_setopt($ch,CURLOPT_REFERER,"http://www.google.com/");
	$scraped=curl_exec($ch);	
	$curl_error = curl_error($ch);	
    curl_close( $ch );
	
	if(!empty($curl_error))
	{
		$errors[]=$page;//where error occured
		$errors[]=$curl_error;
		$results["errors"][]=$errors;
		continue;		
	}
	if(preg_match("/sorry.google.com/", $scraped))
	{
		$results["blocked"]=true;
		return;
	}else
		$results["blocked"]=false;

	$result = array();
	$nres=preg_match_all(base64_decode('IzxoMyBjbGFzcz0iciI+PGEgaHJlZj0iKD8hL2ltYWdlcykoLio/KSIuKj8+KC4qPyk8L2E+Lio/PHNwYW4gY2xhc3M9InN0Ij4oLio/KTwvc3Bhbj4j'),$scraped,$result);
	if($nres==0)
		$results['no_more']=true;
	for ($i=0;$i<$nres;$i++) 
	{
		$routed_url=$result[1][$i];
		if(substr($routed_url,0,4)!='http')
		{
			$routed_url=substr(strstr($routed_url,'?'),1);
			$params=null;
			parse_str($routed_url,$params);
			$results['urls'][]=isset($params['q'])?$params['q']:'http://google.'.$local.$result[1][$i];
		}else
			$results['urls'][]=$routed_url;
		$results['title'][]=strip_tags(isset($result[2][$i])?$result[2][$i]:'');
		$results['desc'][]=strip_tags(isset($result[3][$i])?$result[3][$i]:'');
	}
	$results['num']=$nres;
	$results['size']=strlen($scraped);

	return $results;

}

?>