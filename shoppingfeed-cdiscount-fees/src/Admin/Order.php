<?php

namespace ShoppingFeed\ShoppingFeedWCCdiscountFees\Admin;

use ShoppingFeed\ShoppingFeedWC\Addons\Marketplace;
use ShoppingFeed\ShoppingFeedWC\Addons\Shipping\Marketplaces\Cdiscount;
use ShoppingFeed\ShoppingFeedWC\Orders\Order as SFDefaultOrder;

class Order {

    use Marketplace;

    /**
     * @var $sf_cdiscount_fee_meta_key
     */
    private $sf_discount_fee_meta_key = 'sf_cdiscount_fees';

    /**
     * @var $sf_cdiscount_fee_meta_value
     */
    public $sf_cdiscount_fee_meta_value;

    public function __construct() {
        add_action( 'sf_after_order_add_fee_item', [ $this, 'set_cdiscount_fees_as_sf_meta' ], 10, 3 );
    }

    /**
     * Remove default WC Fee, add it as order meta instead
     *
     * @param WC_Order_Item_Fee $wc_fees
     * @param WC_Order $wc_order
     * @param $cdiscount_fees
     * @return void
     */
    private function set_cdiscount_fees_as_sf_meta( $wc_fees, $wc_order, $cdiscount_fees ) {
        // Make sure order is from Shopping Feed
        if  ( ! SFDefaultOrder::is_sf_order( $wc_order ) ) {
            return;
        }

        // Make sure SF order is from Cdiscount
        if ( ! $this->is_cdiscount( $wc_order ) ) {
            return;
        }

        // Delete standard WC Fees
        $wc_order->remove_item( $wc_fees->get_id() );

        // Define Cdiscount fees as meta
        $wc_order->add_meta_data( $this->sf_discount_fee_meta_key, wp_json_encode( $cdiscount_fees ) );

        $this->sf_cdiscount_fee_meta_value = $cdiscount_fees;
    }

    /**
     * @return mixed
     */
    public function get_cdiscount_fee_meta_value() {
        return $this->sf_cdiscount_fee_meta_value;
    }
}