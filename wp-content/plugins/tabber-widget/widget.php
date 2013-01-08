<?php


if ( ! class_exists( 'ITTabberWidget' ) ) {
	class ITTabberWidget extends WP_Widget {
		var $_var = 'tabber_widget';
		var $_class = 'tabber_widget';
		var $_id_base = 'tabber-widget-';
		
		var $_widget_name = 'Tabber Widget';
		var $_widget_description = 'Adds tabs to a widget location';
		var $_widget_control_width = 300;
		var $_widget_control_height = 300;
		
		
		function ITTabberWidget() {
			$widget_ops = array( 'classname' => $this->_class, 'description' => $this->_widget_description );
			$control_ops = array( 'width' => $this->_widget_control_width, 'height' => $this->_widget_control_height );
			
			$this->WP_Widget( $this->_var, $this->_widget_name, $widget_ops, $control_ops );
			
			$this->_set_vars();
			$this->_templates_directory = dirname( __FILE__ ) . '/templates';
			
			add_action( 'wp_print_scripts', array( &$this, 'add_scripts' ) );
			add_action( 'wp_print_styles', array( &$this,'add_styles' ) );
		}
		
		function add_scripts() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( "{$this->_var}-idtabs", "{$this->_plugin_url}/js/jquery.idTabs.min.js" );
		}
		
		function add_styles() {
//			wp_enqueue_style( 'it-classes-style', "{$this->_plugin_url}/lib/classes/css/classes.css" );
		}
		
		function _set_vars() {
			$this->_plugin_path = dirname( __FILE__ );
			$this->_plugin_relative_path = ltrim( str_replace( '\\', '/', str_replace( rtrim( ABSPATH, '\\\/' ), '', $this->_plugin_path ) ), '\\\/' );
			$this->_plugin_url = get_option( 'siteurl' ) . '/' . $this->_plugin_relative_path;
			
			if ( is_ssl() )
				$this->_plugin_url = preg_replace( '/^http:/', 'https:', $this->_plugin_url );
		}
		
		function widget( $args, $instance ) {
			$options = apply_filters( "it_storage_load_{$this->_var}", array() );
			
			
			if ( ! isset( $instance['tabber_id'] ) || empty( $options['tabbers'][$instance['tabber_id']] ) )
				return;
			
			$tabber = $options['tabbers'][$instance['tabber_id']];
			
			
			extract( $args );
			
			$title = apply_filters( 'widget_title', $instance['title'] );
			
			echo $before_widget;
			
			if ( $title )
				echo $before_title . $title . $after_title;
			
			
			$class = "tabber-widget-{$instance['tabber_id']} {$tabber['template_class']}";
			$id = $this->id;
			
			$first = true;
			
?>
	<div id="<?php echo $id; ?>-content" class="<?php echo $class; ?>">
		<ul class="tabber-widget-tabs">
			<?php for ( $counter = 1; $counter <= $tabber['num']; $counter++ ) : ?>
				<?php
					if ( true === $first ) {
						$class = ' class="selected"';
						$first = false;
					}
					else
						$class = '';
					
					$tab_href = "tab-$id-$counter";
				?>
				<li><a<?php echo $class; ?> href="#<?php echo $tab_href; ?>"><?php echo $tabber["title_$counter"]; ?></a></li>
			<?php endfor; ?>
		</ul>
		<?php for ( $counter = 1; $counter <= $tabber['num']; $counter++ ) : ?>
			<?php $tab_href = "tab-$id-$counter"; ?>
			<div id="<?php echo $tab_href; ?>" class="tabber-widget-content">
				<?php dynamic_sidebar( "Tabber - {$tabber['name']} - {$tabber["title_$counter"]}" ); ?>
			</div>
		<?php endfor; ?>
	</div>
	
	<script type="text/javascript">
		jQuery("#<?php echo $id; ?>-content ul").idTabs();
	</script>
<?php
			
			echo $after_widget;
		}
		
		function update( $new_instance, $old_instance ) {
			/* Add update processing code if necessary */
			
			return $new_instance;
		}
		
		function form( $instance ) {
			$options = apply_filters( "it_storage_load_{$this->_var}", array() );
			
			ITUtility::require_file_once( dirname( __FILE__ ) . '/lib/classes/it-array-sort.php', 'ITArraySort' );
			$sorter = new ITArraySort( $options['tabbers'], 'name' );
			$options['tabbers'] = $sorter->get_sorted_array();
			
			$tabbers = array( '' => '' );
			foreach ( (array) $options['tabbers'] as $id => $tabber )
				$tabbers[$id] = $tabber['name'];
			
			if ( ! isset( $instance['tabber_id'] ) || isset( $tabbers[$instance['tabber_id']] ) )
				unset( $tabbers[''] );
			
			reset( $tabbers );
			
			$defaults = array(
				'title'			=> '',
				'tabber_id'		=> key( $tabbers ),
			);
			$instance = wp_parse_args( (array) $instance, $defaults );
			
			
			$form =& new ITForm( $instance, array( 'widget_instance' => &$this ) );
			
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">
		Title (optional):<br />
		<?php $form->add_text_box( 'title' ); ?>
	</label></p>
	<p><label for="<?php echo $this->get_field_id( 'tabber_id' ); ?>">
		Tabber:<br />
		<?php $form->add_drop_down( 'tabber_id', $tabbers ); ?>
	</label></p>
<?php
			
		}
	}
}


?>
