<?php
/*
Plugin Name: Roots Plug
Plugin URI: http://zslabs.com
Description: Base plugin
Version: 1.2.4
Author: Zach Schnackel
Author URI: http://zslabs.com
License: GNU General Public License v2.0 or later
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

/*
	Copyright 2013	 Zach Schnackel	 (email : info@zslabs.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Load all of the necessary class files for the plugin
spl_autoload_register( 'RootsPlug::autoload' );

/**
 * Constants
 */

// Folder URL
if(!defined('ROOTSPLUG_BASE_URL')) {
	define('ROOTSPLUG_BASE_URL', plugin_dir_url(__FILE__));
}
// Folder Path
if(!defined('ROOTSPLUG_BASE_DIR')) {
	define('ROOTSPLUG_BASE_DIR', plugin_dir_path( __FILE__ ));
}
// Root file
if(!defined('ROOTSPLUG_PLUGIN_FILE')) {
	define('ROOTSPLUG_PLUGIN_FILE', __FILE__);
}

/**
 * Init class for Roots Plug
 *
 * Loads all of the necessary components for the Roots Plug plugin.
 *
 * @since 1.0.0
 */
class RootsPlug {

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Current version of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = '1.2.4';

	/**
	 * Holds a copy of the main plugin filepath.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private static $file = __FILE__;

	/**
	 * Constructor. Hooks all interactions into correct areas to start
	 * the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		self::$instance = $this;

		// Run activation hook and make sure the WordPress version supports the plugin
		register_activation_hook( __FILE__, array( $this, 'activation' ) );

		add_action( 'admin_init', array( $this, 'perform_rewrite_check' ) );
		add_action( 'generate_rewrite_rules', array( $this, 'remove_rewrite_option' ) );

		// Load the plugin
		add_action( 'init', array( $this, 'init' ) );

	}

	/**
 	 * Registers a plugin activation hook to make sure the current WordPress
 	 * version is suitable (>= 3.5.1) for use.
 	 *
 	 * @since 1.0.0
 	 *
 	 * @global int $wp_version The current version of this particular WP instance
 	 */
	public function activation() {

		global $wp_version;

		if ( version_compare( $wp_version, '3.5', '<' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( printf( __( 'Sorry, but your version of WordPress, <strong>%s</strong>, does not meet Roots Plug\'s required version of <strong>3.5</strong> to run properly. The plugin has been deactivated. <a href="%s">Click here to return to the Dashboard</a>', 'rootsplug' ), $wp_version, admin_url() ) );
		}

		add_option( 'roots_plug_try_rewrites', true );

	}

	/**
	 * Perform check to see if we should output notice to re-save permalinks
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function perform_rewrite_check() {

		if ( (get_option( 'roots_plug_try_rewrites' ) && is_writable(get_home_path() . '.htaccess' ) ) ) {

			if ( current_user_can( 'administrator' ) ) {
				add_action( 'admin_notices', create_function( '', "echo '<div class=\"error\"><p>" . sprintf(__('Please <a href="%s">resave your permalinks</a> to enable Roots Plug .htacess additions ', 'roots'), admin_url('options-permalink.php')) . "</p></div>';" ) );
			}
		}

	}

	/**
	 * Remove option that reminds admins to resave their permalink structure
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function remove_rewrite_option() {

		delete_option( 'roots_plug_try_rewrites' );
	}

	/**
	 * Loads all the actions and filters for the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Only run certain processes in the admin.
		if ( is_admin() ) :

			/** Instantiate all the necessary admin components of the plugin */

		endif;

		// Load these components regardless.
		$rootsplug_cleanup  = new RootsPlug_Cleanup;
		$rootsplug_addons   = new RootsPlug_Addons;
		$rootsplug_htaccess = new RootsPlug_Htaccess;

	}

	/**
	 * PSR-0 compliant autoloader to load classes as needed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $classname The name of the class
	 * @return null Return early if the class name does not start with the correct prefix
	 */
	public static function autoload( $classname ) {

		if ( 'RootsPlug' !== mb_substr( $classname, 0, 9 ) )
			return;

		$filename = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . str_replace( '_', DIRECTORY_SEPARATOR, $classname ) . '.php';
		if ( file_exists( $filename ) )
			require $filename;

	}

	/**
	 * Getter method for retrieving the main plugin filepath.
	 *
	 * @since 1.0.0
	 */
	public static function get_file() {

		return self::$file;

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

// Initiate the init class
new RootsPlug;