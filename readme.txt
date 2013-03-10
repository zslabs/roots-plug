=== Roots Plug ===
Contributors: zslabs
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EEMPDX7SN4RFW
Tags: roots, cleanup, htaccess, relative urls
Requires at least: 3.5
Tested up to: 3.5
Stable tag: 1.0.0
License: GPLv2

Catch-all awesomeness for a leaner, meaner WordPress site.

== Description ==

Roots Plug is the catch-all awesome WordPress plugin that cleans up default output and provides all those handy-dandy functions that we all search for around the web - in one convenient package! It also appends on [HTML5 Boilerplate's](http://html5boilerplate.com/) `.htaccess` rewrites automagically.

**Roots Plug was recently rewritten from scratch. While most of the base functionality remained intact, gone are the asset rewrites. Why? Because they forced you to change how your template files were stored and broke several other plugins - which isn't cool. Upcoming releases will continue to make this more compatible across different WordPress environments. 3.5 is a requirement now.**

== Installation ==

1. Upload the `roots-plug` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Can you add `feature-x`? =

Sure! I'm always open to knew ideas. Just create a new [issue](https://github.com/zslabs/roots-plug/issues) and I'll take a gander.

== Changelog ==

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