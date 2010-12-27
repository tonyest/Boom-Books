<script>
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
</script>

<?php

function playground_callback(){
?>
Hello, world!
<?php
}

add_meta_box("playground", __('Playground', 'playground'), "playground_callback", "playground");
	echo "\t<div class='postbox-container' style='49%'>\n";
do_meta_boxes('playground','advanced',null);
echo "</div>";
	echo "\t<div class='postbox-container' style='49%'>\n";
do_meta_boxes('playground','advanced',null);
echo "</div>";
?>

<?php
wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
?>

<div class="dbx-b-ox-wrapper">
<fieldset id="myplugin_fieldsetid" class="dbx-box">
<div class="dbx-handle-wrapper"><h3 class="dbx-handle"><?php _e( 'My Post Section Title', 'myplugin_textdomain' ); ?></h3></div>
<div class="dbx-content-wrapper">
<div class="dbx-content">
Your plugin box content here
</div>
</div>
</fieldset>
</div>