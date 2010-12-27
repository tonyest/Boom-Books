<?php

	settings_errors();
	?>
	<div class="wrap">
		<form action="options.php" method="post">
			<?php 
			settings_fields( 'pp_core_options' );//settings fields pp_core_options
			screen_icon( 'prospress' ); 
			?>
			<h2><?php _e( 'Prospress Settings', 'prospress' ) ?></h2>
		<?php get_userselect(); ?>
		<p class="submit">
			<input type="submit" value="Save" class="button-primary" name="submit">
		</p>
		</form>
	</div>






