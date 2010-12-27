jQuery(document).ready( function($) {
	// close postboxes that should be closed
	jQuery('.if-js-closed').removeClass('if-js-closed').addClass('closed');

	// postboxes
	<?php
	global $wp_version;
	if(version_compare($wp_version,"2.7-alpha", "<")){
		echo "add_postbox_toggles('yourpluging');"; //For WP2.6 and below
	}
	else{
		echo "postboxes.add_postbox_toggles('yourplugin');"; //For WP2.7 and above
	}
	?>

});