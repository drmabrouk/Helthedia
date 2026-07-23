<?php
class Healthedia_Auth_Endpoints {
	public function register_routes() {
		register_rest_route('healthedia/v1', '/auth/request-otp', array(
			'methods' => 'POST',
			'callback' => array($this, 'request_otp'),
			'permission_callback' => '__return_true'
		));
		register_rest_route('healthedia/v1', '/auth/verify-otp', array(
			'methods' => 'POST',
			'callback' => array($this, 'verify_otp'),
			'permission_callback' => '__return_true'
		));
	}

	public function request_otp($request) {
		$email = sanitize_email($request->get_param('email'));
		if (!is_email($email)) {
			return new WP_Error('invalid_email', 'Invalid email address', array('status' => 400));
		}

		$ip = $_SERVER['REMOTE_ADDR'];
		$rate_limit_key = 'healthedia_otp_rate_limit_' . md5($ip);
		$attempts = get_transient($rate_limit_key) ?: 0;
		if ($attempts > 5) {
			return new WP_Error('rate_limit', 'Too many requests. Please try again later.', array('status' => 429));
		}
		set_transient($rate_limit_key, $attempts + 1, 15 * MINUTE_IN_SECONDS);

		$is_register = filter_var($request->get_param('is_register'), FILTER_VALIDATE_BOOLEAN);

		if ($is_register) {
			$registration_enabled = get_option('healthedia_enable_registration', 'yes');
			if ($registration_enabled !== 'yes') {
				return new WP_Error('registration_disabled', 'New user registration is currently disabled.', array('status' => 403));
			}
			if (email_exists($email)) {
				return new WP_Error('email_exists', 'An account with this email already exists. Please log in.', array('status' => 400));
			}
			// Store temporary registration data
			$temp_data = array(
				'name' => sanitize_text_field($request->get_param('name')),
				'specialty' => sanitize_text_field($request->get_param('specialty')),
				'institution' => sanitize_text_field($request->get_param('institution')),
				'country' => sanitize_text_field($request->get_param('country')),
				'orcid' => sanitize_text_field($request->get_param('orcid'))
			);
			set_transient('healthedia_reg_' . md5($email), $temp_data, 15 * MINUTE_IN_SECONDS);
		} else {
			if (!email_exists($email)) {
				return new WP_Error('email_not_found', 'No account found with this email.', array('status' => 400));
			}
		}

		$otp = Healthedia_Auth_OTP::generate($email);
		Healthedia_Auth_Mailer::send_otp($email, $otp, $is_register ? 'register' : 'login');

		return rest_ensure_response(array('success' => true, 'message' => 'OTP sent to email.'));
	}

	public function verify_otp($request) {
		$email = sanitize_email($request->get_param('email'));
		$otp = sanitize_text_field($request->get_param('otp'));
		$is_register = filter_var($request->get_param('is_register'), FILTER_VALIDATE_BOOLEAN);

		$ip = $_SERVER['REMOTE_ADDR'];
		$verify_limit_key = 'healthedia_otp_verify_limit_' . md5($email . '_' . $ip);
		$verify_attempts = get_transient($verify_limit_key) ?: 0;
		if ($verify_attempts > 5) {
			return new WP_Error('rate_limit', 'Too many failed attempts. Please request a new OTP.', array('status' => 429));
		}

		if (!Healthedia_Auth_OTP::verify($email, $otp)) {
			set_transient($verify_limit_key, $verify_attempts + 1, 15 * MINUTE_IN_SECONDS);
			return new WP_Error('invalid_otp', 'Invalid or expired OTP', array('status' => 401));
		}
		delete_transient($verify_limit_key);

		$user = get_user_by('email', $email);

		if ($is_register) {
			if ($user) {
				return new WP_Error('email_exists', 'Account already exists.', array('status' => 400));
			}
			$temp_data = get_transient('healthedia_reg_' . md5($email));
			if (!$temp_data) {
				return new WP_Error('session_expired', 'Registration session expired. Please start over.', array('status' => 400));
			}

			$user_id = wp_create_user($email, wp_generate_password(), $email);
			if (is_wp_error($user_id)) {
				return new WP_Error('creation_failed', 'Failed to create account.', array('status' => 500));
			}
			$user = get_user_by('id', $user_id);
			$user->set_role('subscriber');

			// Save custom meta
			wp_update_user(array('ID' => $user_id, 'display_name' => $temp_data['name']));
			update_user_meta($user_id, '_healthedia_specialty', $temp_data['specialty']);
			update_user_meta($user_id, '_healthedia_institution', $temp_data['institution']);
			update_user_meta($user_id, '_healthedia_country', $temp_data['country']);
			update_user_meta($user_id, '_healthedia_orcid', $temp_data['orcid']);

			// Auto verify email based on OTP success
			update_user_meta($user_id, '_healthedia_email_verified', '1');

			delete_transient('healthedia_reg_' . md5($email));
		} else {
			if (!$user) {
				return new WP_Error('user_not_found', 'User not found.', array('status' => 400));
			}
		}

		wp_set_current_user($user->ID);
		wp_set_auth_cookie($user->ID);

		return rest_ensure_response(array('success' => true, 'message' => 'Authenticated successfully.'));
	}
}
