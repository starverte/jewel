<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package Jewel
 * @since Jewel 0.1
 */

get_header(); ?>

    <section id="primary">
      <div id="content" role="main">

      <?php if ( have_posts() ) : ?>

        <header class="page-header">
          <h1 class="page-title"><?php
            printf( __( 'Category Archives: %s', 'jewel' ), '<span>' . single_cat_title( '', false ) . '</span>' );
          ?></h1>

          <?php
            $category_description = category_description();
            if ( ! empty( $category_description ) )
              echo apply_filters( 'category_archive_meta', '<div class="category-archive-meta">' . $category_description . '</div>' );
          ?>
        </header>

        <?php jewel_content_nav( 'nav-above' ); ?>

        <?php /* Start the Loop */ ?>
        <?php $count = 1;
          while (have_posts()) : the_post();
          $fourth = (($count%4 == 0)) ? 'fourth' : ''; ?>

          <article id="post-<?php the_ID(); ?>" <?php post_class("$fourth"); ?>>
  <header class="entry-header">
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <?php edit_post_link( __( 'Edit', 'jewel' ), '<span class="edit-link">', '</span>' ); ?>

    <div class="entry-meta">
      <?php jewel_posted_on(); ?>
    </div><!-- .entry-meta -->
  </header><!-- .entry-header -->

  <div class="entry-content">
    <?php the_content(); ?>
    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'jewel' ), 'after' => '</div>' ) ); ?>
  </div><!-- .entry-content -->

  <footer class="entry-meta">
    <?php
      /* translators: used between list items, there is a space after the comma */
      $category_list = get_the_category_list( __( ', ', 'jewel' ) );

      /* translators: used between list items, there is a space after the comma */
      $tag_list = get_the_tag_list( '', ', ' );

      if ( ! jewel_categorized_blog() ) {
        // This blog only has 1 category so we just need to worry about tags in the meta text
        if ( '' != $tag_list ) {
          $meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'jewel' );
        } else {
          $meta_text = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'jewel' );
        }

      } else {
        // But this blog has loads of categories so we should probably display them here
        if ( '' != $tag_list ) {
          $meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'jewel' );
        } else {
          $meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'jewel' );
        }

      } // end check for categories on this blog

      printf(
        $meta_text,
        $category_list,
        $tag_list,
        get_permalink(),
        the_title_attribute( 'echo=0' )
      );
    ?>

    
  </footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->

        <?php $count++; ?>
        <?php endwhile; ?>

        <?php jewel_content_nav( 'nav-below' ); ?>

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

      </div><!-- #content -->
    </section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>