<?php
/**
 * Htaccess class for Roots Plug
 * Holds rewrite functionality
 *
 * @since 1.0.0
 */
class RootsPlug_Htaccess {

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

		add_action( 'admin_init', array( $this, 'htaccess_writable' ) );
		add_action( 'generate_rewrite_rules', array( $this, 'add_h5bp_htaccess' ) );

	}

	public function htaccess_writable() {

		if ( !is_writable(get_home_path() . '.htaccess' ) ) {
			if ( current_user_can( 'administrator' ) ) {
				add_action( 'admin_notices', create_function( '', "echo '<div class=\"error\"><p>" . sprintf(__('Please make sure your <a href="%s">.htaccess</a> file is writable ', 'roots'), admin_url('options-permalink.php')) . "</p></div>';" ) );
			}
		}
	}

	public function add_h5bp_htaccess( $content ) {

		global $wp_rewrite;
		$home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
		$htaccess_file = $home_path . '.htaccess';
		$mod_rewrite_enabled = function_exists('got_mod_rewrite') ? got_mod_rewrite() : false;

		if ((!file_exists($htaccess_file) && is_writable($home_path) && $wp_rewrite->using_mod_rewrite_permalinks()) || is_writable($htaccess_file)) {
			if ($mod_rewrite_enabled) {
				$h5bp_rules = extract_from_markers($htaccess_file, 'HTML5 Boilerplate');
				if ($h5bp_rules === array()) {
					$filename = ROOTSPLUG_BASE_DIR . '/lib/h5bp-htaccess';
					return insert_with_markers($htaccess_file, 'HTML5 Boilerplate', extract_from_markers($filename, 'HTML5 Boilerplate'));
				}
			}
		}

		return $content;
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