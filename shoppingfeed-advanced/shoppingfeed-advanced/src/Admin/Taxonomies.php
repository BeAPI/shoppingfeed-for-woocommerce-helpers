<?php


namespace ShoppingFeed\ShoppingFeedWCAdvanced\Admin;

use ShoppingFeed\ShoppingFeedWCAdvanced\ShoppingFeedAdvancedHelper;

class Taxonomies {

	const BRAND_TAXONOMY_SLUG = 'product_brand';

	public function __construct() {

		add_action( 'init', array( $this, 'add_brand' ) );

	}


	public function add_brand() {
		if ( empty( ShoppingFeedAdvancedHelper::get_sfa_settings( BRAND_FIELD_SLUG ) ) ) {
			return;
		}

		$labels = array(
			'name'                       => _x( 'Brands', 'Taxonomy General Name', 'shopping-feed-advanced' ),
			'singular_name'              => _x( 'Brand', 'Taxonomy Singular Name', 'shopping-feed-advanced' ),
			'menu_name'                  => __( 'Brands', 'shopping-feed-advanced' ),
			'all_items'                  => __( 'All Brands', 'shopping-feed-advanced' ),
			'parent_item'                => __( 'Parent Brand', 'shopping-feed-advanced' ),
			'parent_item_colon'          => __( 'Parent Brand:', 'shopping-feed-advanced' ),
			'new_item_name'              => __( 'New Brand Name', 'shopping-feed-advanced' ),
			'add_new_item'               => __( 'Add New Brand', 'shopping-feed-advanced' ),
			'edit_item'                  => __( 'Edit Brand', 'shopping-feed-advanced' ),
			'update_item'                => __( 'Update Brand', 'shopping-feed-advanced' ),
			'view_item'                  => __( 'View Brand', 'shopping-feed-advanced' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'shopping-feed-advanced' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'shopping-feed-advanced' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'shopping-feed-advanced' ),
			'popular_items'              => __( 'Popular Brands', 'shopping-feed-advanced' ),
			'search_items'               => __( 'Search Brands', 'shopping-feed-advanced' ),
			'not_found'                  => __( 'Not Found', 'shopping-feed-advanced' ),
			'no_terms'                   => __( 'No brands', 'shopping-feed-advanced' ),
			'items_list'                 => __( 'Brands list', 'shopping-feed-advanced' ),
			'items_list_navigation'      => __( 'Brands list navigation', 'shopping-feed-advanced' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => false,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
			'meta_box_cb'       => false,
		);

		if ( taxonomy_exists( self::BRAND_TAXONOMY_SLUG ) ) {
			return;
		}
		register_taxonomy( self::BRAND_TAXONOMY_SLUG, array( 'product' ), $args );
	}

}
