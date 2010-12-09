<?php

/*constructor for the Boom-Books page*/

/*
 *INITIALISE SCRIPTS INTO BOOM BOOKS ADMIN/PLUGIN AREAS ONLY
 *register styles and enqueue scripts first to place in correct part of header
 *enqueue scripts after checking plugin area
 *
 */
add_action( 'admin_init', 'bb_admin_init_scripts' );
function bb_admin_init_scripts() {
//	wp_deregister_script('jquery');

//	wp_register_script('jquery',BB_PLUGIN_URL.'/js/jquery-1.4.2.min.js',false,'1.4.3','');
//	wp_register_script('jqueryUI',BB_PLUGIN_URL.'/js/jquery-ui-1.8.6.custom.min.js', 'jquery','1.8.6','');
//	wp_register_script('jqueryUI-core',BB_PLUGIN_URL.'/js/ui/jquery.ui.core.js', 'jquery','1.8.6','');
//	wp_register_script('jqueryUI-datepicker',BB_PLUGIN_URL.'/js/ui/jquery.ui.datepicker.js', 'jqueryUI-core','1.8.6','');
		
//	wp_register_script('bb-admin',BB_PLUGIN_URL.'/js/bb-admin.js','jqueryUI');
//	wp_register_script('bb-admin-submit',BB_PLUGIN_URL.'/js/bb-admin-submit.js','jqueryUI');
//	wp_register_script('jqueryUI-spinner',BB_PLUGIN_URL.'/js/ui.spinner.js','jqueryUI');
//	wp_register_script('jqueryUI-spinner-min',BB_PLUGIN_URL.'/js/ui.spinner.min.js','jqueryUI');

//	wp_enqueue_style('redmond',BB_PLUGIN_URL.'/css/redmond/jquery-ui-1.8.6.custom.css',false,'1.8.6');
//	wp_enqueue_style('bb-admin-style', BB_PLUGIN_URL.'/css/bb-admin-style.css');
	wp_enqueue_style('google-fonts','http://fonts.googleapis.com/css?family=Reenie+Beanie|IM+Fell+DW+Pica+SC&subset=latin');
}
function bb_admin_scripts() {
//	wp_enqueue_script('jquery');
//	wp_enqueue_script('jqueryUI');
	
//	wp_enqueue_script('jqueryUI-core');
//	wp_enqueue_script('jqueryUI-datepicker');
//	wp_enqueue_script('jqueryUI-spinner');
//	wp_enqueue_script('jqueryUI-spinner-min');
	
//	wp_enqueue_script( 'bb-admin');
}
function bb_submit_scripts() {
//		wp_enqueue_script( 'bb-admin-submit');

	wp_enqueue_script('common');
	wp_enqueue_script('wp-lists');
	wp_enqueue_script('postbox');
}
/*
 *BOOM BOOKS ADMIN MENU CONSTRUCTOR	
 *create boom books admin menu 
 *and hook scripts specifically to this page.
 *
 */
