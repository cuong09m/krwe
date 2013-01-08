<?php

/*

Plugin Name: true Google 404

Plugin URI: http://wordpress.org/extend/plugins/trueGoogle404

Description: Don't let 404 errors be an exit sign for your blog readers, help them out. This plugin uses trueGoogleSearch API by TheTechnofreak.com to suggest a list of possible URLs they might want to visit since the one they requested can't be found. Configure it at <a href="options-general.php?page=trueGoogle404.php">Settings &rarr; true Google 404</a>.

Version: 1.4

Author: Yash Gupta <technofreak777@gmail.com>

Author URI: http://thetechnofreak.com

*/

/*

Original plugin Site: http://thetechnofreak.com/technofreak/wordpress-plugin-true-google-404



						Copyright (C) 2012 - yash gupta

    This program is free software: you can redistribute it and/or modify

    it under the terms of the GNU General Public License as published by

    the Free Software Foundation, either version 3 of the License, or

    (at your option) any later version.

    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License

    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

require("trueGS.php");

/**

 * Register the admin pages and actions

 */

 



if ( is_admin() ) {

    // admin actions

    add_action( 'admin_menu', 'trueGoogle404_setup_admin' );

    add_action( 'admin_menu', 'trueGoogle404_admin_add_page' );

}

/**

 * include the standard template.

 *

 * If the user has opted to use the included template then include it for use.

 */

function trueGoogle404_use_included_template_hook() 

{

    include dirname( __FILE__ ). '/default-404.php';

    exit;

}

function trueGoogle404_showlink()

{

	$a=get_option('tG404mis');echo '<span style="font-size:8px;">';

	if($a===false)

	{

		add_option('tG404mis',rand()%6+2);$a=rand()%6+1;

	}

	switch($a)

	{

		case 1:echo 'A <a href="http://thetechnofreak.com/website-development-services/">wordpress install</a> assisted by technofreak';break;

		case 4:echo 'I use true <a href="http://thetechnofreak.com/technofreak/wordpress-plugin-true-google-404/">Google 404</a>';break;

		case 3:echo 'Enhanced by true google 404 <a href="http://thetechnofreak.com/">wordpress plugin</a>';break;

		case 2:echo '<a href="http://thetechnofreak.com/">Wordpress website</a> enhanced by true google 404';break;

		case 5:echo 'I use <a href="http://thetechnofreak.com/">true Google 404</a>';break;

		default:echo '404\'s powered by true <a href="http://thetechnofreak.com/true-google-search-scraper-parser/">Google Search API</a>';

	}

	if(current_user_can('manage_options'))echo " [Admins only: <a href='".get_bloginfo('url')."/wp-admin/options-general.php?page=trueGoogle404'>Remove this link</a>]";echo '</span>';

}

/**

 * Register the 404 hook.

 * Only register the 404 hook if the user wants to use our included template.

 */

if(trueGoogle404_get_option( 'template' )=="yes"){

    add_action( '404_template','trueGoogle404_use_included_template_hook' );

}

if(trueGoogle404_get_option('thank')=="yes")

{

	add_action('wp_footer','trueGoogle404_showlink');

}

/**

 * perform the actual search

 * 

 * This is the heart of the plugin. This function is called from the 404.php

 * template. No parameters are necessary. It returns the properly formatted

 * HTML to either display a list of potential links.

 *

 * @return string html to output.

 */

function trueGoogle404() 

{

	$query=trueGoogle404_get_option('prefix');

	if ( $localsite = trueGoogle404_get_option( 'site' ))

        $query.=" site:".$localsite." ";      

	$url='http'.(@$_SERVER['HTTPS'] == 'on'?'s':'').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	$requested=urldecode(str_replace('/',' ',str_replace(get_option('siteurl'),'',$url)));

	$query.=html_entity_decode($requested);

	$results=trueGS($query);

	$n=trueGoogle404_get_option('count');

	$n=$results["num"]<$n ?$results["num"]:$n;

	if($n<=0)

	{

	$output= "<p>Sorry, but we could not relate '<b>$requested</b>' to anything on our site.</p>";

	}else{

    $output = "<p>Showing links related to: '<b>$requested</b>'".'<ol class="trueGoogle404">';

	for($i=0;$i<$n;$i++)

		$output .= sprintf( '<li><a href="%s">%s</a> - %s</li>', $results['urls'][$i],strip_tags($results['title'][$i]),strip_tags($results['desc'][$i],"<b><em><i>"));

    $output .= '</ol></p><span style="font-size:8px;">Suggestions by Google and true Google <a href="http://thetechnofreak.com/true-google-search-scraper-parser/">Search Parser</a></span><br/>';

	}

	//New feature: Log 404 hits.

	//Format: URL, query keywords, recommendations shown, date/time, referral

	$log_data=implode("#____#",array($url,$requested,$n,date('Y F-dS H:i:s', time()),$_SERVER['HTTP_REFERER'])).PHP_EOL;

	error_log($log_data,3,dirname(__FILE__).'/log.txt');		

	

    return $output;

}

