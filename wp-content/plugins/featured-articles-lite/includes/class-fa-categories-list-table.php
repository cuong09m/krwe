<?php
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @url http://www.codeflavors.com/featured-articles-pro/
 * @version 2.4
 * @since 2.4.8
 */

/**
 * Load WP_List_Table class
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class FA_Categories_List_Table extends WP_List_Table {
	/**
     * Constructor. Takes as argument the singular/plural of items 
     * and whether the table is ajax based
     * @param array $args
     */
    function __construct(){
    	
    	$this->admin_page = menu_page_url('featured-articles-lite-publish-categories-list', false);
    	
    	parent::__construct( array(
			'singular' => 'category',
			'plural' => 'categories',
			'ajax' => false
		) );        
    } 
        
    /**
	 * (non-PHPdoc)
	 * @see WP_List_Table::ajax_user_can()
	 */
	function ajax_user_can() {
		return current_user_can( FA_CAPABILITY );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::no_items()
	 */
	function no_items() {
		_e( 'No categories found.', 'falite' );
	}
	
	/**
     * The default table column if custom function is not defined.
     * @param array $item
     * @param string $column_name
     */
    function column_default($item, $column_name){        
    	if( !array_key_exists($column_name, $item) ){    		
    		trigger_error('Column not found. Column name: '.$column_name, E_USER_NOTICE);    		
    	}else{
    		return $item[$column_name];
    	}
    } 
    
	/**
     * The checkbox for bulk actions column
     * @param array $item
     */
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="item_id[]" value="%2$s" id="content_%2$d" />',
            /*$1%s*/ $this->_args['singular'],
            /*$2%s*/ $item['cat_ID']
        );
    }
    
    function column_cat_name($item){
    	return sprintf('<label for="content_%1$d" id="label_content_%1$d">%2$s</label>', 
    		$item['cat_ID'], 
    		$item['cat_name']
    	);   
    }
    
    /**
     * (non-PHPdoc)
     * @see WP_List_Table::get_columns()
     */
	function get_columns() {		
		$columns = array(
			'cb'          			=> '',
			'cat_name'				=> __('Category', 'falite'),
			'category_description' 	=> __( 'Description', 'falite' ),
			'count'  				=> __( 'Posts', 'falite' )
		);

		return $columns;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::get_sortable_columns()
	 */
	function get_sortable_columns() {
		return array();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::get_views()
	 */
	function get_views(){
		return array();
	}
	
	/**
     * (non-PHPdoc)
     * @see WP_List_Table::get_bulk_actions()
     */
    function get_bulk_actions() {    	
    	return array();
    }
    
    /**
     * (non-PHPdoc)
     * @see WP_List_Table::prepare_items()
     */
    function prepare_items() {
        
    	$columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        $per_page = 13;
        
        $current_page = $this->get_pagenum();
        $offset = ( $current_page-1 ) * $per_page;
        
        $categories = get_categories(array(
        	'hide_empty' => 0
        ));
        
        foreach( $categories as $k=>$c ){
        	$categories[$k] = (array)$c;
        }
       	
       	$this->items	= (array)$categories;
    	$total_items 	= count($categories);
    	
    	$this->set_pagination_args( array(
            'total_items' => $total_items,                  
            'per_page'    => $per_page,                     
            'total_pages' => ceil($total_items/$per_page)  
        ) );
    } 
    
}