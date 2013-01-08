<?php

/*
Written by Chris Jean for iThemes.com
Licensed under GPLv2

Version 1.0.5

*/

if ( ! class_exists( 'ITTabber' ) ) {
	class ITTabber extends ITCoreClass {
		var $_var = 'tabber_widget';
		var $_page_title = 'Manage Tabber Widgets';
		var $_page_var = 'tabber-widgets';
		var $_menu_title = 'Tabber Widgets';
		var $_default_menu_function = 'add_management_page';
		var $_menu_priority = '15';
		
		var $_global_storage = true;
		
		
		function ITTabber() {
			$this->ITCoreClass();
			
			$this->_file = __FILE__;
			
			add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_public_scripts' ) );
		}
		
		function init() {
			ITCoreClass::init();
			
			if ( ! isset( $this->_options['tabbers'] ) )
				$this->_options['tabbers'] = array();
			
			foreach ( (array) $this->_options['tabbers'] as $id => $tabber ) {
				for ( $counter = 1; $counter <= $tabber['num']; $counter++ ) {
					register_sidebar( array( 'name' => "Tabber - {$tabber['name']} - {$tabber["title_$counter"]}", 'before_widget' => '<div class="tabber-widget %2$s" id="%1$s">','after_widget' => '</div>', 'before_title' => '<h5 class="tabber-widget-title">', 'after_title' => '</h5>' ) );
				}
			}
			
			$this->_templates_directory = dirname( __FILE__ ) . '/templates';
			
			if ( is_ssl() )
				$this->_plugin_url = preg_replace( '/^http:/', 'https:', $this->_plugin_url );
		}
		
		function contextual_help( $text, $screen ) {
/*			$text = "<h5>Get help with \"Layout Editor\"</h5>\n";
			$text .= "The Layout Editor allows you to create an unlimited variety of layout options for your site.";*/
			
			return $text;
		}
		
		function enqueue_public_scripts() {
			$styles = array();
			
			foreach ( (array) $this->_options['tabbers'] as $tabber )
				$styles[$tabber['template_class']] = $tabber['template_file'];
			
			foreach ( (array) $styles as $class => $file )
				wp_enqueue_style( "{$this->_var}-$class", "{$this->_plugin_url}/templates/$file" );
		}
		
		function add_admin_scripts() {
			ITCoreClass::add_admin_scripts();
			
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( "{$this->_var}-script", "{$this->_plugin_url}/js/editor.js" );
			wp_enqueue_script( "{$this->_var}-idtabs", "{$this->_plugin_url}/js/jquery.idTabs.min.js" );
		}
		
		function add_admin_styles() {
			ITCoreClass::add_admin_styles();
			
/*			wp_enqueue_style( "{$this->_var}-theme-options", "{$this->_plugin_url}/css/layout-editor.css" );*/
		}
		
		
		// Pages //////////////////////////////////////
		
		function index() {
			ITCoreClass::index();
			
			if ( ! empty( $_REQUEST['save'] ) )
				$this->_save_tabber();
			else if ( isset( $_REQUEST['delete'] ) )
				$this->_delete_tabber();
			else
				$this->_list_tabbers();
		}
		
		function _save_tabber() {
			$tabber = ITForm::get_post_data();
			
			
			foreach ( (array) $this->_options['tabbers'] as $cur_id => $cur_tabber )
				if ( ( $tabber['name'] === $cur_tabber['name'] ) && ( $_POST['id'] != $cur_id ) )
					$this->_errors[] = 'A Tabber with that Name already exists. Please choose a unique Name.';
			
			if ( empty( $tabber['name'] ) )
				$this->_errors[] = "You must supply a Name";
			if ( empty( $tabber['num'] ) )
				$this->_errors[] = "You must supply the Number of Widgets";
			if ( ! is_file( "{$this->_templates_directory}/{$tabber['template_file']}" ) )
				$this->_errors[] = "The selected Template was unable to be read. Please choose another Template.";
			
			if ( is_numeric( $tabber['num'] ) ) {
				for ( $counter = 1; $counter <= 10; $counter++ )
					if ( ( $counter <= $tabber['num'] ) && ( empty( $tabber["title_$counter"] ) ) )
						$this->_errors[] = "You must supply a Tab $counter Title";
			}
			
			if ( ! empty( $this->_errors ) ) {
				$this->_list_tabbers();
				return;
			}
			
			
			$templates = $this->_get_templates();
			
			$tabber['template_name'] = $templates[$tabber['template_file']]['name'];
			$tabber['template_class'] = $templates[$tabber['template_file']]['class'];
			
			
			if ( ! isset( $_POST['id'] ) ) {
				$this->_options['tabbers'][] = $tabber;
				ITUtility::show_status_message( 'Tabber added' );
			}
			else {
				$this->_options['tabbers'][$_POST['id']] = $tabber;
				ITUtility::show_status_message( 'Tabber updated' );
			}
			
			$this->_save();
			
			unset( $_POST['id'] );
			unset( $_REQUEST['id'] );
			
			$this->_list_tabbers();
		}
		
		function _delete_tabber() {
			$tabber = $this->_options['tabbers'][$_REQUEST['delete']];
			
			unset( $this->_options['tabbers'][$_REQUEST['delete']] );
			$this->_save();
			
			ITUtility::show_status_message( "Deleted {$tabber['name']}" );
			
			$this->_list_tabbers();
		}
		
		function _list_tabbers() {
			ITUtility::require_file_once( 'it-array-sort.php' );
			
?>
	<?php if ( empty( $this->_errors ) && ! isset( $_REQUEST['id'] ) ) : ?>
		<div class="wrap">
			<h2>Tabber Widgets</h2>
			
			<?php if ( count( $this->_options['tabbers'] ) > 0 ) : ?>
				<table class="widefat">
					<thead>
						<tr class="thead">
							<th>Name</th>
							<th>Number of Widgets</th>
							<th>Template</th>
							<th>Tab Titles</th>
						</tr>
					</thead>
					<tfoot>
						<tr class="thead">
							<th>Name</th>
							<th>Number of Widgets</th>
							<th>Template</th>
							<th>Tab Titles</th>
						</tr>
					</tfoot>
					<tbody id="tabbers">
						<?php
							$class = '';
							
							$sort = new ITArraySort( $this->_options['tabbers'], 'name' );
							$tabbers = $sort->get_sorted_array();
						?>
						<?php foreach ( (array) $tabbers as $id => $tabber ) : ?>
							<?php
								$tab_titles = '';
								
								for ( $counter = 1; $counter <= 10; $counter++ ) {
									if ( $counter <= $tabber['num'] ) {
										if ( ! empty( $tab_titles ) )
											$tab_titles .= ', ';
										
										$tab_titles .= $tabber["title_$counter"];
									}
								}
							?>
							
							<tr id="tabber-<?php echo $id; ?>"<?php echo $class; ?>>
								<td>
									<strong><a class="tabber_name" href="<?php echo $this->_self_link; ?>&id=<?php echo $id; ?>" title="Modify Tabber Settings"><?php echo $tabber['name']; ?></a></strong>
									<div class="row-actions">
										<span class="edit"><a href="<?php echo $this->_self_link; ?>&id=<?php echo $id; ?>">Edit</a> | </span>
										<span class="delete"><a class="delete_tabber_link" href="<?php echo $this->_self_link; ?>&delete=<?php echo $id; ?>">Delete</a></span>
									</div>
								</td>
								<td><?php echo $tabber['num']; ?></td>
								<td><?php echo $tabber['template_name']; ?></td>
								<td><?php echo $tab_titles; ?></td>
							</tr>
							<?php $class = ( '' === $class ) ? ' class="alternate"' : ''; ?>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	
	<div class="wrap">
		<h2><?php echo ( ! isset( $_REQUEST['id'] ) ) ? 'Add New Tabber' : 'Modify Tabber'; ?></h2>
		
		<?php
			$number_widgets = array();
			
			for ( $num = 1; $num <= 10; $num++ )
				$number_widgets[$num] = $num;
			
			
			if ( isset( $this->_errors ) )
				foreach ( (array) $this->_errors as $error )
					ITUtility::show_error_message( $error );
			
			
			$tabber = array();
			
			if ( ! empty( $this->_errors ) ) {
				$tabber = ITForm::get_post_data();
				
				if ( isset( $_POST['id'] ) )
					$tabber['id'] = $_POST['id'];
			}
			else if ( isset( $_REQUEST['id'] ) ) {
				$tabber = $this->_options['tabbers'][$_REQUEST['id']];
				$tabber['id'] = $_REQUEST['id'];
			}
			else
				$tabber['num'] = 3;
			
			
			$templates = $this->_get_templates();
			$template_options = array();
			
			foreach ( (array) $templates as $template )
				$template_options[$template['file']] = $template['name'];
			
			asort( $template_options );
			
			
			$form =& new ITForm( $tabber, array( 'prefix' => $this->_var ) );
		?>
		
		<?php $form->start_form(); ?>
			<table class="form-table">
				<tr><th scope="row">Name</th>
					<td><?php $form->add_text_box( 'name', array( 'size' => 20 ) ); ?></td>
				</tr>
				<tr><th scope="row">Number of Widgets</th>
					<td><?php $form->add_drop_down( 'num', $number_widgets ); ?></td>
				</tr>
				<tr><th scope="row">Template</th>
					<td><?php $form->add_drop_down( 'template_file', $template_options ); ?></td>
				</tr>
				<?php for ( $count = 1; $count <= 10; $count++ ) : ?>
					<tr id="title_input_row_<?php echo $count; ?>"><th scope="row">Tab <?php echo $count; ?> Title</th>
						<td><?php $form->add_text_box( "title_$count", array( 'size' => 20 ) ); ?></td>
					</tr>
				<?php endfor; ?>
			</table>
			
			<p class="submit">
				<?php $form->add_submit( 'save', ( ! isset( $tabber['id'] ) ) ? 'Add Tabber' : 'Update Tabber' ); ?>
			</p>
			
			<?php if ( isset( $tabber['id'] ) ) : ?>
				<?php $form->add_hidden_no_save( 'id', $tabber['id'] ); ?>
			<?php endif; ?>
		<?php $form->end_form(); ?>
	</div>
<?php
			
		}
		
		
		// Utility Functions //////////////////////////
		
		function _get_templates() {
			if ( isset( $this->_templates ) )
				return $this->_templates;
			
			$this->_templates = array();
			
			if ( false === ( $dir = opendir( $this->_templates_directory ) ) )
				return $this->_templates;
			
			while ( false !== ( $file = readdir( $dir ) ) ) {
				if ( is_dir( "{$this->_templates_directory}/$file" ) )
					continue;
				if ( ! preg_match( '|\.css$|', $file ) )
					continue;
				
				$template = $this->_get_template_data( "{$this->_templates_directory}/$file" );
				
				if ( is_array( $template ) )
					$this->_templates[$template['file']] = $template;
			}
			
			return $this->_templates;
		}
		
		function _get_template_data( $file ) {
			$file_handle = fopen( $file, 'r' );
			$data = fread( $file_handle, 8192 );
			fclose( $file_handle );
			
			preg_match( '|Name:(.*)$|mi', $data, $name );
			preg_match( '|Class:(.*)$|mi', $data, $class );
			
			$name = trim( $name[1] );
			$class = trim( $class[1] );
			
			if ( empty( $name ) || empty( $class ) )
				return false;
			
			return array( 'file' => basename( $file ), 'name' => $name, 'class' => $class );
		}
	}
	
	new ITTabber();
}

?>
