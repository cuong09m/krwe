<?php

if(!class_exists('Widget_Include_Post')):

class Widget_Include_Post extends WP_Widget {
	var $post_type_name = 'pt-widget';
	
	function Widget_Include_Post() {
		$widget_ops = array( 'classname' => 'Widget_Include_Post', 'description' => __('Include widget entries as widgets in your sidebars.', 'widget-entries'));
		parent::WP_Widget(false, $name = __('Widget Entry', 'widget-entries'), $widget_ops);
	}
	function form($instance) {
		// outputs the options form on admin
		if(! isset($instance['postwidgetid']) )
			$instance['postwidgetid'] = '';		
			
		global $wpdb;
		
		$widgets = $wpdb->get_results("SELECT ID, post_title from $wpdb->posts WHERE post_type='$this->post_type_name' AND post_status='publish' ORDER BY post_title");
			
		//$widgets = get_posts('post_type=' . $this->post_type_name . '&orderby=title&order=asc');
		$id_selected = $instance['postwidgetid'];
		$includetitle = $instance['includetitle'];
		
		include('view/form.php');
		//echo $instance['widget-post-id'];
		//print_r($instance);		
	}
	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = $old_instance;
		$instance['postwidgetid'] = esc_attr($new_instance['postwidgetid']);
		$instance['title'] = esc_attr($new_instance['title']);
		
		if(isset($new_instance['includetitle']))
			$instance['includetitle'] = 'checked="checked"';
		else
			$instance['includetitle'] = '';		
			
		return $instance;
	}
	function widget($args, $instance) {		
		$params = 'allowType=' . $this->post_type_name;
		
		if(! empty($instance['includetitle']) )
			$params .= '&titleBefore=' . $args['before_title'] .
					'&titleAfter=' . $args['after_title'] .
					'&displayTitle=true';
		
		
		echo $args['before_widget'];
		
		$content = '';
		
		//Retrieve the post
		if(is_numeric($instance['postwidgetid'])){
			$content = iinclude_page(intval($instance['postwidgetid']), $params, TRUE);
		}
		
		//Execute code
		if(version_compare(phpversion(), '5.0.0')>-1)
			$content = preg_replace_callback('/\[php\]((.|\n)*?)\[\/php\]/', array($this,'_exec_php5'), $content);
		else
			$content = preg_replace_callback('/\[php\]((.|\n)*?)\[\/php\]/', array($this,'_exec_php4'), $content);
			
		echo preg_replace('/\[php off\]((.|\n)*?)\[\/php\]/', '<exec>$1</exec>', $content);
		
		echo $args['after_widget'];
	}
	
	function _exec_php4($matches){
		error_reporting(0);
		//eval('ob_start();'.strip_tags($matches[1]).'$inline_execute_output = ob_get_contents();ob_end_clean();');
		$out = $this->_my_eval($matches[1]);
		return $out;
	}
	
	function _exec_php5($matches){
		try{
			$out = $this->_my_eval($matches[1]);
		} catch(Exception $e){
			$out = '* PHP sintax Error *';
		}
		return $out;
	}
	
	function _my_eval($script){
		$script =(htmlspecialchars($script,ENT_QUOTES));
		$script = str_replace("&amp;#8217;","'",$script);
		$script = str_replace("&amp;#8216;","'",$script);
		$script = str_replace("&amp;#8242;","'",$script);
		$script = str_replace("&amp;#8220;","\"",$script);
		$script = str_replace("&amp;#8221;","\"",$script);
		$script = str_replace("&amp;#8243;","\"",$script);
		$script = str_replace("&amp;#039;","'",$script);
		$script = str_replace("&#039;","'",$script);
		$script = str_replace("&amp;#038;","&",$script);
		$script = str_replace("&amp;lt;br /&amp;gt;"," ", $script);
		$script = htmlspecialchars_decode($script);
		$script = str_replace("<br />"," ",$script);
		$script = str_replace("<p>"," ",$script);
		$script = str_replace("</p>"," ",$script);
	
		#line break
		$script = str_replace("[br/]","<br/>",$script);
		#other tags
		$script = str_replace("\\[","&#91;",$script);
		$script = str_replace("\\]","&#93;",$script);
		$script = str_replace("[","<",$script);
		$script = str_replace("]",">",$script);
		$script = str_replace("&#91;",'[',$script);
		$script = str_replace("&#93;",']',$script);
		$script = str_replace("&gt;",'>',$script);
		$script = str_replace("&lt;",'<',$script);
		
		ob_start();
		eval ($script);
		$returned = ob_get_clean();
		
		return $returned;
	}
}

endif; //class exists