add_action( 'admin_menu', 'bb_admin_menu' );
function bb_admin_menu() {
	
	$page_title = __( 'Boom Books', 'boom_books' );
	$menu_title = __( 'Boom Books', 'boom_books' );
	$capability = 'edit_posts';
	$menu_slug = 'boom_books';
	$function = 'bb_admin_menu_boom_books';
	$icon_url = '/wp_BT/favicon.ico';

	$page = add_menu_page( $page_title , $menu_title , $capability , $menu_slug , $function , $icon_url );
				add_action( 'admin_print_scripts-' . $page , 'bb_admin_scripts' );
				add_action( 'admin_print_scripts-' . $page , 'bb_submit_scripts' );					
	$page = add_submenu_page( $menu_slug, __( 'Program', 'boom_books' ) , __( 'Program', 'boom_books' ) , $capability , 'boom_books' , $function ); 
				add_action( 'admin_print_scripts-' . $page , 'bb_admin_scripts' );
				
	$page = add_submenu_page( $menu_slug, __( 'Reports', 'boom_books' ) , __( 'Reports', 'boom_books' ) , $capability , 'bb_reports' , 'bb_admin_submenu_report' ); 
				add_action( 'admin_print_scripts-' . $page, 'bb_admin_scripts' );
				
	$page = add_submenu_page( $menu_slug , __( 'Submit', 'boom_books' ) , __( 'Submit', 'boom_books' ) , $capability , 'bb_submit' , 'bb_admin_submenu_submit' ); 
				add_action( 'admin_print_scripts-' . $page , 'bb_admin_scripts' );
				add_action( 'admin_print_scripts-' . $page , 'bb_submit_scripts' );	

add_meta_box(	'programs', __('Program'), 'get_program', 'boom-books_page_bb_submit', 'normal', 'core');
//add_meta_box(	'reports', __('Reports'), 'get_report', 'boom-books_page_bb_submit', 'normal', 'core');

add_meta_box(	'editor', __('Editor'), 'get_editor', 'boom-books_page_bb_submit', 'side', 'core');
//add_meta_box(	'summary', __('Summary'), 'get_summary', 'boom-books_page_bb_submit', 'side', 'core');
								
	$page = add_submenu_page( $menu_slug , __( 'Program Author', 'boom_books' ) , __( 'Program Author', 'boom_books' ) , 'edit_others_posts' , 'bb_author' , 'bb_admin_submenu_author' );
				add_action( 'admin_print_scripts-' . $page , 'bb_admin_scripts' );
				add_action( 'admin_print_scripts-' . $page , 'bb_submit_scripts' );	
							
//	$page = add_submenu_page( $menu_slug, __( 'Dailys', 'boom_books' ), __( 'Dailys', 'boom_books' ), $capability, 'dailys', 'bb_admin_submenu_dailys'); 
//				add_action('admin_print_scripts-' . $page,'bb_admin_scripts');

//				$page = add_submenu_page( $menu_slug, __( 'playground', 'boom_books' ), __( 'playground', 'boom_books' ), $capability, 'playground', 'bb_admin_submenu_playground'); 
//							add_action('admin_print_scripts-' . $page,'bb_playground_script');				
}
/*
 *BOOM BOOKS admin menu content 
 *read it and weep!
 *
 *
 */
function bb_admin_menu_boom_books() {
/* Output our admin page */
	include(BB_PLUGIN_DIR.'/bb-menu/bb-menu-program.php');
}
function bb_admin_submenu_author() {
	include(BB_PLUGIN_DIR.'/bb-menu/bb-menu-author.php');
}
function bb_admin_submenu_submit() {
	include(BB_PLUGIN_DIR.'/bb-menu/bb-menu-submit.php');
}
function bb_admin_submenu_report() {
	include(BB_PLUGIN_DIR.'/bb-menu/bb-menu-report.php');
}
function bb_admin_playground() {
	include(BB_PLUGIN_DIR.'/bb-menu/bb-playground.php');
}
function bb_admin_submenu_dailys() {
//	include(BB_PLUGIN_DIR.'/bb-menu/bb-menu-template-1.1.php');
	include(BB_PLUGIN_DIR.'/bb-menu/bb-menu-dailys.php');
}
function bb_admin_submenu_playground () {
	include(BB_PLUGIN_DIR.'/bb-menu/bb-menu-playground.php');
}

function bb_load_db(){
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	if (!isset($wpdb->bb_sets)) {
		$wpdb->bb_sets = $table_prefix . 'bb_sets';
	}
	if (!isset($wpdb->bb_efforts)) {
		$wpdb->bb_efforts = $table_prefix . 'bb_efforts';
	}
	if (!isset($wpdb->bb_stretches)) {
		$wpdb->bb_stretches = $table_prefix . 'bb_stretches';
	}
	if (!isset($wpdb->bb_journals)) {
		$wpdb->bb_journals = $table_prefix . 'bb_journals';
	}
	if (!isset($wpdb->bb_dailys)) {
		$wpdb->bb_dailys = $table_prefix . 'bb_dailys';
	}
}
add_action( 'admin_menu', 'bb_load_db' );