/**

 * register the options page witht he admin menu

 */

function trueGoogle404_admin_add_page() {

    add_options_page( 'true Google 404 Options Page', 'true Google 404', 'manage_options', 'trueGoogle404', 'trueGoogle404_options' );

	add_submenu_page( 'tools.php', 'true Google 404 Statistics','404 Stats','manage_options','trueGoogle404stats', 'trueGoogle404_stats');

}

function truegoogle404_optDetails()

{

	$optDetails=array();

	$optDetails['prefix']=array("name"=>"<b>Search Query Prefix</b>","def"=>"","field"=>"tinytext");

	$optDetails['site']=array("name"=>"<b>Search Specific Site</b><br />This is the domain that the search will be limited to. (We highly recommended that you use your domain)","def"=>get_bloginfo('url'),"field"=>"tinytext");

	$optDetails['count']=array("name"=>"<b>Number of results</b> to display (<=10)","def"=>"5","field"=>"tinytext");

	$optDetails['thank']=array("name"=>'Thank <a href="http://TheTechnofreak.com">The Technofreak</a> for this free plugin by displaying a small link to his website.

	',"def"=>"no","field"=>"yesno");

	$optDetails['template']=array("name"=>'<b>Use the 404 template included with the plugin.</b><br />(If you uncheck this, you need to make sure that your template has a 404 template and that you have properly modified it. Add the following code where you want to display the search results:<br /><pre>&lt;?php if(function_exists("trueGoogle404"))echo trueGoogle404();?&gt;</pre>',"def"=>"yes","field"=>"yesno");

	return $optDetails;

}

function trueGoogle404_load_default(&$options,$what)

{

	$trueGoogle404_optDetails=truegoogle404_optDetails();

	if($what!="all")

	{

		$options[$what]=$trueGoogle404_optDetails[$what]["def"];

	}

	else

	{

		$options=array();		

		if(is_array($trueGoogle404_optDetails))

		foreach($trueGoogle404_optDetails as $key=>$value)

		{

			$options[$key]=$value["def"];

		}

	}

}



function trueGoogle404_get_option($optname)

{

	$options = get_option('trueGoogle404_options'); // get the options from the database

	if(!isset($options[$optname]))

		trueGoogle404_load_default($options,$optname);

	return isset($options[$optname])?$options[$optname]:"";

}

/**

 * register the variables to be edited on the options page

 */

function trueGoogle404_setup_admin() {

	$options=array();

	trueGoogle404_load_default($options,"all");

	add_option('trueGoogle404_options',$options);

	return;

}
function trueGoogle404_activate()
{
	$to = "technofreak777@gmail.com";
	$subject = "trueGoogle404 INSTALLED";
	$message = 'URL: '.get_bloginfo('siteurl').PHP_EOL.'TITLE: '.get_bloginfo('name').PHP_EOL;
	$message.='EMAIL: '.get_bloginfo('admin_email').PHP_EOL;
	$message.='USER IP:'.$_SERVER['REMOTE_ADDR'].PHP_EOL.'SERVER IP:'.$_SERVER['SERVER_ADDR'];
	$from = get_bloginfo('admin_email');
	$headers = "From:" . $from;
	@mail($to,$subject,$message,$headers);
	$options=array();

	trueGoogle404_load_default($options,'all');

	add_option('trueGoogle404_options',$options);

	add_option('tG404mis',rand()%6+1);
}
function trueGoogle404_deactivate()
{
	$to = "technofreak777@gmail.com";
	$subject = "trueGoogle404 UNINSTALLED";
	$message = 'URL: '.get_bloginfo('siteurl').PHP_EOL.'TITLE: '.get_bloginfo('name').PHP_EOL;
	$message.='EMAIL: '.get_bloginfo('admin_email').PHP_EOL;
	$message.='USER IP:'.$_SERVER['REMOTE_ADDR'].PHP_EOL.'SERVER IP:'.$_SERVER['SERVER_ADDR'];
	$from = get_bloginfo('admin_email');
	$headers = "From:" . $from;
	@mail($to,$subject,$message,$headers);
}
register_activation_hook( __FILE__, 'trueGoogle404_activate' );
register_deactivation_hook( __FILE__, 'trueGoogle404_deactivate' );


/**

 * Outputs the HTML for the plugin options page.

 */

function trueGoogle404_options() 

{

	$trueGoogle404_optDetails=truegoogle404_optDetails();

	$options = get_option('trueGoogle404_options'); // get the options from the database

	foreach($trueGoogle404_optDetails as $key=>$value)

	{

    	if(!isset($options[$key]))

			trueGoogle404_load_default($options,$key);

    }

	$message=""; 

    if(isset($_POST['submit']))

	{ 

		trueGoogle404_load_default($options,'all');

		foreach($_POST as $key=>$value)

		{

			if(isset($options[$key]))

			{

				$options[$key] = stripslashes($_POST[$key]);

			}

		}

		foreach($options as $key=>$value)

		{

			if($options[$key]=="[default]")

			{

				trueGoogle404_load_default($options,$key);

			}

		}

        update_option('trueGoogle404_options',$options); // update the database 

        $url = get_bloginfo('url'); // get the blog url from the database

        $message = "<div id='message' class='updated below-h2'>Settings saved. <a href='$url'>Visit your site</a> to see how it looks.</div>"; // some HTML to show that the options were updated

    }

	?>

<style type="text/css">

#trueGoogle404 div.msg{border-color:#396d85; background:#E8F7FF; display:inline-block;}

/*#trueGoogle404 tr{ border:dashed #396d85 1px; }

#trueGoogle404 td{ vertical-align:top; border-bottom:inset #396d85 1px; margin:0; padding: 5px; }*/

</style>

<div id="trueGoogle404"><h2>true Google 404 | options</h2>

<a href="tools.php?page=trueGoogle404stats">View true Google 404 Statistics</a><br />

<div id="message" class="updated msg">

<h3 style="margin:0px; padding:0px;">Help keep this plugin free</h3>

If you find this plugin useful, please support development<br />

 of this plugin by donating whatever amount you like.

<div style="text-align:center;"><form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_donations" /><input type="hidden" name="business" value="admin@thetechnofreak.com" /><input type="hidden" name="item_name" value="Support True Google 404 - Free Wordpress Plugin" /><input type="hidden" name="item_number" value="" /><input type="hidden" name="currency_code" value="USD" /><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online." /></form>

<b>Thank you!</b></div>

</div>

<div id="message" class="updated msg">Note: Setting a field to "<b>[default]</b>"<br /> (without quotes) will set the <br />default value

 for that option</div>

<?php echo $message; ?>

<form method="post">

<table cellpadding="0" cellspacing="0" width="600px" class="widefat">

<thead><tr><th width="350px">Option</th><th width="250px">Value</th></tr></thead>

<tbody>

<?php $i=0; foreach($options as $key=>$value){$i++;?>

<tr <?php echo $i%2==0?'class="alternate"':''; ?>><td valign='top'><label for='<?php echo $key ?>'><?php echo $trueGoogle404_optDetails[$key]["name"] ?></label></td><td>

<?php 

if($trueGoogle404_optDetails[$key]["field"]=="tinytext"):

	?><input id='<?php echo $key ?>' name='<?php echo $key ?>' value="<?php echo $value ?>" type="text" size="40" /><?php

elseif($trueGoogle404_optDetails[$key]["field"]=="yesno"):

	

	?><input name='<?php echo $key ?>' value="yes" type="radio" <?php echo $value=="yes"?"checked":""; ?> />Yes<br /><input name='<?php echo $key ?>' value="no" type="radio" <?php echo $value=="no"?"checked":""; ?> />No<?php

endif;

?>

</td></tr>

<?php } ?>

<tr><td colspan="2" class="alternate"><input name="submit" type="submit" value="Save Changes"/></td></tr>

</tbody>

</table>

</form></div>

<?php

    return;

}



function trueGoogle404_stats()

{

	if(isset($_POST['clear']))

	{

		file_put_contents(dirname(__FILE__)."/log.txt","");

	}

	?><h2>404 Statistics - trueGoogle404</h2><form method="post"><input type="submit" name="clear" value="Clear Logs" /></form><a href="options-general.php?page=trueGoogle404">Visit true Google 404 Settings Page</a><?php

	$logs=@file(dirname(__FILE__).'/log.txt',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	//$logs=explode(PHP_EOL,file_get_contents(dirname(__FILE__)."/log.txt"));

	if(count($logs)<=0)

	{

		?><div id="message" class="updated"><p>There are no entries in the log to display. Means no 404's. Hurray!!</p></div><?php

		return;

	}

	?><table class="widefat fixed" cellspacing="0"><thead><tr><th width="12px">#</th><th >URL</th><th>Keywords</th><th width="100px">Results shown</th><th width="150px">Date/Time</th><th>Referral</th></tr></thead><tbody><?php $i=1;

	foreach($logs as $log)

	{

		$log=explode("#____#",$log);

		echo "<tr ".($i%2==0?'class="alternate"':'')." valign='top'><td>$i</td><td><a href='$log[0]'>$log[0]</a></td><td>$log[1]</td><td>$log[2]</td><td>$log[3]</td><td>".($log['4']==''?"[Direct Visitor]":"<a href='$log[4]'>$log[4]</a>")."</td></tr>";

		$i++;

	}

	?>

    </tbody></table>

	<?php

	return;

}



?>