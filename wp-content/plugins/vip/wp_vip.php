<?php
 /* 
    Plugin Name: VIP Reff Links
    Plugin URI: http://www.ezymedia.com
    Description: Plugin for get refferal links for users
    Author: Ezymedia Team - Ajith
    Version: 1.0 
    Author URI: http://www.ezymedia.com 
    */
   define('VIP_PLUGIN_DIR',WP_PLUGIN_DIR .'/'.dirname(plugin_basename(__FILE__)));     
	function wp_vip_admin_functions(){
		add_options_page('Wp-VIP', 'Wp-VIP', 1, 'wp-vip-link', 'wp_vip_admin');
	}
	
	function wp_vip_admin(){
		include ( 'wp_vip_admin.php' );
	}
	
	add_action('admin_menu', 'wp_vip_admin_functions');
	
	 define('SALT', 'refferel_link');
 
	 /*function encrypt($text) 
		{ 
			return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
		}*/
	
	function write_to_text_file($user_level){
		$args = array( 'role' => $user_level, 'orderby' => 'login' );
		$all_users = get_users( $args );
		$ref_txt_file = VIP_PLUGIN_DIR."/refferal_links.csv";
		if(file_exists($ref_txt_file)){
			unlink($ref_txt_file);
		}
		$fh = fopen($ref_txt_file, 'a') or die("can't open file".$ref_txt_file);
		
		foreach($all_users as $user){
			$ref_code = encrypt($user->user_login);
			$ref_url = home_url()."?usUsername=".$ref_code;
			$ref_line =  $user->user_email.",".$ref_url."\r\n";
// 			echo $ref_line;
			fwrite($fh, $ref_line);
		}
		
		return 'Download <a href="'.plugins_url( 'refferal_links.csv' , __FILE__ ).'">Referrer links</a>';
        
	}
	
?>