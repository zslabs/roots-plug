<?php

/*-----------------------------------------------------------------------------------*/
/*	THIS IS A PLACEHOLDER PAGE FOR REGISTEERING SETTINGS THAT MAY BE ADDED IN THE FUTURE
/*-----------------------------------------------------------------------------------*/

function rplug_register_settings_initial() {

	if( false == get_option( 'rplug_settings' ) ) {  
		add_option( 'rplug_settings' );  
	}
}
add_action('admin_init', 'rplug_register_settings_initial');