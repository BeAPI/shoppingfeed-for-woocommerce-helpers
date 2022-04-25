<?php

namespace ShoppingFeed\ShoppingFeedWCGLS\Admin;

use WC_Order;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

class Database {

	public function __construct() {}

	/**
	 * Ensures the GLS table exists
	 *
	 * @param string $table_name
	 * @return bool
	 */
	public function check_gls_table_exists( string $table_name ): bool {
		global $wpdb;

		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name );

		if ( $wpdb->get_var( $query ) === $table_name ) { // WPCS: unprepared SQL OK. False positive
			return true;
		}

		return false;
	}

	/**
	 * Inserts the WC_Order fields in the GLS table
	 *
	 * @param WC_Order $order
	 *
	 * @return void
	 */
	public function write_order_in_gls_table( WC_Order $order ): void {
		// Make sure we get an order ID
		if ( ! $order->get_id() ) {
			return;
		}

		// Create fields and formats mapping from WC_Order to GLS table
		$data = $this->map_order_fields_for_gls_table( $order );

		// Make sure fields and formats data are correct
		if ( ! $data['fields'] || ! $data['formats'] ) {
			return;
		}

		// Either update or insert into the GLS table
		$this->insert_or_update_in_gls_table(
			SF_GLS_TABLE_NAME, // table name
			$order,
			$data['fields'], // fields
			$data['formats'] // formats
		);
	}

	/**
	 * Creates a mapping for WC_Order fields
	 * to be inserted into wp_woocommerce_gls_cart_carrier table
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	private function map_order_fields_for_gls_table( WC_Order $order ): array {
		return array(
			'fields' => array(
				'session_id'              => '', // 255 max characters string, non-nullable
				'customer_id'             => $order->get_customer_id(), // 255 max characters string, non-nullable
				'order_id'                => $order->get_id(), // 11 number int
				'shipping_method_id'      => Orders::get_sf_order_shipping_method_id( $order ), // 255 max characters string, non-nullable
				'gls_product'             => GLS::get_gls_product( $order ), // 255 max characters string, non-nullable
				'parcel_shop_id'          => null, // 255 max characters string, nullable
				'name'                    => $order->get_formatted_shipping_full_name(), // 255 max characters string, nullable
				'address1'                => $order->get_shipping_address_1(), // 255 max characters string, nullable
				'address2'                => $order->get_shipping_address_2(), // 255 max characters string, nullable
				'postcode'                => $order->get_shipping_postcode(), // 255 max characters string, nullable
				'city'                    => $order->get_shipping_city(), // 255 max characters string, nullable
				'phone'                   => $order->get_billing_phone(), // 255 max characters string, nullable
				'phone_mobile'            => null, // 255 max characters string, nullable
				'customer_phone_mobile'   => '', // 255 max characters string, non-nullable
				'country'                 => $order->get_shipping_country(), // 3 max characters string, nullable
				'parcel_shop_working_day' => null, // text (65535 max), nullable
			),
			'formats' => array(
				'%s',
				'%s',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			),
		);
	}

	/**
	 * Checks if we want to perform an update or insert operation, and returns the operation status code
	 *
	 * @param string $table_name
	 * @param WC_Order $order
	 * @param array $fields
	 * @param array $formats
	 *
	 * @return void
	 */
	private function insert_or_update_in_gls_table( string $table_name, WC_Order $order, array $fields, array $formats ): void {
		global $wpdb;

		$sql = $wpdb->prepare( "SELECT order_id FROM `$table_name` WHERE order_id = %d;", $order->get_id() );
		$order_exists = $wpdb->get_results( $sql ); // WPCS: unprepared SQL OK

		// If it doesn't exist, insert it
		if ( empty( $order_exists ) ) {
			$wpdb->insert( $table_name, $fields, $formats );
		} else { // if it exists update it
			$wpdb->update( $table_name, $fields, array( 'order_id' => $order->get_id() ), $formats, '%d' );
		}
	}
}
