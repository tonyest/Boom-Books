<?php
/*
Plugin Name: Boom Books (Alpha)
Plugin URI: http://
Description: Boom Books is an interface for exercise logging and reporting
Version: 0.1
Author: Anthony Khoo
Author URI: http://boomtimecyclesystems.com.au
License: GPL2
*/
if( !defined( 'BB_PLUGIN_DIR' ) )
	define( 'BB_PLUGIN_DIR', WP_PLUGIN_DIR . '/boom-books');
if( !defined( 'BB_PLUGIN_URL' ) )
	define( 'BB_PLUGIN_URL', WP_PLUGIN_URL . '/boom-books');
if( !defined( 'WP_ADMIN_URL' ) )
	define( 'WP_ADMIN_URL', get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php');

register_activation_hook(__FILE__,'bb_dbinstall');
//register_deactivation_hook(__FILE__,'bb_cleanup');
include_once(BB_PLUGIN_DIR.'/includes/boomb-db-install.php');
include_once(BB_PLUGIN_DIR.'/includes/functions.php');
include_once(BB_PLUGIN_DIR.'/includes/boomb-ajax.php');
include_once(BB_PLUGIN_DIR.'/includes/boomb-query-functions.php');
include_once(BB_PLUGIN_DIR.'/boomb-load.php');
include_once(BB_PLUGIN_DIR.'/boomb-templatetags.php');
/*
 *	BOOM BOOKS page
 *	install the first boombooks custom page
 *
 */
register_activation_hook( __FILE__, 'install_boombook' );
function install_boombook(){
	error_log('make page');
	global $wpdb,$current_user;	
		$post_data = array(
			'post_status' => 'publish', 
			'post_type' => 'page',
			'ping_status' => get_option('default_ping_status'),
			'post_name' => 'boom-book', // The name (slug) for your post
			'post_content' => 'Boom Books Content',
			'post_excerpt' => 'Boom Books is the conduit between member and coach',
			'post_title' => __('Boom Books' , 'bb')
		);
		return $post_id = wp_insert_post($post_data, false);
}
/*
 * Table constructor for Boom Books tables
 * inserts tables into wpdb for use in Boom Books loggin/queries
 * will join with wpdb users table to query
 *
 */
	global $bb_db_version;
	$bb_db_version = "0.1";
function bb_dbinstall () {
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	install_boomb_sessions();
	install_boomb_sets();
	install_boomb_stretches();
	install_boomb_dailys();
	install_boomb_journals();
	
	install_boomb_programs();
	install_boomb_program_sessions();
	install_boomb_program_sets();
	
	add_option("bb_db_version", $bb_db_version);
}
/*
 *cleanup functions for de-activation (currently not working)
 *
*/
function bb_cleanup(){
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$args = array('wp_bb_sets','wp_bb_efforts','wp_bb_dailys','wp_bb_stretches','wp_bb_journal');
	foreach($args as $table_name){
		if($wpdb->get_var("show tables like '$table_name'") == $table_name) {
		$sql = "DROP TABLE ".$table_name.";";
		     dbDelta($sql);
		}
	}
}

?>