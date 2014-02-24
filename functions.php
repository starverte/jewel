<?php
/**
 * Jewel functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package Jewel
 * @since Jewel 0.1
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'jewel_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override jewel_setup() in a child theme, add your own jewel_setup to your child theme's
 * functions.php file.
 */
function jewel_setup() {
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on jewel, use a find and replace
	 * to change 'jewel' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'jewel', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'jewel' ),
	) );

	/**
	 * Add support for the Aside and Gallery Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'gallery' ) );
}
endif; // jewel_setup

/**
 * Tell WordPress to run jewel_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'jewel_setup' );

/**
 * Set a default theme color array for WP.com.
 */
$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'eeeeee',
	'text' => '444444',
);

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function jewel_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'jewel_page_menu_args' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function jewel_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Posts/Pages Sidebar', 'jewel' ),
		'id' => 'sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Store Sidebar', 'jewel' ),
		'id' => 'storeside',
		'description' => __( 'For use on Store and Category pages', 'jewel' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'init', 'jewel_widgets_init' );

if ( ! function_exists( 'jewel_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since Jewel 1.2
 */
function jewel_content_nav( $nav_id ) {
	global $wp_query;

	?>
	<nav id="<?php echo $nav_id; ?>">
		<h1 class="assistive-text section-heading"><?php _e( 'Post navigation', 'jewel' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'jewel' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'jewel' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( 'Older items', 'jewel' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer items', 'jewel' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // jewel_content_nav


if ( ! function_exists( 'jewel_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own jewel_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Jewel 0.4
 */
function jewel_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'jewel' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'jewel' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'jewel' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'jewel' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'jewel' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( '(Edit)', 'jewel' ), ' ' );
					?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for jewel_comment()

if ( ! function_exists( 'jewel_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own jewel_posted_on to override in a child theme
 *
 * @since Jewel 1.2
 */
function jewel_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'jewel' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'jewel' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}
endif;

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Jewel 1.2
 */
function jewel_body_classes( $classes ) {
	// Adds a class of single-author to blogs with only 1 published author
	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	return $classes;
}
add_filter( 'body_class', 'jewel_body_classes' );

/**
 * Returns true if a blog has more than 1 category
 *
 * @since Jewel 1.2
 */
function jewel_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so jewel_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so jewel_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in jewel_categorized_blog
 *
 * @since Jewel 1.2
 */
function jewel_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'jewel_category_transient_flusher' );
add_action( 'save_post', 'jewel_category_transient_flusher' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function jewel_enhanced_image_navigation( $url ) {
	global $post;

	if ( wp_attachment_is_image( $post->ID ) )
		$url = $url . '#main';

	return $url;
}
add_filter( 'attachment_link', 'jewel_enhanced_image_navigation' );

/**
 * Load some necessary scripts
 */
function my_scripts_method() {
	wp_enqueue_script( 'scriptaculous-effects' );
	wp_enqueue_script( 'jquery' );
	wp_register_script( 'jquery.hovercard', get_stylesheet_directory_uri().'/js/hovercard.js');
	wp_enqueue_script('jquery.hovercard');
}    
 
add_action('wp_enqueue_scripts', 'my_scripts_method');

/**
 * Custom Post Type - Jewelry
 */
add_action( 'init', 'codex_custom_init' );
function codex_custom_init() {
  $directory = get_stylesheet_directory_uri();
  $labels = array(
    'name' => _x('Jewelry', 'post type general name'),
    'singular_name' => _x('Jewelry Piece', 'post type singular name'),
    'add_new' => _x('Add Jewelry Piece', 'jewelry'),
    'add_new_item' => __('Add New Piece to Store'),
    'edit_item' => __('Edit Jewelry Piece'),
    'new_item' => __('New Jewelry Piece'),
    'all_items' => __('Store'),
    'view_item' => __('View jewelry piece'),
    'search_items' => __('Search Store'),
    'not_found' =>  __('No jewelry pieces found. We lost our marbles too.'),
    'not_found_in_trash' => __('No jewelry pieces have been discarded, but who would?'), 
    'parent_item_colon' => '',
    'menu_name' => 'Store'

  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_icon' => $directory . '/images/jewelry-16x16.png',
    'menu_position' => 5,
    'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' )
  ); 
  register_post_type('jewelry',$args);
}

//add filter to ensure the text Jewelry Piece, or jewelry, is displayed when user updates a jewelry piece
add_filter( 'post_updated_messages', 'codex_jewelry_updated_messages' );
function codex_jewelry_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['jewelry'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Jewelry piece updated. <a href="%s">View profile</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Jewelry piece updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Jewelry piece restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Jewelry piece added to store. <a href="%s">View profile</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Jewelry piece saved.'),
    8 => sprintf( __('Jewelry piece submitted. <a target="_blank" href="%s">Preview profile</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Jewelry piece scheduled to go public: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview profile</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Jewelry piece (private) updated. <a target="_blank" href="%s">Preview profile</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

add_action( 'init', 'create_jewelry_taxonomies', 0 );
function create_jewelry_taxonomies()
{
/* Stone Type */
$labels = array(	
	'name' => _x( 'Stone Types', 'custom taxonomy general name'),
	'singular_name' => _x( 'Stone Type', 'custom taxonomy singular name' ),
	'menu_name' => __( 'Stone Types'),
	'search_items' => __( 'Search stone types' ), 
	'popular_items' => __( 'Most popular stone types' ), 
	'all_items' => __( 'All stone types' ),
	'edit_item' => __( 'Edit stone type' ),
	'update_item' => __( 'Update stone type' ), 
	'add_new_item' => __( 'Add new stone type' ),
	'new_item_name' => __( 'New stone type' ), 
	'separate_items_with_commas' => __( 'Separate stone types with commas' ),
	'add_or_remove_items' => __( 'Add or remove stone types' ),
	'choose_from_most_used' => __( 'Choose from most used stone types' ),
	'parent_item' => null,
    'parent_item_colon' => null,
);

register_taxonomy('stone','jewelry', array(
	'hierarchical' => false, 
	'labels' => $labels, 
	'show_ui' => true,
	'query_var' => true,
    	'rewrite' => true,
));
}

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 234, 132, true ); // default Post Thumbnail dimensions   
}

if ( function_exists( 'add_image_size' ) ) { 

}

// BEGIN - Create custom fields
add_action("admin_menu", "my_meta_boxes");

function my_meta_boxes() {
	add_meta_box('jewelry_meta', 'Details', 'jewelry_meta', 'jewelry', 'side', 'high');
}
function hide_meta_boxes() {
	remove_meta_box( 'postcustom' , 'post' , 'normal' );
	remove_meta_box( 'postcustom' , 'page' , 'normal' ); 
}
add_action( 'admin_menu' , 'hide_meta_boxes' );

/* Staff Details */
function jewelry_meta() {
	global $post;
	$custom = get_post_custom($post->ID);
	$jewelry_price = $custom["jewelry_price"] [0];
	$jewelry_shipping = $custom["jewelry_shipping"] [0];
?>
    <p><label>Price</label> 
	<input type="text" size="25" name="jewelry_price" value="<?php echo $jewelry_price; ?>" /></p>
    <p><label>Shipping</label> 
	<input type="text" size="25" name="jewelry_shipping" value="<?php echo $jewelry_shipping; ?>" /></p>
	<?php
}

/* Save Details */
add_action('save_post', 'save_details');


function save_details(){
  global $post;
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
	return $post_id;
  }

  if( defined('DOING_AJAX') && DOING_AJAX ) { //Prevents the metaboxes from being overwritten while quick editing.
	return $post_id;
  }

  if( ereg('/\edit\.php', $_SERVER['REQUEST_URI']) ) { //Detects if the save action is coming from a quick edit/batch edit.
	return $post_id;
  }
  // save all meta data

  update_post_meta($post->ID, "jewelry_price", $_POST["jewelry_price"]);
  update_post_meta($post->ID, "jewelry_shipping", $_POST["jewelry_shipping"]);  

}
// END - Custom Fields

//BEGIN - Custom Widget for Recent Jewelry Items
//Source: http://wordpress.stackexchange.com/questions/2405/including-custom-post-types-in-recent-posts-widget
class WP_Widget_Recent_Jewelry_Items extends WP_Widget {

    function WP_Widget_Recent_Jewelry_Items() {
        $widget_ops = array('classname' => 'widget_recent_jewelry_items', 'description' => __( "The most recent jewelry items on your site") );
        $this->WP_Widget('recent-jewelry-items', __('Recent Jewelry Items'), $widget_ops);
        $this->alt_option_name = 'widget_recent_jewelry_items';

        add_action( 'save_post', array(&$this, 'flush_widget_cache') );
        add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
        add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
    }

    function widget($args, $instance) {
        $cache = wp_cache_get('widget_recent_jewelry_items', 'widget');

        if ( !is_array($cache) )
            $cache = array();

        if ( isset($cache[$args['widget_id']]) ) {
            echo $cache[$args['widget_id']];
            return;
        }

        ob_start();
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Jewelry Items') : $instance['title'], $instance, $this->id_base);
        if ( !$number = (int) $instance['number'] )
            $number = 10;
        else if ( $number < 1 )
            $number = 1;
        else if ( $number > 15 )
            $number = 15;

        $r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => true, 'post_type' => 'jewelry'));
        if ($r->have_posts()) :
?>
        <?php echo $before_widget; ?>
        <?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <ul>
        <?php  while ($r->have_posts()) : $r->the_post(); ?>
        <li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
        <?php endwhile; ?>
        </ul>
        <?php echo $after_widget; ?>
<?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        endif;

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_recent_jewelry_items', $cache, 'widget');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_recent_jewelry_items']) )
            delete_option('widget_recent_jewelry_items');

        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_recent_jewelry_items', 'widget');
    }

    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
            $number = 5;
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of items to show:'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
    }
}

register_widget('WP_Widget_Recent_Jewelry_Items');
//END - Custom Widget for Recent Jewelry Items

// remove version info from head and feeds
function complete_version_removal() {
    return '';
}
add_filter('the_generator', 'complete_version_removal');

/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a Jewel.
 */
