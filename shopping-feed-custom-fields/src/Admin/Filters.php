<?php

namespace ShoppingFeed\ShoppingFeedWCCustomFields\Admin;

use ShoppingFeed\ShoppingFeedWCCustomFields\ShoppingFeedCustomFieldsHelper;

class Filters {

	public function __construct() {
		add_filter( 'shopping_feed_extra_fields', [ $this, 'acf_fields' ], 10, 2 );
	}

	/**
	 * @param $fields
	 * @param \WC_Product $wc_product
	 *
	 * @return array
	 */
	public function acf_fields( $fields, $wc_product ) {
		$acf_fields = ShoppingFeedCustomFieldsHelper::get_acf_options();
		if ( empty( $acf_fields ) ) {
			$acf_fields = ShoppingFeedCustomFieldsHelper::get_acf_product_fields();
			if ( empty( $acf_fields ) ) {
				return $fields;
			}
		}

		return array_merge( $fields, $this->get_acf_fields( $acf_fields, $wc_product ) );
	}

	/**
	 * @param array $acf_fields
	 * @param \WC_Product $wc_product
	 *
	 * @return array
	 */
	public function get_acf_fields( $acf_fields, $wc_product ) {
		return array_map( function ( $acf_field ) use ( $wc_product ) {
			$field          = array();
			$field['name']  = sprintf( '%s_%s', 'acf', $acf_field['name'] );
			$field['value'] = get_field( $acf_field['key'], $wc_product->get_id() );
			switch ( $acf_field['type'] ) {
				case 'select':
				case 'checkbox':
					$field['value'] = is_array( $field['value'] ) ? implode( ',', $field['value'] ) : '';
					break;
				case 'true_false':
					$field['value'] = (string) $field['value'];
					break;
				case 'link':
					$field['value'] = ! empty( $field['value']['url'] ) ? $field['value']['url'] : '';
					break;
				default:
					break;
			}

			return $field;
		}, $acf_fields );
	}
}
