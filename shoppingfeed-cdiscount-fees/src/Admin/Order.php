<?php

namespace ShoppingFeed\ShoppingFeedWCCdiscountFees;

class Order {

    /**
     * @var $sf_cdiscount_fee_meta_key
     */
    private $sf_discount_fee_meta_key;

    /**
     * @var $sf_cdiscount_fee_meta_value
     */
    public $sf_cdiscount_fee_meta_value;

    public function __construct() {
        $this->sf_discount_fee_meta_key = 'sf_cdiscount_fees';

        add_action( 'sf_after_order_add_fee_item', [ $this, 'set_cdiscount_fees_as_sf_meta' ], 10, 3 );
    }

    private function set_cdiscount_fees_as_sf_meta( $wc_fees, $wc_order, $cdiscount_fee ) {
        // Delete standard WC Fees
        $wc_order->remove_item( $wc_fees->get_id() );

        // Define Cdiscount fees as meta
        $wc_order->add_meta_data( $this->sf_discount_fee_meta_key, wp_json_encode( $cdiscount_fee ) );

        $this->sf_cdiscount_fee_meta_value = $cdiscount_fee;
    }

    public function get_cdiscount_fee_meta_value() {
        return $this->sf_cdiscount_fee_meta_value;
    }
}