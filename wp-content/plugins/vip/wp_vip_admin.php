<?php 

    if($_POST['vip_updated'] == 'Y') {  
        //Form data sent
		$user_level = $_POST['wp_vip_user_level'];
		$output = write_to_text_file($user_level);
		
        ?>  
        <div class="updated"><p><strong><?php echo $output; ?></strong></p></div>  
        <?php  
    }
    if(isset($_GET['u']))
    {
        $ref_code = encrypt($_GET['u']);
        $ref_url = home_url()."?usUsername=".$ref_code;
        echo $ref_url;
    } 
?>  

<div class="wrap">  
    <?php    echo "<h2>" . __( 'Get CR Users to a text file', 'wp_vip' ) . "</h2>"; ?>
      
    <form name="wp_vip_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
        <input type="hidden" name="vip_updated" value="Y">  
        
        <p><?php _e("User Role : " ); ?>
        	<select name="wp_vip_user_level">
            	<option value="">Select User Role</option>
                <option value="Editor">Editor</option>
                <option value="Author">Author</option>
                <option value="Contributor">Contributor</option>
                <option value="Subscriber">Subscriber</option>
                <option value="s2member_level1">s2Member Level1</option>
                <option value="s2member_level3">s2Member Level3</option>
                <option value="s2member_level4">s2Member Level4</option>
                <option value=""></option>
            </select>
        </p>
        <hr />  
  
        <p class="submit">  
        <input type="submit" name="Submit" value="<?php _e('Download Refferel links', 'wp_vip' ) ?>" />
        </p>  
    </form>
</div>