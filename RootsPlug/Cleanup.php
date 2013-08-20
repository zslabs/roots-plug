<?php
/**
 * Cleanup class for Roots Plug
 *
 * @since 1.0.0
 */
class RootsPlug_Cleanup {

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Holds each action the relative URL filter will parse through
	 * @var array
	 */
	private $root_rel_filters = array(
		'bloginfo_url',
		'the_permalink',
		'wp_list_pages',
		'wp_list_categories',
		'wp_nav_menu',
		'the_content_more_link',
		'the_tags',
		'get_pagenum_link',
		'get_comment_link',
		'month_link',
		'day_link',
		'year_link',
		'tag_link',
		'the_author_posts_link',
		'script_loader_src',
		'style_loader_src'
	);

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		self::$instance = $this;

		add_action( 'style_loader_tag', array( $this, 'clean_style_tag' ) );
		add_filter( 'language_attributes', array( $this, 'language_attributes' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );
		add_filter( 'embed_oembed_html', array( $this, 'embed_wrap' ), 10, 4);
		add_filter( 'embed_googlevideo', array( $this, 'embed_wrap' ), 10, 2);
		add_filter( 'admin_init', array( $this, 'remove_dashboard_widgets' ) );
		add_filter( 'get_avatar', array( $this, 'remove_self_closing_tags' ) );
		add_filter( 'comment_id_fields', array( $this, 'remove_self_closing_tags' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'remove_self_closing_tags' ) );
		add_filter( 'get_bloginfo_rss', array( $this, 'remove_default_rss_description' ) );
		add_filter( 'dynamic_sidebar_params', array( $this, 'widget_first_last_classes' ) );
		add_filter( 'template_redirect', array( $this, 'search_redirect' ) );
		add_filter( 'request', array( $this, 'request_filter' ) );
		add_filter( 'img_caption_shortcode', array( $this, 'image_caption_cleanup' ), 10, 3 );

		if ( !( is_admin() || in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ) ) {

			$this->add_filters( $this->root_rel_filters, array( $this, 'root_relative_url' ) );
		}

		/**
		 * Clean up wp_head()
		 *
		 * Remove unnecessary <link>'s
		 * Remove inline CSS used by Recent Comments widget
		 * Remove inline CSS used by posts with galleries
		 * Remove self-closing tag and change ''s to "'s on rel_canonical()
		 * Originally from http://wpengineer.com/1438/wordpress-header/
		 */
		//remove_action( 'wp_head', 'feed_links', 2);
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

		global $wp_widget_factory;
		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );

		add_filter( 'use_default_gallery_style', '__return_null' );

