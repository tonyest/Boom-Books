<?php


/*constructor for the Boom-Books page*/
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

// register boomb widgets
include_once(BB_PLUGIN_DIR.'/boomb-widget.class.php');
add_action('widgets_init', create_function('', 'return register_widget("boomb_editor_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("boomb_stats_widget");'));
/*
 *		
 *	Boom books init
 *
 */
function boomb_init() {

	load_plugin_textdomain( 'boomb', NULL, BB_PLUGIN_DIR );		//set internationalisation domain		

	if ( !is_admin() ) {
		if ( function_exists ('register_sidebar')) { 
			$args = array(
				'name'          => sprintf(__('BoomBar') , 'boomb' ),
				'id'            => 'boom-bar',
				'description'   => 'BoomBooks Custom Sidebar',
				'before_widget' => '<li id="%1$s" class="widget-area">',
				'after_widget'  => '</li>',
				'before_title'  => '<h3 class="bb-widget">',
				'after_title'   => '</h3>' );
		    register_sidebar( $args );
		}
		
			wp_register_script('jquery',BB_PLUGIN_URL.'/js/jquery-1.4.2.min.js',false,'1.4.3','');
			wp_register_script('jqueryUI',BB_PLUGIN_URL.'/js/jquery-ui-1.8.6.custom.min.js', 'jquery','1.8.6','');
			wp_register_script('jqueryUI-core',BB_PLUGIN_URL.'/js/ui/jquery.ui.core.js', 'jquery','1.8.6','');
			wp_register_script('jqueryUI-datepicker',BB_PLUGIN_URL.'/js/ui/jquery.ui.datepicker.js', 'jqueryUI-core','1.8.6','');

			wp_register_script('jqueryUI-spinner',BB_PLUGIN_URL.'/js/ui.spinner.js','jqueryUI');
			wp_register_script('jqueryUI-spinner-min',BB_PLUGIN_URL.'/js/ui.spinner.min.js','jqueryUI');

			wp_enqueue_style('redmond',BB_PLUGIN_URL.'/css/redmond/jquery-ui-1.8.6.custom.css',false,'1.8.6');
			wp_enqueue_style('boomb-admin-style', BB_PLUGIN_URL.'/css/boomb-admin-style.css');
			wp_enqueue_style('boomb-content-style', BB_PLUGIN_URL.'/css/boomb-content-style.css');			
			wp_enqueue_style('boomb-widget', BB_PLUGIN_URL.'/css/boomb-widget.css');	
			//wp_enqueue_style('google-fonts','http://fonts.googleapis.com/css?family=Reenie+Beanie|IM+Fell+DW+Pica+SC&subset=latin');
			wp_enqueue_script('jquery');
			wp_enqueue_script('jqueryUI');

			wp_enqueue_script('jqueryUI-core');
//			wp_enqueue_script('jqueryUI-datepicker');
			wp_enqueue_script('jqueryUI-spinner');
			wp_enqueue_script('jqueryUI-spinner-min');
			
			wp_enqueue_script('boomb-widget', BB_PLUGIN_URL.'/js/boomb-widget.js');
			wp_enqueue_script('boomb-content', BB_PLUGIN_URL.'/js/boomb-content.js');
			// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
			wp_localize_script( 'boomb-content', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}
}
add_action( 'init' , 'boomb_init' );


/*
 *  REDIRECT Boom Books page to custom index template
 *
 * 	
 *
 */
function boomb_template_redirect () {
if ( is_page( get_option( 'boomb_index_page' ) ) ):
	include( BB_PLUGIN_DIR . '/boomb-index-template.php' );
	exit;
endif;
}
add_action( 'template_redirect' , 'boomb_template_redirect' );



// add_filter('manage_users_columns', 'boomb_user_groups_column');
function boomb_user_groups_column($columns) {
$columns['boomb_groups'] = __('Groups cunny?');
return $columns;
}
//add_action('manage_users_custom_column', 'boomb_user_groups_values', 8, 3);
function boomb_user_groups_values( $value, $column_name, $id ) {
	if( $column_name!='boomb_groups' )
		return;
	// TODO: check if this user ($id) is a client in wp_usermeta

	return $id;
}
//add_action( 'admin_head', 'admin_users_update');
function admin_users_update() {
error_log(print_r($_GET,true));
error_log(print_r($_POST,true));
	// $clients = $_GET['my_clients'];
	// foreach($clients as $uid=>$client) {
	// 
	// // update wp_usermeta with new values
	// }
	return;
}




?>