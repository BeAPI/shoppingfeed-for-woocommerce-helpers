<?php

namespace ShoppingFeed\ShoppingFeedWCAdvanced\Admin;

use ShoppingFeed\ShoppingFeedWCAdvanced\ShoppingFeedAdvancedHelper;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

class Actions {

	public function __construct() {
		add_action( 'admin_post_sfa_migrate', array( $this, 'migrate_old_data_add_action' ) );
		add_action( 'migrate_old_data_action', array( $this, 'migrate_old_data' ) );
	}

	public function migrate_old_data_add_action() {
		$id_action = as_schedule_single_action( time(), 'migrate_old_data_action' );
		update_option( LAST_MIGRATION_ACTION, $id_action );
		wp_safe_redirect( ShoppingFeedAdvancedHelper::get_setting_link(), 302 );
	}

	public function migrate_old_data() {
		$old_ean_meta_key          = 'custom_text_field_ean';
		$old_variable_ean_meta_key = '_text_field_ean';
		$old_brand_meta_key        = 'custom_text_field_brand';

		$wc_products = wc_get_products( array( 'limit' => - 1 ) );
		/** @var \WC_Product|\WC_Product_Variable $wc_product */
		foreach ( $wc_products as $wc_product ) {
			if ( $wc_product instanceof \WC_Product_Variable ) {
				$variations = $wc_product->get_available_variations();
				if ( ! empty( $variations ) ) {
					foreach ( $variations as $wc_product_variation ) {
						$wc_product_variation = new \WC_Product_Variation( $wc_product_variation['variation_id'] );
						$old_ean_meta_value   = $wc_product_variation->get_meta( $old_variable_ean_meta_key );
						if ( empty( $old_ean_meta_value ) ) {
							continue;
						}
						$wc_product_variation->update_meta_data( EAN_FIELD_SLUG, $old_ean_meta_value );
						$wc_product_variation->save_meta_data();
					}
				}
			} else {
				$old_ean_meta_value = $wc_product->get_meta( $old_ean_meta_key );
				if ( ! empty( $old_ean_meta_value ) ) {
					$wc_product->update_meta_data( EAN_FIELD_SLUG, $old_ean_meta_value );
					$wc_product->save_meta_data();
				}
			}

			$old_brand_meta_value = $wc_product->get_meta( $old_brand_meta_key );
			if ( ! empty( $old_brand_meta_value ) ) {
				$brand_term_id = term_exists( $old_brand_meta_value, Taxonomies::BRAND_TAXONOMY_SLUG );
				if ( ! $brand_term_id ) {
					$brand_term_id = wp_insert_term( $old_brand_meta_value, Taxonomies::BRAND_TAXONOMY_SLUG );
				}
				if ( is_wp_error( $brand_term_id ) || is_null( $brand_term_id ) ) {
					continue;
				}
				if ( is_array( $brand_term_id ) ) {
					$brand_term_id = (int) $brand_term_id['term_id'];
				}
				wp_set_object_terms( $wc_product->get_id(), array( $brand_term_id ), Taxonomies::BRAND_TAXONOMY_SLUG );
				$wc_product->update_meta_data( BRAND_FIELD_SLUG, $brand_term_id );
			}
			$wc_product->save_meta_data();
		}
	}


}
