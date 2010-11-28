<?php
/*
Template Name: Boom Book
*/
/*
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
	
get_header(); ?>

		<div id="container">
			<div id="content" role="main">

				<br/>
				<br/>
				<br/>
				<br/>
				<form class="form-user" action="submit_names.asp" method="post">
					First name: <input type="text" name="firstname" ><br/>
					Last name: <input type="text" name="lastname"/>
					<input type="submit" value="Submit" />
				</form>


			</div><!-- #content -->
		</div><!-- #container -->

<?PHP get_sidebar(); ?>
<?PHP get_footer(); ?>



