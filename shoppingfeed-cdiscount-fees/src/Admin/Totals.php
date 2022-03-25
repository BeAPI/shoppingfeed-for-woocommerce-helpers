<?php

namespace ShoppingFeed\ShoppingFeedWCCdiscountFees\Admin;

use ShoppingFeed\ShoppingFeedWC\ShoppingFeedHelper;
use ShoppingFeed\ShoppingFeedWCCdiscountFees\Admin\Order;

class Totals {

	public function __construct() {
		add_action( 'sf_before_add_order', [ $this, 'add_cdiscount_fees_meta_to_total' ], 20, 1 );
		add_action( 'woocommerce_admin_order_totals_after_shipping', [ $this, 'display_cdiscount_fees_as_total_line' ], 10, 1 );
	}

	/**
	 * Add cdiscount fees amount to WC_Order total, since it's not a WC_Order_Item_Fee
	 *
	 * @param \WC_Order $wc_order
	 *
	 * @return mixed|void
	 */
	public function add_cdiscount_fees_meta_to_total( $wc_order ) {
		$total = $wc_order->get_total();

		$cdiscount_fees = (float) $wc_order->get_meta( Order::SF_CDISCOUNT_FEE_META_KEY, true );

		if ( empty( $cdiscount_fees ) ) {
			return;
		}

		$wc_order->set_total( $total + $cdiscount_fees );

		$wc_order->save();
	}

	/**
	 * Display Cdiscount Fees as new row in order totals
	 *
	 * @return void
	 */
	public function display_cdiscount_fees_as_total_line( $order_id ) {
		// Get cdiscount fees meta
		$wc_order            = wc_get_order( $order_id );
		$cdiscount_fees_meta = $wc_order->get_meta( Order::SF_CDISCOUNT_FEE_META_KEY, true );

		// Do not display anything if meta is empty
		if ( empty( $cdiscount_fees_meta ) ) {
			return;
		}

		?>
		<tr>
			<td class="label"><?php _e( 'Facilités de paiement Cdiscount', 'shopping-feed-cdiscount-fees' ); ?>:</td>
			<td width="1%"></td>
			<td class="total" style="font-weight: 700;"><?php echo wc_price( (float) $cdiscount_fees_meta ); ?></td>
		</tr>
		<?php
	}
}