		/**
		 * Remove the WordPress version from RSS feeds
		 */
		add_filter( 'the_generator', '__return_false' );


	}

	/**
	 * Clean up output of stylesheet <link> tags
	 * @param  string $input
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function clean_style_tag( $input ) {

		preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
		// Only display media if it is meaningful
		$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
		return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";

	}

	/**
	 * Clean up language_attributes() used in <html> tag
	 *
	 * Change lang="en-US" to lang="en"
	 * Remove dir="ltr"
	 *
	 * @since 1.0.0
	 */
	public function language_attributes() {

		$attributes = array();
		$output = '';

		if (function_exists( 'is_rtl' ) ) {
			if (is_rtl() == 'rtl' ) {
				$attributes[] = 'dir="rtl"';
			}
		}

		$lang = get_bloginfo( 'language' );

		if ($lang && $lang !== 'en-US' ) {
			$attributes[] = "lang=\"$lang\"";
		} else {
			$attributes[] = 'lang="en"';
		}

		$output = implode( ' ', $attributes);
		$output = apply_filters( __METHOD__, $output);

		return $output;
	}

	/**
	 * Root relative URLs
	 *
	 * WordPress likes to use absolute URLs on everything - let's clean that up.
	 * Inspired by http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
	 *
	 * You can enable/disable this feature in config.php:
	 * current_theme_supports( 'root-relative-urls' );
	 *
	 * @author Scott Walkinshaw <scott.walkinshaw@gmail.com>
	 *
	 * @since 1.0.0.0
	 */
	public function root_relative_url( $input ) {

		preg_match( '|https?://([^/]+)(/.*)|i', $input, $matches );

		if ( isset( $matches[1] ) && isset($matches[2] ) && $matches[1] === $_SERVER['SERVER_NAME'] ) {
			return wp_make_link_relative( $input );
		} else {
			return $input;
		}
	}

	/**
	 * Add and remove body_class() classes
	 * @param  array $classes
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function body_class( $classes ) {

		// Add post/page slug
		if ( is_single() || is_page() && !is_front_page() ) {
			$classes[] = basename(get_permalink() );
		}

		// Remove unnecessary classes
		$home_id_class = 'page-id-' . get_option( 'page_on_front' );
		$remove_classes = array(
			'page-template-default',
			$home_id_class
		);
		$classes = array_diff( $classes, $remove_classes );

		return $classes;
	}

	/**
	 * Remove unecessary dashboard widgets
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function remove_dashboard_widgets() {

		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );

	}

	/**
	 * Remove unecessary self-closing tags
	 * @param  string $input
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function remove_self_closing_tags( $input ) {

		return str_replace( ' />', '>', $input);

	}

	/**
	 * Wrap embedded media as suggested by Readability
	 *
	 * @link https://gist.github.com/965956
	 * @link http://www.readability.com/publishers/guidelines#publisher
	 *
	 * @since 1.0.0
	 */
	public function embed_wrap( $cache, $url, $attr = '', $post_ID = '' ) {

		return '<div class="entry-content-asset">' . $cache . '</div>';
	}

	/**
	 * Don't return the default description in the RSS feed if it hasn't been changed
	 * @param  string $bloginfo
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function remove_default_rss_description( $bloginfo ) {

		$default_tagline = 'Just another WordPress site';
		return ( $bloginfo === $default_tagline ) ? '' : $bloginfo;

	}

	/**
	 * Adds additional useful classes to widgets
	 * @param  array $params
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function widget_first_last_classes( $params ) {

		global $my_widget_num;

		$this_id = $params[0]['id'];
		$arr_registered_widgets = wp_get_sidebars_widgets();

		if (!$my_widget_num) {
			$my_widget_num = array();
		}

		if (!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id]) ) {
			return $params;
		}

		if (isset($my_widget_num[$this_id]) ) {
			$my_widget_num[$this_id] ++;
		} else {
			$my_widget_num[$this_id] = 1;
		}

		$class = 'class="widget-' . $my_widget_num[$this_id] . ' ';

		if ($my_widget_num[$this_id] == 1) {
			$class .= 'widget-first ';
		} elseif ($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id]) ) {
			$class .= 'widget-last ';
		}

		$params[0]['before_widget'] = preg_replace( '/class=\"/', "$class", $params[0]['before_widget'], 1);

		return $params;

	}

	/**
	 * Redirects search results from /?s=query to /search/query/, converts %20 to +
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function search_redirect() {

		global $wp_rewrite;
		if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks() ) {
			return;
		}

		$search_base = $wp_rewrite->search_base;
		if ( is_search() && !is_admin() && strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) === false ) {
			wp_redirect( home_url( "/{$search_base}/" . urlencode( get_query_var( 's' ) ) ) );
			exit();
		}

	}

	/**
	 * Fix for empty search queries redirecting to home page
	 *
	 * @link http://wordpress.org/support/topic/blank-search-sends-you-to-the-homepage#post-1772565
	 * @link http://core.trac.wordpress.org/ticket/11330
	 *
	 * @since  1.2.3
	 */
	public function request_filter( $query_vars ) {

		if (isset($_GET['s']) && empty($_GET['s'])) {
			$query_vars['s'] = ' ';
		}

		return $query_vars;
	}

	/**
	 * Cleanup image caption shortocde to not include width in output
	 * @return void
	 *
	 * @since  1.2.2
	 */
	public function image_caption_cleanup( $output, $attr, $content ) {
		if (is_feed()) {
			return $output;
		}

		$defaults = array(
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => ''
		);

		$attr = shortcode_atts($defaults, $attr);

		// If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
		if (1 > $attr['width'] || empty($attr['caption'])) {
			return $content;
		}

		// Set up the attributes for the caption <figure>
		$attributes  = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '' );
		$attributes .= ' class="thumbnail wp-caption ' . esc_attr($attr['align']) . '"';

		$output  = '<figure' . $attributes .'>';
		$output .= do_shortcode($content);
		$output .= '<figcaption class="caption wp-caption-text">' . $attr['caption'] . '</figcaption>';
		$output .= '</figure>';

		return $output;
	}

	/**
	 * Helper method to parse multiple filters from array
	 * @param array $tags
	 * @param string $function
	 *
	 * @since 1.0.0
	 */
	private function add_filters( $tags, $function ) {

		foreach( $tags as $tag ) {
			add_filter( $tag, $function );
		}
	}

	/**
	 * Getter method for retrieving the object instance.
	 *
	 * @since 1.0.0
	 */
	public static function get_instance() {

		return self::$instance;

	}

}