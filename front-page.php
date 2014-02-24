<?php
/**
 * Template Name: Front
 * Description: The home page when static home page is selected
 *
 * @package Jewel
 */

get_header(); ?>

<div id="primary" class="full-width">
  <div id="content" role="main">
  
    <?php echo do_shortcode('[nivoslider slug="splash"]'); ?>
  
  </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>
