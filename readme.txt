=== Roots Plug ===
Contributors: zslabs
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EEMPDX7SN4RFW
Tags: roots, cleanup, htaccess, relative urls
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 1.2.4
License: GPLv2

Catch-all awesomeness for a leaner, meaner WordPress site.

== Description ==

Roots Plug is the catch-all awesome WordPress plugin that cleans up default output and provides all those handy-dandy functions that we all search for around the web - in one convenient package! It also appends on [HTML5 Boilerplate's](http://html5boilerplate.com/) `.htaccess` rewrites automagically.

Inspired by [Roots Theme](http://rootstheme.com) by Ben Word -- extracted much of the cleanup efforts into a handy-dandy plugin.

== Installation ==

1. Upload the `roots-plug` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Can you add `feature-x`? =

Sure! I'm always open to knew ideas. Just create a new [issue](https://github.com/zslabs/roots-plug/issues) and I'll take a gander.

== Changelog ==

= 1.2.4 =
* Updated readme

= 1.2.3 =
* Fix for empty search queries redirecting to home page
* Tested in WP 3.6

= 1.2.2 =
* Updated relative URL function (for external URLs)
* Image caption shortcode cleanup (removed inline width attribute)

= 1.2.1 =
* Readme typo fix

= 1.2.0 =
* Updated `.htaccess` file from [Roots repo](https://github.com/retlehs/wp-h5bp-htaccess)
* Updated [relative URL cleanup](https://github.com/retlehs/roots/commit/a8c543753ae4f2e9b39a9ece4f023d54f95c6588)

= 1.1.0 =
* Source credits given
* 'Options' link added (for easy access to `options.php`) in Settings Panel

= 1.0.1 =
* Quick-fix for push error

= 1.0.0 =
* Complete rewrite (removed asset rewrites, but more compatible across different setups)

= 0.7 =
* Cleanup and htaccess updates

= 0.6 =
* .htaccess updates
* We're on Github now! https://github.com/zslabs/roots-plug/

= 0.5 =
* Removed Roots Nav Walker (do this on the theme level instead)

= 0.4 =
* Roots cleanup updates

= 0.3 =
* HTML5 Boilerplate .htaccess updates

= 0.2 =
* HTML5 Boilerplate .htaccess updates

= 0.1 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Roots Plug no longer forces you to keep your assets in a specific directory in your theme (so update accordingly if need-be). You may need to re-save your permalinks to add-in the updated `.htaccess` additions.