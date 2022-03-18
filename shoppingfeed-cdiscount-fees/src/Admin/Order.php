<?php

namespace ShoppingFeed\ShoppingFeedWCCdiscountFees\Admin;

use ShoppingFeed\ShoppingFeedWC\Addons\Marketplace;
use ShoppingFeed\ShoppingFeedWC\Addons\Shipping\Marketplaces\Cdiscount;
use ShoppingFeed\ShoppingFeedWC\Orders\Order as SF_Order;

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
		add_filter( 'sf_pre_add_fees', [ $this, 'save_cdiscount_fees_as_meta' ], 10, 4 );
    }

	/**
	 * Save the Cdiscount fees as WC order meta data
	 *
	 * @param $wc_order // A WC_Order object or ID
	 * @param $sf_order // a OrderResource object
	 * @param $fees // Cdiscount Fees
	 *
	 * @return bool
	 */
	public function save_cdiscount_fees_as_meta( $pre_save_fees, $wc_order, $sf_order, $fees ) {
		// Make sure order comes from Shopping Feed
		if  ( ! $sf_order::is_sf_order( $wc_order ) ) {
			return $pre_save_fees;
		}

		// Make sure SF order is from Cdiscount
		if ( ! $this->is_cdiscount( $sf_order ) ) {
			return $pre_save_fees;
		}

		// Define Cdiscount fees as meta
		$wc_order->add_meta_data( $this->sf_discount_fee_meta_key, wp_json_encode( $fees ) );

		$this->sf_cdiscount_fee_meta_value = $fees;

		return true;
	}

    /**
     * Get the order fees meta value
     *
     * @return mixed
     */
    public function get_cdiscount_fee_meta_value() {
        return $this->sf_cdiscount_fee_meta_value;
    }
}