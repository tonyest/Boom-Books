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
	wp_deregister_script('jquery');
//external java libraries
	//wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"), false, '1.4.3','');
	//wp_register_script('jqueryUI', ("https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"), 'jquery','1.8.6','');
	wp_register_script('jquery',BB_PLUGIN_URL.'/js/jquery-1.4.2.min.js',false,'1.4.3','');
	wp_register_script('jqueryUI',BB_PLUGIN_URL.'/js/jquery-ui-1.8.6.custom.min.js', 'jquery','1.8.6','');
	wp_register_script('bb-admin',BB_PLUGIN_URL.'/js/bb-admin.js','jqueryUI');
	wp_register_script('bb-admin-submit',BB_PLUGIN_URL.'/js/bb-admin-submit.js','jqueryUI');
	wp_register_script('jqueryUI-spinner',BB_PLUGIN_URL.'/js/ui.spinner.js','jqueryUI');
	wp_register_script('jqueryUI-spinner-min',BB_PLUGIN_URL.'/js/ui.spinner.min.js','jqueryUI');

//	wp_enqueue_style('jqdark',BB_PLUGIN_URL.'/css/ui-darkness/jquery-ui-1.8.6.custom.css',false,'1.8.6');
	wp_enqueue_style('jq_eggplant',BB_PLUGIN_URL.'/css/custom-theme/jquery-ui-1.8.6.custom.css',false,'1.8.6');
	wp_enqueue_style('bb-admin-style', BB_PLUGIN_URL.'/css/bb-admin-style.css');
	wp_enqueue_style('google-fonts','http://fonts.googleapis.com/css?family=Reenie+Beanie|IM+Fell+DW+Pica+SC&subset=latin');
}
function bb_admin_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jqueryUI');
	wp_enqueue_script('jqueryUI-spinner');
	wp_enqueue_script('jqueryUI-spinner-min');
	wp_enqueue_script( 'bb-admin');
}
function bb_submit_scripts() {
		wp_enqueue_script( 'bb-admin-submit');
}
/*
 *BOOM BOOKS ADMIN MENU CONSTRUCTOR	
 *create boom books admin menu 
 *and hook scripts specifically to this page.
 *
 */
add_action( 'admin_menu', 'bb_admin_menu' );
function bb_admin_menu() {
	/* Register our plugin page */
	$page_title = __( 'Boom Books', 'boom_books' );
	$menu_title = __( 'Boom Books', 'boom_books' );
	$capability = 'edit_posts';
	$menu_slug = 'boom_books';
	$function = 'bb_admin_menu_boom_books';
	$icon_url = '/wp_BT/favicon.ico';
	$position = '';

	$page = add_menu_page( $page_title , $menu_title , $capability , $menu_slug , $function , $icon_url );
				add_action( 'admin_print_scripts-' . $page , 'bb_admin_scripts' );
				
	$page = add_submenu_page( $menu_slug, __( 'Reports', 'boom_books' ) , __( 'Reports', 'boom_books' ) , $capability , 'bb_reports' , 'bb_admin_submenu_report' ); 
				add_action( 'admin_print_scripts-' . $page, 'bb_admin_scripts' );
				
	$page = add_submenu_page( $menu_slug , __( 'Submit', 'boom_books' ) , __( 'Submit', 'boom_books' ) , $capability , 'bb_submit' , 'bb_admin_submenu_submit' ); 
				add_action( 'admin_print_scripts-' . $page , 'bb_admin_scripts' );
				add_action( 'admin_print_scripts-' . $page , 'bb_submit_scripts' );	
								
	$page = add_submenu_page( $menu_slug , __( 'Program Author', 'boom_books' ) , __( 'Program Author', 'boom_books' ) , 'edit_others_posts' , 'bb_author' , 'bb_admin_submenu_author' );
				add_action( 'admin_print_scripts-' . $page , 'bb_admin_scripts' );
				add_action( 'admin_print_scripts-' . $page , 'bb_submit_scripts' );	
							
	$page = add_submenu_page( $menu_slug, __( 'Dailys', 'boom_books' ), __( 'Dailys', 'boom_books' ), $capability, 'dailys', 'bb_admin_submenu_dailys'); 
				add_action('admin_print_scripts-' . $page,'bb_admin_scripts');
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
function bb_admin_submenu_submit(){
	include(BB_PLUGIN_DIR.'/bb-menu/bb-menu-submit.php');
}
function bb_admin_submenu_report(){
	include(BB_PLUGIN_DIR.'/bb-menu/bb-menu-report.php');
}
function bb_admin_playground(){
	include(BB_PLUGIN_DIR.'/bb-menu/bb-playground.php');
}
function bb_admin_submenu_dailys(){
//	include(BB_PLUGIN_DIR.'/bb-menu/bb-menu-template-1.1.php');
	include(BB_PLUGIN_DIR.'/bb-menu/bb-menu-dailys.php');
}
/*
 *BOOM BOOKS custom type
 *install the first boombooks custom page
 *
 *				DISABLED
 */
//register_activation_hook( __FILE__, 'install_boombook' );
function install_boombook(){
	global $wpdb,$current_user;	
		$post_data = array(
			'post_status' => 'publish', 
			'post_type' => 'boom_book',
			'ping_status' => get_option('default_ping_status'),
			'post_name' => 'boom-book', // The name (slug) for your post
			'post_content' => 'Boom', //include(__DIR__.'/include/template2.php')
			'post_excerpt' => 'Boom Books is the conduit between member and coach',
			'post_title' => 'Boom Books'
		);
		return $post_id = wp_insert_post($post_data, false);
}
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

?>