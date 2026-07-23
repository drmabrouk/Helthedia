<?php
class Healthedia_Directory_Endpoints {
	public function register_routes() {
		register_rest_route('healthedia/v1', '/directories/researchers', array(
			'methods' => 'GET',
			'callback' => array($this, 'get_researchers'),
			'permission_callback' => '__return_true'
		));
	}

	public function get_researchers($request) {
		$page = $request->get_param('page') ?: 1;
		$per_page = 20;
		$specialty = sanitize_text_field($request->get_param('specialty'));

		$args = array(
			'role'    => 'subscriber',
			'orderby' => 'display_name',
			'order'   => 'ASC',
			'number'  => $per_page,
			'paged'   => $page,
		);

		if (!empty($specialty)) {
			$args['meta_query'] = array(
				array(
					'key' => '_healthedia_specialty',
					'value' => $specialty,
					'compare' => '='
				)
			);
		}

		$user_query = new WP_User_Query( $args );
		$users = $user_query->get_results();

		$data = array();
		foreach ($users as $user) {
			require_once HEALTHEDIA_PLUGIN_DIR . 'modules/Profiles/class-profile-model.php';
			require_once HEALTHEDIA_PLUGIN_DIR . 'modules/Profiles/class-profile-verification.php';
			$metrics = Healthedia_Profile_Model::get_metrics($user->ID);

			$data[] = array(
				'id' => $user->ID,
				'name' => $user->display_name,
				'specialty' => get_user_meta($user->ID, '_healthedia_specialty', true),
				'verified' => Healthedia_Profile_Verification::is_verified($user->ID),
				'views' => $metrics->views,
				'url' => home_url('/profile/' . $user->ID)
			);
		}

		return rest_ensure_response(array(
			'data' => $data,
			'total' => $user_query->get_total()
		));
	}
}
