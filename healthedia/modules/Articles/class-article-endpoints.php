<?php
class Healthedia_Article_Endpoints {
	public function register_routes() {
		register_rest_route('healthedia/v1', '/manuscript/submit', array(
			'methods' => 'POST',
			'callback' => array($this, 'handle_submission'),
			'permission_callback' => function() { return is_user_logged_in(); }
		));
	}

	public function handle_submission($request) {
		$title = sanitize_text_field($request->get_param('title'));
		$abstract = sanitize_textarea_field($request->get_param('abstract'));
		$specialty = sanitize_text_field($request->get_param('specialty'));
		$nct = sanitize_text_field($request->get_param('nct'));

		if (empty($title) || empty($abstract)) {
			return new WP_Error('missing_fields', 'Title and Abstract are required.', array('status' => 400));
		}

		$files = $request->get_file_params();
		if (empty($files['manuscript'])) {
			return new WP_Error('missing_file', 'Please upload a manuscript file.', array('status' => 400));
		}

		$file = $files['manuscript'];

		require_once(ABSPATH . 'wp-admin/includes/file.php');
		$upload_overrides = array(
			'test_form' => false,
			'mimes' => array(
				'pdf' => 'application/pdf',
				'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
			)
		);
		$upload = wp_handle_upload($file, $upload_overrides);

		if (isset($upload['error'])) {
			return new WP_Error('upload_error', $upload['error'], array('status' => 500));
		}

		$post_id = wp_insert_post(array(
			'post_title' => $title,
			'post_content' => $abstract,
			'post_type' => 'healthedia_article',
			'post_status' => 'pending', // Awaiting editorial review
			'post_author' => get_current_user_id()
		));

		if (is_wp_error($post_id)) {
			return new WP_Error('db_error', 'Failed to save manuscript.', array('status' => 500));
		}

		update_post_meta($post_id, '_healthedia_specialty', $specialty);
		update_post_meta($post_id, '_healthedia_nct', $nct);
		update_post_meta($post_id, '_healthedia_file_url', $upload['url']);
		update_post_meta($post_id, '_healthedia_file_path', $upload['file']);

		return rest_ensure_response(array(
			'success' => true,
			'message' => 'Manuscript submitted successfully for review.',
			'post_id' => $post_id
		));
	}
}
