<?php
/**
 * The template for displaying Stone Type Archive pages.
 *
 * @package Jewel
 * @since 0.1
 */

get_header(); ?>

<section id="primary">
  <div id="content" role="main">
    
    <?php if ( have_posts() ) : ?>
    
      <?php jewel_content_nav( 'nav-above' ); ?>
      
      <?php /* Start the Loop */ ?>
      <?php $count = 1;
      while (have_posts()) : the_post();
        $third = (($count%3 == 0)) ? 'third' : ''; ?>
        
        <script type="text/javascript">
          jQuery(document).ready(function ($) {
            $("#post-<?php the_ID(); ?>").hovercard({
              detailsHTML: $('#desc-<?php the_ID(); ?>').html(),
              width: 466,
              delay: 500,
            });
          });
        </script>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class("$third"); ?>>
          <a href="#" id="post-<?php the_ID(); ?>">
            <?php if ( has_post_thumbnail() ) { the_post_thumbnail();} 
            $jewelry_price_value = get_post_meta($post->ID, 'jewelry_price', true);
            if (!$jewelry_price_value) {
              echo '';
            }
            else {
              echo '<div class="thumb-price">$'.$jewelry_price_value.'</div>';
            } ?>
          </a>
        </article><!-- #post-<?php the_ID(); ?> -->
        
        <?php $count++; ?>
      <?php endwhile; ?>
      
      <?php else : ?>
      
      <article id="post-0" class="post no-results not-found">
        <header class="entry-header">
          <h1 class="entry-title"><?php _e( 'Nothing Found', 'jewel' ); ?></h1>
        </header><!-- .entry-header -->
        
        <div class="entry-content">
          <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'jewel' ); ?></p>
          <?php get_search_form(); ?>
        </div><!-- .entry-content -->
      </article><!-- #post-0 -->
      
    <?php endif; ?>

    <?php /* Start the Loop */ ?>
    <?php $count = 1;
    while (have_posts()) : the_post();?>
    
      <article style="display: none;" class="description" id="desc-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="jheader">
          <h1 class="jtitle"><?php the_title(); ?></h1>
        </header><!-- .jheader -->
        
        <div class="jcontent">
        
          <?php if ( has_post_thumbnail() ) { the_post_thumbnail(); }
          the_content();
          
          $jewelry_price_value    = get_post_meta( $post->ID, 'jewelry_price'   , true);
          $jewelry_shipping_value = get_post_meta( $post->ID, 'jewelry_shipping', true);
          $title                  = get_the_title();
          
          if (!$jewelry_price_value) {
            echo '';
          }
          else {
             echo '<div class="desc-price">Price: $'.$jewelry_price_value.'</div>';
          }
          if (!$jewelry_shipping_value) {
            echo '';
          }
          else {
            echo '<div class="desc-ship">Shipping: $'.$jewelry_shipping_value.'</div>';
          }
          echo print_wp_cart_button_for_product( $title , $jewelry_price_value); ?>
          
        </div><!-- .jcontent -->
      </article><!-- #post-<?php the_ID(); ?> -->
      
      <?php $count++; ?>
    <?php endwhile; ?>
    
    <div id="clear">&nbsp;</div>
    
    <?php jewel_content_nav( 'nav-below' ); ?>
    
    <div id="clear">&nbsp;</div>
    
    <header class="page-header">
      <h1 class="page-title"><?php printf( __( '%s', 'jewel' ), '<span>' . single_term_title( '', false ) . '</span>' );?></h1>
      
      <?php
      $category_description = category_description();
      if ( ! empty( $category_description ) )
        echo apply_filters( 'category_archive_meta', '<div class="category-archive-meta">' . $category_description . '</div>' );
      ?>
    </header>
    
  </div><!-- #content -->
</section><!-- #primary -->

<?php if ( is_active_sidebar( 'storeside' ) ) : ?>
  <div id="tertiary" class="widget-area" role="complementary">
    <?php dynamic_sidebar( 'storeside' ); ?>
  </div><!-- #tertiary .widget-area -->
<?php endif; ?>

<?php get_footer(); ?>
