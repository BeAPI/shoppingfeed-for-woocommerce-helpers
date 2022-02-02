<?php

namespace ShoppingFeed\ShoppingFeedWCCdiscountFees;

class Totals {

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

        if ( ! $this->get_cdiscount_fees_amount() ) {
            return $total;
        }

        return $total + $this->get_cdiscount_fees_amount();
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
    private function display_cdiscount_fees_line_in_order() {
        // TODO: display a new line for Cdiscount meta fees in WC order in back-office
    }

}