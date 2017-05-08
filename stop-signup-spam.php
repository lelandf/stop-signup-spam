<?php
/*
Plugin Name: Stop Signup Spam
Description: Check user registration info against the Stop Forum Spam database before allowing registration
Version: 1.0.0
Author: Leland Fiegel
Author URI: https://leland.me/
Text Domain: stop-signup-spam
License: GPLv2 or later
License URI: LICENSE
*/

/**
 * Adds integration to any core WordPress registration form, like the one at /wp-login.php?action=register
 */
function lelandf_stop_signup_spam_wp( $errors, $sanitized_user_login, $user_email ) {
	// Do not run if email is not set
	if ( $user_email ) {
		$ip = lelandf_stop_signup_spam_get_ip();

		// Add error if conditional returns true
		if ( lelandf_is_signup_spam( $user_email, $ip ) ) {
			$errors->add( 'likely_spammer', __( '<strong>ERROR</strong>: Cannot register. Please contact site administrator for assistance.', 'stop-signup-spam' ) );
		}
	}

	return $errors;
}
add_filter( 'registration_errors', 'lelandf_stop_signup_spam_wp', 10, 3 );

/**
 * Adds integration with Restrict Content Pro
 * @url https://restrictcontentpro.com/
 */
function lelandf_stop_signup_spam_rcp( $user ) {
	// Do not run if email is not set
	if ( $user['email'] ) {
		$ip = lelandf_stop_signup_spam_get_ip();

		// Add error if conditional returns true
		if ( lelandf_is_signup_spam( $user['email'], $ip ) ) {
			rcp_errors()->add( 'likely_spammer', __( 'Cannot register. Please contact site administrator for assistance.', 'stop-signup-spam' ), 'register' );
		}

		return $user;
	}
}
add_filter( 'rcp_user_registration_data', 'lelandf_stop_signup_spam_rcp' );

/**
 * Conditional function to check for signup spam, so we don't have to repeat ourselves with every integration
 */
function lelandf_is_signup_spam( $email, $ip ) {
	// Stop Forum Spam API URL
	$url = 'http://api.stopforumspam.org/api';

	// Build data array (send email and IP address to Stop Forum Spem)
	$data = array(
		'email' => $email,
		'ip'    => $ip,
	);

	// Complete API request URL in JSON format
	$url_with_data = $url . '?' . http_build_query( $data ) . '&json';

	// Get API response
	$response = wp_remote_get( $url_with_data );

	// Parse API response
	$response_array = json_decode( $response["body"], true );

	// If email or IP appear to be spam, return true
	if ( 1 === $response_array["success"] && ( 1 === $response_array["email"]["appears"] || 1 === $response_array["ip"]["appears"] ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Function to get user IP address
 * @props https://gist.github.com/pippinsplugins/9641841
 */
function lelandf_stop_signup_spam_get_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return apply_filters( 'lelandf_stop_signup_spam_get_ip', $ip );
}
