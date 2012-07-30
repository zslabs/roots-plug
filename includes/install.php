<?php

function rplug_activate() {
	global $wpdb, $wp_rewrite, $rplug_options;
	/*if( false == get_option( 'rplug_settings' ) ) {
		$rplug_options = array(
			'example_height' => '200',
		);
		update_option( 'rplug_settings', $rplug_options );
	}*/

	// set default and clear permalinks
	if( true == get_option( 'permalink_structure' ) ) { 
		wpse_50359_set_plugin_basics();
	}
	else {
		$wp_rewrite->set_permalink_structure( '/%monthnum%/%postname%/' );
	}
	$wp_rewrite->flush_rules();
}
register_activation_hook( RPLUG_PLUGIN_FILE, 'rplug_activate' );