/*
 *BOOM BOOKS custom type
 *register a custom template type for boom books type
 *
 *				DISABLED
 */
//add_action( 'init', 'create_bb_type' ); // boom book custom type
function create_bb_type() {
$labels = array(
   'name' => _x('Boom Books','Boom Books'),
   'add_new' => _x('Add New', 'Boom Book'),
   'add_new_item' => __('Add New Boom Book'),
   'edit_item' => __('Edit Boom Book'),
   'new_item' => __('New Boom Book'),
   'view_item' => __('View Boom Book'),
   'search_items' => __('Search Boom Books'),
   'not_found' =>  __('No Boom Books found'),
   'not_found_in_trash' => __('No Boom Books found in Trash'), 
   'parent_item_colon' => ''
 );
 $args = array(
   'labels' => $labels,
   'public' => true,
   'publicly_queryable' => true,
   'show_ui' => true, 
   'query_var' => true,
   'rewrite' => false, //array('slug' => 'boom_book'),
   'show_in_nav_menus' => true,
   'capability_type' => 'post',
   'show_ui' => true,
   'hierarchical' => false,
   'menu_position' => null,
   'supports' => array('title','excerpt')
 ); 
 register_post_type('boom_books',$args);
}

// register FooWidget widget
include_once(BB_PLUGIN_DIR.'/bb-widget.class.php');
add_action('widgets_init', create_function('', 'return register_widget("bboom_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("boomb_stats_widget");'));


/*
 *		Boom books init
 *		
 *
 *
 */
function bb_init() {

	load_plugin_textdomain( 'boomb', NULL, BB_PLUGIN_DIR );		//set internationalisation domain		
		
	if ( !is_admin() ) {
			wp_register_script('jquery',BB_PLUGIN_URL.'/js/jquery-1.4.2.min.js',false,'1.4.3','');
			wp_register_script('jqueryUI',BB_PLUGIN_URL.'/js/jquery-ui-1.8.6.custom.min.js', 'jquery','1.8.6','');
			wp_register_script('jqueryUI-core',BB_PLUGIN_URL.'/js/ui/jquery.ui.core.js', 'jquery','1.8.6','');
			wp_register_script('jqueryUI-datepicker',BB_PLUGIN_URL.'/js/ui/jquery.ui.datepicker.js', 'jqueryUI-core','1.8.6','');

			wp_register_script('jqueryUI-spinner',BB_PLUGIN_URL.'/js/ui.spinner.js','jqueryUI');
			wp_register_script('jqueryUI-spinner-min',BB_PLUGIN_URL.'/js/ui.spinner.min.js','jqueryUI');

			wp_enqueue_style('redmond',BB_PLUGIN_URL.'/css/redmond/jquery-ui-1.8.6.custom.css',false,'1.8.6');
		//	wp_enqueue_style('bb-admin-style', BB_PLUGIN_URL.'/css/bb-admin-style.css');
			wp_enqueue_style('bb-admin-style', BB_PLUGIN_URL.'/css/bb-widget.css');	
			wp_enqueue_style('google-fonts','http://fonts.googleapis.com/css?family=Reenie+Beanie|IM+Fell+DW+Pica+SC&subset=latin');
			wp_enqueue_script('jquery');
			wp_enqueue_script('jqueryUI');

			wp_enqueue_script('jqueryUI-core');
//			wp_enqueue_script('jqueryUI-datepicker');
			wp_enqueue_script('jqueryUI-spinner');
			wp_enqueue_script('jqueryUI-spinner-min');
	}	
}
add_action( 'init' , 'bb_init' );
?>