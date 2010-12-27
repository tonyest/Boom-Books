<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
		// close postboxes that should be closed
		$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
		// postboxes setup
				$('.bb-datepicker').datepicker();
		postboxes.add_postbox_toggles('boom-books_page_bb_submit');
	});
	//]]>
</script>

 <?php 
 global $screen_layout_columns;	
wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
 <?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
 <div id="poststuff" class="metabox-holder has-right-sidebar" style="min-width:900px">
     <div id="side-info-column" class="inner-sidebar">
			<?php $meta_boxes = do_meta_boxes('boom-books_page_bb_submit', 'side', $test_object); ?>
     </div>
     <div id="post-body" class="has-sidebar">
         <div id="post-body-content" class="has-sidebar-content">
			<?php $meta_boxes = do_meta_boxes('boom-books_page_bb_submit', 'normal', $test_object); ?>
        </div>
     </div>
     <br class="clear"/>
 </div>
<script>
