<?php


namespace ShoppingFeed\ShoppingFeedWCCustomFields;


use ShoppingFeed\ShoppingFeedWCCustomFields\Admin\Options;

class ShoppingFeedCustomFieldsHelper {

	const ALLOWED_ACF_FIELD_TYPES = array(
		'text',
		'textarea',
		'number',
		'email',
		'password',
		'url',
		'select',
		'checkbox',
		'radio',
		'true_false',
		'link'
	);

	/**
	 * Return the settings link for plugin
	 * @return string
	 */
	public static function get_setting_link() {
		return admin_url( 'admin.php?page=shopping-feed-custom-fields' );
	}

	public static function get_acf_options() {
		$options = get_option( Options::SFA_OPTIONS );
		if ( empty( $options['acf'] ) ) {
			return array();
		}

		return array_map( function ( $field ) {
			return json_decode( $field, true );
		}, $options['acf'] );
	}

	public static function acf_is_selected( $key, $options ) {
		$key = array_search( $key, array_column( $options, 'key' ) );

		return ! empty( $options[ $key ] );
	}

	/**
	 * @return array
	 */
	public static function get_acf_product_fields() {
		$product_groups = acf_get_field_groups( array( 'post_type' => 'product' ) );
		$fields         = array();
		if ( empty( $product_groups ) ) {
			return $fields;
		}
		foreach ( $product_groups as $group ) {
			$group_fields = acf_get_fields( $group['key'] );
			if ( empty( $group_fields ) ) {
				continue;
			}
			foreach ( $group_fields as $field ) {
				if ( ! in_array( $field['type'], self::ALLOWED_ACF_FIELD_TYPES ) ) {
					continue;
				}

				$fields[] = [
					'type'  => $field['type'],
					'name'  => $field['name'],
					'key'   => $field['key'],
					'label' => $field['label']
				];
			}
		}

		return $fields;
	}
}
