<?php
/*
	Plugin Name: tagDiv Composer
	Plugin URI: http://tagdiv.com
	Description: tagDiv Composer - Create beautiful pages with this custom frontend drag and drop builder.
	Author: tagDiv
	Version: 2.1
	Author URI: http://tagdiv.com
*/


require_once 'td_deploy_mode.php';
require_once 'includes/tdc_version_check.php';





add_action('td_wp_booster_loaded', 'tdc_plugin_init');
function tdc_plugin_init() {

    if (tdc_version_check::is_theme_compatible() === false) {
        return;
    }



	// Hook - used by other plugins to know the composer is on
	do_action( 'tdc_init' );

	// load the plugin config
	require_once('includes/tdc_config.php');

	// load the plugin
	require_once "includes/tdc_main.php";

	// register 'css-live' extension
	require_once "css-live/css-live.php";

	// Hook - used by other plugins to know the composer is loaded
    // here we can map aditional shortcodes
	do_action( 'tdc_loaded' );
}




