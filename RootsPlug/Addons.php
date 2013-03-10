<?php
/**
 * Addons class for Roots Plug
 *
 * @since 1.0.0
 */
class RootsPlug_Addons {

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		self::$instance = $this;

		add_action( 'check_comment_flood', array( $this, 'verify_comment_referer' ) );
		add_filter( 'wp_nav_menu_items', array( $this, 'first_last_class_menu' ) );

		/**
		 * Allow widgets to parse shortcodes
		 *
		 * @since 1.0.0
		 */
		add_filter('widget_text', 'do_shortcode');

		add_theme_support('post-thumbnails');
		add_theme_support('menus');

	}

	/**
	 * Add first/last classes to menu items
	 * @param  array $items
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function first_last_class_menu( $items ) {

		$first = strpos( $items, 'class=' );

		if( false !== $first )
			$items = substr_replace( $items, 'first ', $first+7, 0 );

		$last = strripos( $items, 'class=');

		if( false !== $last )
			$items = substr_replace( $items, 'last ', $last+7, 0 );

		return $items;

	}

	/**
	 * Force browser referers to post comments
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function verify_comment_referer() {

		if (!wp_get_referer()) {
		    wp_die( __('You cannot post comment at this time, may be you need to enable referrers in your browser.','roots') );
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