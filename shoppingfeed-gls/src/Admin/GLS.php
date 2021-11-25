<?php

namespace ShoppingFeed\ShoppingFeedWCGLS\Admin;

use ShoppingFeed\ShoppingFeedWC\Orders\Order;
use WC_Order;
use WC_Gls;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

class GLS {
	public function __construct() {}

	public static function get_gls_product( WC_Order $order ) {
		$shipping_method_id = Orders::get_sf_order_shipping_method_id( $order );

		return WC_Gls::get_gls_product( $shipping_method_id, 'FR' );
	}

}
