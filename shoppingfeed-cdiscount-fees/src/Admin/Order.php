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
		add_filter( 'sf_pre_add_fees', [ $this, 'is_cdiscount_sf_order' ], 10, 2 );
    }

    /**
     * Add Cdiscount fees as meta
     *
     * @param WC_Order_Item_Fee $fees
     * @param WC_Order $wc_order
     * @param $sf_order
     *
     * @return void
     */
    private function set_cdiscount_fees_as_sf_meta( $fees, $wc_order, $sf_order ) {
		// If not a Shopping Feed order from Cdiscout, do nothing
        if ( ! $this->is_cdiscount_sf_order( $wc_order, $sf_order ) ) {
			return;
        }

        // Define Cdiscount fees as meta
        $wc_order->add_meta_data( $this->sf_discount_fee_meta_key, wp_json_encode( $fees ) );

        $this->sf_cdiscount_fee_meta_value = $fees;
    }

	/**
	 * Check if it is a Shopping Feed order from Cdiscount
	 *
	 * @param $wc_order
	 * @param $sf_order
	 *
	 * @return bool
	 */
	private function is_cdiscount_sf_order( $wc_order, $sf_order  ) {
		// Make sure order comes from Shopping Feed
		if  ( ! $sf_order->is_sf_order( $wc_order ) ) {
			return false;
		}

		// Make sure SF order is from Cdiscount
		if ( ! $this->is_cdiscount( $sf_order ) ) {
			return false;
		}

		return true;
	}

    /**
     * @return mixed
     */
    public function get_cdiscount_fee_meta_value() {
        return $this->sf_cdiscount_fee_meta_value;
    }
}