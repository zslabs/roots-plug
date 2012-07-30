<?php

// Remove WordPress admin bar from the front-end of the website
add_filter('show_admin_bar', '__return_false');

// Add support for menus
add_theme_support('menus');

// Add post thumbnail support
add_theme_support('post-thumbnails');

// Add theme support for automatic feed links (removed in head anyways... put here to take out Theme Check Warning)
add_theme_support( 'automatic-feed-links' );

// Check for existance of posts, if > 0, output feedlink
function roots_feed_link() {
  $count = wp_count_posts('post'); if ($count->publish > 0) {
    echo "\n\t<link rel=\"alternate\" type=\"application/rss+xml\" title=\"". get_bloginfo('name') ." Feed\" href=\"". home_url() ."/feed/\">\n";
  }
}
add_action('wp_head', 'roots_feed_link',7);

// Allow shortcodes in widgets
add_filter('widget_text', 'do_shortcode');

// Add first/last class to menu items
add_filter( 'wp_nav_menu_items', 'of_first_last_class' );
function of_first_last_class( $items ) {
	$first = strpos( $items, 'class=' );
	if( false !== $first )
			 $items = substr_replace( $items, 'first ', $first+7, 0 );
	$last = strripos( $items, 'class=');
	if( false !== $last )
			 $items = substr_replace( $items, 'last ', $last+7, 0 );
	return $items;
}

// Display post thumbnail in your RSS feed
function of_post_thumbnail_feeds($content) {
	global $post;
	if(has_post_thumbnail($post->ID)) {
		$content = '<div>' . get_the_post_thumbnail($post->ID) . '</div>' . $content;
	}
	return $content;
}
add_filter('the_excerpt_rss', 'of_post_thumbnail_feeds');
add_filter('the_content_feed', 'of_post_thumbnail_feeds');

/* Add Favicon
function of_favicon() {
	if (file_exists(''.get_bloginfo('stylesheet_directory').'/img/favicon.png')) { ?>
		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon.png" />
	<?php }
}
add_action('wp_head', 'of_favicon');*/

// Force browser referers to post comments
function verify_comment_referer() {
	if (!wp_get_referer()) {
	    wp_die( __('You cannot post comment at this time, may be you need to enable referrers in your browser.','roots') );
	}
}
add_action('check_comment_flood', 'verify_comment_referer');

// Add delete/spam links on front-end comments
function of_delete_spam_comment_link($id) {
	if (current_user_can('edit_post')) {
		echo '| <a href="'.get_bloginfo('wpurl').'/wp-admin/comment.php?action=cdc&c='.$id.'">Delete</a> ';
		echo '| <a href="'.get_bloginfo('wpurl').'/wp-admin/comment.php?action=cdc&dt=spam&c='.$id.'">Mark as Spam</a>';
	}
}
function spam_delete_comment_link($id) {
	global $comment, $post;

	if ( $post->post_type == 'page' ) {
		if ( !current_user_can( 'edit_page', $post->ID ) )
			return;
	} else {
		if ( !current_user_can( 'edit_post', $post->ID ) )
			return;
	}

	$id = $comment->comment_ID;

	// Commented out - is this even needed?  if ( null === $link )
	$link = __('Edit','roots');

	$link = '<a class="comment-edit-link" href="' . get_edit_comment_link( $comment->comment_ID ) . '" title="' . __( 'Edit comment','roots' ) . '">' . $link . '</a>';
	$link = $link . ' | <a href="'.admin_url("comment.php?action=cdc&c=$id").'">Delete</a> ';
	$link = $link . ' | <a href="'.admin_url("comment.php?action=cdc&dt=spam&c=$id").'">Mark as Spam</a>';
	// Commented out - undefined variables - is this even needed? $link = $before . $link . $after;

	return $link;
}

add_filter('edit_comment_link', 'spam_delete_comment_link');