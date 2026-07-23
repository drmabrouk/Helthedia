<?php
class Healthedia_Auth_Mailer {
	public static function send_otp($email, $otp) {
		$subject = 'Your Healthedia Login OTP';
		$message = "Your one-time password to access Healthedia is: $otp\n\nThis code will expire in 15 minutes.";
		wp_mail($email, $subject, $message);
	}
}
