<?php
/*
Plugin Name: Boom Books
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
include_once(BB_PLUGIN_DIR.'/includes/functions.php');
include_once(BB_PLUGIN_DIR.'/bb-load.php');
include_once(BB_PLUGIN_DIR.'/bb-templatetags.php');

/*
 *BOOM BOOKS custom type
 *install the first boombooks custom page
 *
 *				Create Boom Book Page
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
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	/* Boom Books 'bb_sets' table constructor */
	$table_name = $wpdb->prefix . "bb_sets";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			setID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			userID bigint(20) UNSIGNED NOT NULL,
			start datetime NOT NULL,
			category tinytext NOT NULL,			
			status tinytext NOT NULL,
			parent bigint(20) UNSIGNED,
			PRIMARY KEY  (setID),
			FOREIGN KEY (userID) REFERENCES wp_users(ID)
		);";

      dbDelta($sql);
   }
	/* Boom Books 'bb_efforts' table constructor */

	$table_name = $wpdb->prefix . "bb_efforts";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			effortID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			setID bigint(20) UNSIGNED NOT NULL,
			discipline tinytext NOT NULL,
			setting tinytext NOT NULL,			
			difficulty tinyint(2) UNSIGNED DEFAULT '0' NOT NULL,
			duration time NOT NULL,
			distance float(6,2) UNSIGNED DEFAULT '0' NOT NULL,			
			details text NOT NULL,
			max_hr int UNSIGNED,
			avg_hr int UNSIGNED,
			water float(4,2) UNSIGNED DEFAULT '0' NOT NULL,
			PRIMARY KEY  (effortID,setID),
			FOREIGN KEY (setID) REFERENCES wp_bb_sets(setID)
		);";
      dbDelta($sql);
   }
	/* Boom Books 'bb_stretches' table constructor */

	$table_name = $wpdb->prefix . "bb_stretches";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			stretchesID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			setID bigint(20) UNSIGNED NOT NULL,
			muscle tinytext NOT NULL,
			duration time NOT NULL,		
			PRIMARY KEY  (stretchesID,setID),
			FOREIGN KEY (setID) REFERENCES wp_bb_sets(setID)
		);";
      dbDelta($sql);
   }
	/* Boom Books 'bb_daily' table constructor */

	$table_name = $wpdb->prefix . "bb_dailys";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			dailyID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			userID bigint(20) UNSIGNED NOT NULL,
			date date NOT NULL,
			RHR tinyint(3) UNSIGNED DEFAULT '0' NOT NULL,
			water tinyint(3) UNSIGNED DEFAULT '0' NOT NULL,
			sleep tinyint(3) UNSIGNED DEFAULT '0' NOT NULL,		
			PRIMARY KEY  (dailyID,userID,date),
			FOREIGN KEY (userID) REFERENCES wp_users(ID)
		);";
      dbDelta($sql);
   }
	/* Boom Books 'bb_journal' table constructor */

	$table_name = $wpdb->prefix . "bb_journals";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			journalID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			setID bigint(20) UNSIGNED,
			effortID biging(20) UNSIGNED,
			dailyID bigint(20) UNSIGNED NOT NULL,
			meal tinytext NOT NULL,
			time time NOT NULL,
			foods text NOT NULL,	
			PRIMARY KEY  (journalID,dailyID),
			FOREIGN KEY (dailyID) REFERENCES wp_bb_dailys(dailyID)
		);";
      dbDelta($sql);
   }
      add_option("bb_db_version", $bb_db_version);
}

/*
 *cleanup functions for de-activation (currently not working)
 *
 *
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