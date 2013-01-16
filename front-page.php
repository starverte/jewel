<?php
/**
 * Template Name: Front
 * Description: The home page when static home page is selected
 *
 * @package WordPress
 * @subpackage Jewel
 */

get_header(); ?>

		<div id="primary" class="full-width">
			<div id="content" role="main">

				<?php echo do_shortcode('[nivoslider slug="splash"]'); ?>

				<!--
				Links and welcome text -->
				<div id="mid">
					<div><!-- Quick links --></div>
					<div id="welcome">
						<h3><!-- Pull title from theme options page --></h3>
						<p><!-- Pull text from theme options page --></p>
					</div>
				</div>

				<!--
				Front Page Boxes -->
				<div id="boxes">
					<div id="box1"><!-- Dynamically insert title, image, and text --></div>
					<div id="box2"><!-- Dynamically insert title, image, and text --></div>
					<div id="box3"><!-- Dynamically insert title, image, and text --></div>
				</div> 

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>