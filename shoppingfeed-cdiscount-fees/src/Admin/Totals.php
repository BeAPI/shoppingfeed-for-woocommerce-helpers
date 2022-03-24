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

		$cdiscount_fees = (float) $wc_order->get_meta( Order::SF_CDISCOUNT_FEE_META_KEY );

		if ( empty( $cdiscount_fees ) ) {
			ShoppingFeedHelper::get_logger()->warning(
				sprintf(
				/* translators: %1$1s: WC_Order ID */
					__( 'Cdiscount Fees for WC_Order %1$1s are empty.', 'shopping-feed-cdiscount-fees' ),
					$wc_order->get_id()
				),
				array(
					'source' => 'sshopping-feed-cdiscount-fees',
				)
			);

			return;
		}

		$this->add_cdiscount_fees_order_item( $wc_order, $cdiscount_fees );

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
		$cdiscount_fees_meta = $wc_order->get_meta( Order::SF_CDISCOUNT_FEE_META_KEY );

		// Do not display anything if meta is empty
		if ( empty( $cdiscount_fees_meta ) ) {
			return;
		}

		?>
		<tr>
			<td class="label"><?php _e( 'Facilités de paiement Cdiscount', 'shopping-feed-cdiscount-fees' ); ?>:</td>
			<td width="1%"></td>
			<td class="total"><?php echo esc_html( $cdiscount_fees_meta ); ?></td>
		</tr>
		<?php
	}

	/**
	 * Add Cdiscount fee value as WC item line
	 *
	 * @return void
	 */
	private function add_cdiscount_fees_order_item( $order, $cdiscount_fees ) {
		if ( ! $order instanceof \WC_Order || ! $cdiscount_fees ) {
			ShoppingFeedHelper::get_logger()->warning(
				sprintf(
				/* translators: %1$1s: WC_Order ID */
					__( 'Cdiscount Fees for WC_Order %1$1s are empty, or order parameter is not of WC_Order type.', 'shopping-feed-cdiscount-fees' ),
					$order->get_id()
				),
				array(
					'source' => 'sshopping-feed-cdiscount-fees',
				)
			);

			return;
		}

		$item_array = [
			'order_item_name' => sprintf( __( 'Facilités de paiement Cdiscount : %s', 'shopping-feed-cdiscount-fees' ), $cdiscount_fees ),
			'order_item_type' => 'line_item',
		];

		wc_add_order_item( $order->get_id(),  $item_array );
	}

}