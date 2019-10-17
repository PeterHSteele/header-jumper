<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       github.com/peterhsteele
 * @since      1.0.0
 *
 * @package    Heading_Jumper
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
if ( current_user_can( 'activate_plugins' ) ){
	delete_option( 'heading_jumper_settings' );
}