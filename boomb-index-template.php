<?php
/*
Template Name: BoomB Page
*/
/*
 * @package WordPress
 * @subpackage BoomBooks
 * @since Boom Books 0.0
 */	
get_header(); ?>
<div id="sidebars">

	<div id="boomb" class="widget-area boomb-inside">
		<ul id="sidebar-inside-right" class="xoxo">
		<?php 
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : 
		 	$args = array(
			'before_widget' => '<li id="bb_stats" class="widget-container">',
			'after_widget'  => '</li>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>' );
			$instance = array(
				'title' => 'Boom Books Bitches'
			);
			
		the_widget('boomb_stats_widget', $instance ,$args);
		$args = array(
			'before_widget' => '<li id="bb_editor" class="widget-container">',
			'after_widget'  => '</li>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>' );
			$instance = array(
				'title' => 'BoomB'
			);
		the_widget('boomb_editor_widget', NULL, $args);
		endif;
		?>
		</ul>
	</div>
	<?PHP get_sidebar(); ?>
</div>
<div id="content" role="main">
	<?php
		if ( is_user_logged_in() ) {
			?>
			<div id="boomb_content">
			<?php 
			do_action( 'boomb_content' , $_GET['page'] );
		} else {
		?>
					<h1 style="clear:none;">Boom Books</h1>
					<p>login to access Boom Books experience</p>
				<?php
				//User not logged in:generate login form
				$args = array(
				        'echo' => true,
				        'form_id' => 'loginform',
						'redirect' => home_url().'/boom-book-4/',
				        'label_username' => __( 'Username' ),
				        'label_password' => __( 'Password' ),
				        'label_remember' => __( 'Remember Me' ),
				        'label_log_in' => __( 'Log In' ),
				        'id_username' => 'user_login',
				        'id_password' => 'user_pass',
				        'id_remember' => 'rememberme',
				        'id_submit' => 'wp-submit',
				        'remember' => true,
				        'value_username' => NULL,
				        'value_remember' => false );
					wp_login_form( $args );	
			}
		?>
	</div>
</div><!-- #content -->
<?PHP get_footer(); ?>