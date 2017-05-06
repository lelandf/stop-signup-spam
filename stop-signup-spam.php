<?php
/*
Plugin Name: Stop Signup Spam
Description: Check email addresses from new user registrations against the Stop Forum Spam database
Version: 0.1
Author: Leland Fiegel
Author URI: https://leland.me/
License: GPLv2 or later
License URI: LICENSE
*/

// Core wp-register.php integration
function lelandf_stop_signup_spam_wp( $errors, $sanitized_user_login, $user_email ) {
	// Do not run if email is not set
	if ( $user_email ) {
		if ( lelandf_is_signup_spam( $user_email ) ) {
			$errors->add( 'likely_spammer', __( '<strong>ERROR</strong>: Cannot register with this email address. Please contact site administrator for assistance.', 'stop-signup-spam' ) );
		}
	}

	return $errors;
}
add_filter( 'registration_errors', 'lelandf_stop_signup_spam_wp', 10, 3 );

// Restrict Content Pro integration
function lelandf_stop_signup_spam_rcp( $user ) {
	// Do not run if email is not set
	if ( $user['email'] ) {
		if ( lelandf_is_signup_spam( $user['email'] ) ) {
			rcp_errors()->add( 'likely_spammer', __( 'Cannot register with this email address. Please contact site administrator for assistance.', 'stop-signup-spam' ), 'register' );
		}

		return $user;
	}
}
add_filter( 'rcp_user_registration_data', 'lelandf_stop_signup_spam_rcp' );

// Condititional function so we don't repeat ourselves in every integration
function lelandf_is_signup_spam( $email ) {
	// Stop Forum Spam API URL
	$url = 'http://api.stopforumspam.org/api';

	// Build data array (just email, for now)
	$data = array(
		'email' => $email,
	);

	// Complete API request URL in JSON format
	$url_with_data = $url . '?' . http_build_query( $data ) . '&json';

	// Get API response
	$response = wp_remote_get( $url_with_data );

	// Parse API response
	$response_array = json_decode( $response["body"], true );

	// If email appears to be spam, return true
	if ( 1 === $response_array["success"] && 1 === $response_array["email"]["appears"] ) {
		return true;
	} else {
		return false;
	}	
}
