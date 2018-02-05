<?php
/**
 * Plugin Name: Tahosa Lodge Registration
 * Plugin URI:  https://mckernan.in
 * Description: Tahosa Lodge registration functionality.
 * Version:     1.0.0
 * Author:      Kevin McKernan
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( 'classes/class-tahosa-event-registration.php' );

/**
 * Helper function to get/return the W2S_Registration object
 * @since  0.1.0
 * @return W2S_Registration object
 */
function w2s_event_registration() {
	return W2S_Event_Registration::get_instance();
}

// Get it started
w2s_event_registration();
