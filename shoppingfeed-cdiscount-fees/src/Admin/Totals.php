<?php

namespace ShoppingFeed\ShoppingFeedWCCdiscountFees\Admin;

class Totals {

    public function __construct() {
        add_action( 'sf_before_add_order', [ $this, 'add_cdiscount_fees_meta_to_total' ], 20, 1 );
    }

	/**
	 * Add cdiscount fees amount to WC_Order total, since it's not a WC_Order_Item_Fee
	 *
	 * @param \WC_Order $wc_order
	 *
	 * @return mixed|void
	 * @throws \WC_Data_Exception
	 * @throws \Exception
	 */
    public function add_cdiscount_fees_meta_to_total( $wc_order ) {
        $total = $wc_order->get_total();

        $cdiscount_fees = (float) $wc_order->get_meta( Order::SF_CDISCOUNT_FEE_META_KEY );

        if ( empty( $cdiscount_fees ) ) {
            return;
        }

        $this->add_cdiscount_fees_order_item( $wc_order, $cdiscount_fees );

        $wc_order->set_total( $total + $cdiscount_fees );

		$wc_order->save();
    }

	/**
	 * Add Cdiscount fee value as WC item line
	 *
	 * @return void
	 * @throws \Exception
	 */
    private function add_cdiscount_fees_order_item( $order, $cdiscount_fees ) {
        if ( ! $order instanceof \WC_Order || ! $cdiscount_fees ) {
            return;
        }

        $item_array = [
            'order_item_name' => sprintf( __( 'Facilités de paiement Cdiscount : %s', 'shopping-feed-cdiscount-fees' ), $cdiscount_fees ),
            'order_item_type' => 'line_item',
        ];

        wc_add_order_item( $order->get_id(),  $item_array );
    }

}