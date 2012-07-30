<?php

/*
Plugin Name: Roots Plug
Plugin URI: http://zslabs.com
Description: Roots Plug is the catch-all awesome WordPress plugin that cleans up default output, uses relative URLs and provides all those handy-dandy functions that we all search for around the web - in one convenient package! Largely based and inspired by the Roots Theme by Ben Word.
Author: Zach Schnackel
Author URI: http://zslabs.com
Version: 0.6
*/

/*-----------------------------------------------------------------------------------*/
/*	PLUGIN CONSTANTS
/*-----------------------------------------------------------------------------------*/

if (!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); }

// plugin folder url
if(!defined('RPLUG_BASE_URL')) {
	define('RPLUG_BASE_URL', plugin_dir_url(__FILE__));
}
// plugin folder path
if(!defined('RPLUG_PLUGIN_DIR')) {
	define('RPLUG_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
}
// plugin root file
if(!defined('RPLUG_PLUGIN_FILE')) {
	define('RPLUG_PLUGIN_FILE', __FILE__);
}
/*-----------------------------------------------------------------------------------*/
/*	INCLUDES
/*-----------------------------------------------------------------------------------*/

/* include_once(RPLUG_PLUGIN_DIR . 'includes/register-settings.php'); // Register
$rplug_options = get_option('rplug_settings');*/
include_once(RPLUG_PLUGIN_DIR . 'includes/install.php'); // Sets default plugin settings on activation

include_once(RPLUG_PLUGIN_DIR . 'includes/util.php');       // utility functions

/*-----------------------------------------------------------------------------------*/
/*	ROOTS STUFF
/*-----------------------------------------------------------------------------------*/

define('WP_BASE', wp_base_dir());
define('THEME_NAME', next(explode('/themes/', get_template_directory())));
define('RELATIVE_PLUGIN_PATH', str_replace(site_url() . '/', '', plugins_url()));
define('FULL_RELATIVE_PLUGIN_PATH', WP_BASE . '/' . RELATIVE_PLUGIN_PATH);
define('RELATIVE_CONTENT_PATH', str_replace(site_url() . '/', '', content_url()));
define('THEME_PATH', RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);

include_once(RPLUG_PLUGIN_DIR . 'includes/cleanup.php'); 			// cleanup
include_once(RPLUG_PLUGIN_DIR . 'includes/htaccess.php'); 			 // rewrites for assets, h5bp htaccess

/*-----------------------------------------------------------------------------------*/
/*	INCLUDES (CONT) BACK TO BUSINESS
/*-----------------------------------------------------------------------------------*/

include_once(RPLUG_PLUGIN_DIR . 'includes/addons.php'); // additions
/*include_once(RPLUG_PLUGIN_DIR . 'includes/plugin-settings.php'); // Plugin options page HTML/Save functions*/