<?php
class Healthedia_Article_CPT {
	public function register() {
		$labels = array(
			'name'               => 'Journal Articles',
			'singular_name'      => 'Journal Article',
			'menu_name'          => 'Journal Articles',
			'name_admin_bar'     => 'Journal Article',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Article',
			'new_item'           => 'New Article',
			'edit_item'          => 'Edit Article',
			'view_item'          => 'View Article',
			'all_items'          => 'All Articles',
			'search_items'       => 'Search Articles',
			'not_found'          => 'No articles found.',
			'not_found_in_trash' => 'No articles found in Trash.'
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'journal/article' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' )
		);

		register_post_type( 'healthedia_article', $args );

		register_taxonomy('healthedia_specialty', 'healthedia_article', array(
			'label' => 'Specialties',
			'hierarchical' => true,
			'show_in_rest' => true
		));
	}
}
