<?php

namespace ShoppingFeed\ShoppingFeedWCCdiscountFees;

class Totals {

    /** @var $cdiscount_fee_item_line_name */
    private $cdiscount_fee_item_line_name;

    public function __construct() {
        add_action( 'woocommerce_checkout_create_order', [ $this, 'add_cdiscount_fees_meta_to_total' ], 20, 1 );
    }

    /**
     * Add cdiscount fees amount to WC_Order total, since it's not a WC_Order_Item_Fee
     *
     * @param $order
     * @return mixed|void
     */
    private function add_cdiscount_fees_meta_to_total( $order ) {
        $total = $order->get_total();
        $cdiscount_fees = (float) $this->get_cdiscount_fees_amount();

        if ( ! $cdiscount_fees ) {
            return $total;
        }

        $this->add_cdiscount_fees_order_item( $order, $cdiscount_fees );

        return $total + $cdiscount_fees;
    }

    /**
     * Get Cdiscount fees amount
     *
     * @return float
     */
    private function get_cdiscount_fees_amount() {
        $order = new Order();

        return $order->get_cdiscount_fee_meta_value();
    }

    /**
     * Display the Cdiscount meta in WC Order as a new line
     *
     * @return void
     */
    private function add_cdiscount_fees_order_item( $order, $cdiscount_fees ) {
        if ( ! $order instanceof WC_Order || ! $cdiscount_fees ) {
            return;
        }

        $item_array = [
            'order_item_name' => __( $this->cdiscount_fee_item_line_name, 'shopping-feed-cdiscount-fees' ),
            'order_item_type' => 'line_item',
        ];

        wc_add_order_item( $order->get_id(),  $item_array );
    }

}