<?php

namespace ShoppingFeed\ShoppingFeedWCAdvanced\Admin;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

class Fields {

	/** @var array */
	private $fields;

	public function __construct() {

		// add setting page to check if we acctivate or not fields
		//add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_settings_page' ) );

		add_action(
			'woocommerce_product_options_inventory_product_data',
			array(
				$this,
				'fields_product_inventory_tab',
			)
		);
		add_action(
			'woocommerce_admin_process_product_object',
			array(
				$this,
				'save_fields_product_option',
			)
		);

		$this->set_fields();

		add_action(
			'woocommerce_product_after_variable_attributes',
			array(
				$this,
				'fields_product_option_variation',
			),
			10,
			3
		);
		add_action(
			'woocommerce_save_product_variation',
			array(
				$this,
				'save_fields_product_option_variation',
			),
			10,
			2
		);
	}

	public function set_fields() {
		$this->fields = array(
			array(
				'type'                => 'text',
				'id'                  => EAN_FIELD_SLUG,
				'label'               => 'EAN',
				'placeholder'         => '',
				'description'         => '',
				'is_variation_option' => true,
			),
			array(
				'type'                => 'select',
				'id'                  => BRAND_FIELD_SLUG,
				'label'               => 'Brand',
				'placeholder'         => '',
				'description'         => '',
				'is_variation_option' => false,
				'taxonomy'            => Taxonomies::BRAND_TAXONOMY_SLUG,
				'options_callback'    => 'get_brand_options',
			),
		);
	}

	public function fields_product_inventory_tab() {
		if ( empty( $this->fields ) ) {
			return;
		}

		echo '<div class="options_group">';
		foreach ( $this->fields as $field ) {
			$this->add_field( $field );
		}
		echo '</div>';
	}

	/**
	 * @param $field array
	 */
	public function add_field( $field ) {
		$input = array(
			'id'            => $field['id'],
			'label'         => $field['label'],
			'placeholder'   => $field['label'],
			'desc_tip'      => ! empty( $field['description'] ),
			'description'   => $field['description'],
			'class'         => isset( $field['class'] ) ? $field['class'] : '',
			'wrapper_class' => isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '',
		);

		if ( 'text' === $field['type'] ) {
			if ( isset( $field['value'] ) ) {
				$input['value'] = $field['value'];
			}
			woocommerce_wp_text_input( $input );
		} elseif ( 'select' === $field['type'] ) {
			$input['options'] = call_user_func( array( $this, $field['options_callback'] ) );
			$field['class']   = 'select short';
			woocommerce_wp_select( $input );
		}
	}

	public function get_brand_options() {
		$brands = get_terms(
			array(
				'taxonomy'   => Taxonomies::BRAND_TAXONOMY_SLUG,
				'hide_empty' => false,
			)
		);
		if ( ! is_array( $brands ) || ! taxonomy_exists( Taxonomies::BRAND_TAXONOMY_SLUG ) ) {
			return array();
		}

		$options     = array();
		$options[''] = '';
		foreach ( $brands as $brand ) {
			$options[ $brand->term_id ] = $brand->name;
		}

		return $options;
	}


	/**
	 * @param $wc_product \WC_Product|\WC_Product_Variable
	 */
	public function save_fields_product_option( $wc_product ) {
		if ( empty( $this->fields ) ) {
			return;
		}

		foreach ( $this->fields as $field ) {
			$field_post_data = $_POST[ $field['id'] ]; //phpcs:ignore

			if ( ! empty( $field_post_data ) ) {
				if ( ! empty( $field['taxonomy'] ) && taxonomy_exists( $field['taxonomy'] ) ) {
					$term = get_term( $field_post_data, $field['taxonomy'] );
					if ( is_wp_error( $term ) || is_null( $term ) ) {
						continue;
					}
					wp_set_object_terms( $wc_product->get_id(), array( $term->term_id ), $field['taxonomy'] );
				}

				$wc_product->update_meta_data( $field['id'], wc_clean( wp_unslash( $field_post_data ) ) );
				$wc_product->save_meta_data();
			}
		}
	}

	/**
	 * @param $index int
	 * @param $variation_data array
	 * @param $variation \WC_Product_Variation
	 */
	public function fields_product_option_variation( $index, $variation_data, $variation ) {
		if ( empty( $this->fields ) || empty( $variation_data ) ) {
			return;
		}

		foreach ( $this->fields as $field ) {
			if ( empty( $field['is_variation_option'] ) ) {
				continue;
			}

			$key                    = $field['id'] . '_' . $index;
			$field['value']         = get_post_meta( $variation->ID, $field['id'], true );
			$field['id']            = $key;
			$field['class']         = 'short';
			$field['wrapper_class'] = 'form-row form-row-full form-field';
			$this->add_field( $field );
		}
	}


	/**
	 * @param $variation_id int
	 */
	public function save_fields_product_option_variation( $variation_id, $index ) {
		$wc_product = wc_get_product( $variation_id );

		foreach ( $this->fields as $field ) {
			if ( empty( $field['is_variation_option'] ) ) {
				continue;
			}
			$key             = $field['id'] . '_' . $index;
			$field_post_data = $_POST[ $key ]; //phpcs:ignore
			if ( ! empty( $field_post_data ) ) {
				$wc_product->update_meta_data( $field['id'], wc_clean( wp_unslash( $field_post_data ) ) );
				$wc_product->save_meta_data();
			}
		}
	}
}
