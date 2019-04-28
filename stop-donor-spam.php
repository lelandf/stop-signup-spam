<?php
/*
Plugin Name: Stop Donor Spam
Description: Check user registration info against the Stop Forum Spam database before allowing registration
Version: 1.2.0
Author: Matt Cromwell
Author URI: https://www.mattcromwell.com
Text Domain: stop-donor-spam
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
			$errors->add( 'likely_spammer', __( '<strong>ERROR</strong>: Cannot register. Please contact site administrator for assistance.', 'stop-donor-spam' ) );
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
			rcp_errors()->add( 'likely_spammer', __( 'Cannot register. Please contact site administrator for assistance.', 'stop-donor-spam' ), 'register' );
		}

		return $user;
	}
}
add_filter( 'rcp_user_registration_data', 'lelandf_stop_signup_spam_rcp' );

/*
 * Adds integration with MemberPress
 * @url https://www.memberpress.com/
 */
function lelandf_stop_signup_spam_mepr( $errors ) {
	$email = is_email( $_POST['user_email'] ) ? $_POST['user_email'] : false;

	if ( $email !== false ) {
		$ip = lelandf_stop_signup_spam_get_ip();

		if ( lelandf_is_signup_spam( $email, $ip ) ) {
			$errors[] = __( 'Sorry, but something went wrong. Please contact us for further assistance.', 'stop-donor-spam' );
		}
	}

	return $errors;
}
add_filter( 'mepr-validate-signup', 'lelandf_stop_signup_spam_mepr' );

/**
 * Adds integration with Give Registration Shortcode
 * @url https://givewp.com/
 */
function lelandf_stop_signup_spam_give_register() {
	$email = is_email( $_POST['give_user_email'] ) ? $_POST['give_user_email'] : false;

	if ( $email !== false ) {
		$ip = lelandf_stop_signup_spam_get_ip();

		if ( lelandf_is_signup_spam( $email, $ip ) ) {
			give_set_error( 'likely_spammer', esc_html__( 'Cannot register. Please contact site administrator for assistance.', 'stop-donor-spam' ) );
		}
	}
}
add_action( 'give_pre_process_register_form', 'lelandf_stop_signup_spam_give_register' );


/**
 * Adds integration with Give Donation Forms
 * @url https://givewp.com/
 */
function lelandf_stop_signup_spam_give_donation($valid_data) {

	$user = give_get_donation_form_user( $valid_data );

	$email = is_email( $user['user_email'] ) ? $user['user_email'] : false;

	$ip = lelandf_stop_signup_spam_get_ip();

	if ( is_email($user['user_first']) ) {
		give_set_error( 'give-payment-spam-donor', esc_html__( 'Cannot donate. Please contact site administrator for assistance.', 'stop-donor-spam' ), $user['user_email'], $user['user_email'] );
	} elseif ( $email !== false ) {

		if ( lelandf_is_signup_spam( $email, $ip ) ) {
			give_set_error( 'give-payment-spam-donor', esc_html__( 'Cannot donate. Please contact site administrator for assistance.', 'stop-donor-spam' ), $user['user_email'], $user['user_email'] );
		}
	}

}
add_action( 'give_checkout_error_checks', 'lelandf_stop_signup_spam_give_donation' );


function sds_give_donations_save_custom_fields( $payment_id, $payment_data ) {

	$ip = lelandf_stop_signup_spam_get_ip();

	if ( isset( $ip ) ) {

		add_post_meta( $payment_id, 'give_donor_ip_address', $ip );
	}
}

add_action( 'give_insert_payment', 'sds_give_donations_save_custom_fields', 10, 2 );

function sds_give_donations_purchase_details( $payment_id ) {
	$ip = get_post_meta( $payment_id, 'give_donor_ip_address', true );
	if ( $ip ) : ?>

		<div id="give-ip-details" class="postbox">
			<h3 class="hndle"><?php esc_html_e( 'Donor IP Address:', 'give' ); ?></h3>
			<div class="inside" style="padding-bottom:10px;">
				<p><strong><a href="https://whatismyipaddress.com/ip/<?php echo $ip;?>" aria-label="<?php echo __('View Details about this IP address', 'stop-donor-spam'); ?>" target="_blank" rel="noopener" class="hint--top hint--bounce"><?php echo $ip; ?></a></strong></p>
				<p><em><?php echo __('Do you suspect this donation as spam? <a href="https://stopforumspam.com/add" target="_blank" rel="noopener">Click here</a> to report it to the Stop Signup Spam website to prevent this from happening again.', 'stop-donor-spam')?></em></p>
			</div>
		</div>

	<?php endif;
}
add_action( 'give_view_order_details_billing_before', 'sds_give_donations_purchase_details', 10, 1 );

/**
 * Conditional function to check for signup spam, so we don't have to repeat ourselves with every integration
 */
function lelandf_is_signup_spam( $email, $ip ) {
	// Stop Forum Spam API URL
	$url = 'https://api.stopforumspam.org/api';

	// Build data array (send email and IP address to Stop Forum Spam)
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
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return apply_filters( 'lelandf_stop_signup_spam_get_ip', $ip );
}
