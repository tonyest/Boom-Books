<?PHP
/*
 *  REMOVE DEADWEIGHT DASHBOARD WIDGETS
 *
 *
 *
 */
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
function remove_dashboard_widgets() {
	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	global $wp_meta_boxes;
	//remove primary wordpress blog widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	//remove secondary other news widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	// remove plugins widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
}
/*
 *add_bb_dashboard_widget()
 *  		-ADD BOOM BOOKS DASHBOARD WIDGET
 *bb_widget_dailys
 *			-CONSTRUCTOR FOR DASHBOARD WIDGET
 *
 */
//	add_action('wp_dashboard_setup', 'add_bb_dashboard_widget' );
function add_bb_dashboard_widget() {
	wp_add_dashboard_widget('add_bb_dashboard_widget', 'Quick-Boom!', 'bb_dash_widget');
} 
	function bb_dash_widget() {include(BB_PLUGIN_DIR.'/bb-dash/bb-dash-widget.php');}
/*
 * CUSTOM FUNCTION FOR CURRENT TIME
 *	 	better format etc
 *
 *
 */	
function current_time_fixed( $type, $gmt = 0 ) {
		$t =  ( $gmt ) ? gmdate( 'Y-m-d H:i:s' ) : gmdate( 'Y-m-d H:i:s', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) );
		switch ( $type ) {
			case 'mysql':
				return $t;
				break;
			case 'timestamp':
				return strtotime($t);
				break;
		}
	}

?>