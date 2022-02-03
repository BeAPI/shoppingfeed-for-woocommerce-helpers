<?php


namespace ShoppingFeed\ShoppingFeedWCAdvanced\Admin;

use ShoppingFeed\ShoppingFeedWCAdvanced\ShoppingFeedAdvancedHelper;

// Exit on direct access
defined( 'ABSPATH' ) || exit;


class Filters {

	public function __construct() {
		$this->add_ean();
		$this->add_brand();
		$this->add_tracking_number();
		$this->add_tracking_link();
	}

	public function add_ean() {
		if ( empty( ShoppingFeedAdvancedHelper::get_sfa_settings( EAN_FIELD_SLUG ) ) ) {
			return;
		}

		add_filter(
			'shopping_feed_custom_ean',
			function () {
				return EAN_FIELD_SLUG;
			}
		);
	}

	public function add_brand() {
		if ( empty( ShoppingFeedAdvancedHelper::get_sfa_settings( BRAND_FIELD_SLUG ) ) ) {
			return;
		}

		add_filter(
			'shopping_feed_custom_brand_taxonomy',
			function () {
				return Taxonomies::BRAND_TAXONOMY_SLUG;
			}
		);
	}

	public function add_tracking_number() {
		add_filter(
			'shopping_feed_tracking_number',
			function () {
				return TRACKING_NUMBER_FIELD_SLUG;
			}
		);
	}

	public function add_tracking_link() {
		add_filter(
			'shopping_feed_tracking_link',
			function () {
				return TRACKING_LINK_FIELD_SLUG;
			}
		);
	}
}
