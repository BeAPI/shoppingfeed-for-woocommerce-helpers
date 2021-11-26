<?php

namespace ShoppingFeed\ShoppingFeedWCGLS\Admin;

use ShoppingFeed\ShoppingFeedWC\Orders\Order;
use WC_Order;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

class Orders {

	public function __construct() {
		add_action( 'woocommerce_order_status_changed', array( $this, 'write_order_to_gls_table' ), 10, 4 );
	}

	/**
	 * Writes the order as processing in the GLS plugin database table
	 *
	 * @param $order_id
	 * @param $status_from
	 * @param $status_to
	 * @param $order
	 *
	 * @return void
	 */
	public function write_order_to_gls_table( $order_id, $status_from, $status_to, $order ): void {
		// TODO: would be nice to add apply_filters on $order and $status_to
		// Only target status changes to processing
		if ( 'processing' !== $status_to ) {
			return;
		}

		// Make sure $order_id is valid
		if ( ! $order_id ) {
			return;
		}

		// Make sure we are treating only Shopping Feed orders
		if ( ! Order::is_sf_order( $order ) ) {
			return;
		}

		$database = new Database();

		// Make sure GLS table is set and writeable
		if ( ! defined( 'SF_GLS_TABLE_NAME' ) || ! $database->check_gls_table_exists( SF_GLS_TABLE_NAME ) ) {
			return;
		}

		// Everything is ok now, write to the database
		$database->insert_order_in_gls_table( $order );
	}

	/**
	 * Returns SF shipping method's ID
	 *
	 * @param WC_Order $order
	 * @return string
	 */
	public static function get_sf_order_shipping_method_id( WC_Order $order ): string {
		$shipping_method = json_decode( $order->get_meta( 'sf_shipping' ), true );

		if ( ! $shipping_method || ! $shipping_method['method_id'] ) {
			return '';
		}

		return $shipping_method['method_id'];
	}
}
