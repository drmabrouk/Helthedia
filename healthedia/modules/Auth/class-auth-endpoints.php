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

		$otp = Healthedia_Auth_OTP::generate($email);
		Healthedia_Auth_Mailer::send_otp($email, $otp);

		return rest_ensure_response(array('success' => true, 'message' => 'OTP sent to email.'));
	}

	public function verify_otp($request) {
		$email = sanitize_email($request->get_param('email'));
		$otp = sanitize_text_field($request->get_param('otp'));

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
		if (!$user) {
			$user_id = wp_create_user($email, wp_generate_password(), $email);
			$user = get_user_by('id', $user_id);
			$user->set_role('subscriber'); // default role
		}

		wp_set_current_user($user->ID);
		wp_set_auth_cookie($user->ID);

		return rest_ensure_response(array('success' => true, 'message' => 'Authenticated successfully.'));
	}
}
