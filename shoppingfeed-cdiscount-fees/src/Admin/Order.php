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
    const SF_CDISCOUNT_FEE_META_KEY = 'sf_cdiscount_fees';

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
		// Make sure order comes from Shopping Feed and is from Cdiscount
		if  ( ! $this->is_cdiscount( $sf_order ) ) {
			return $pre_save_fees;
		}

		// Define Cdiscount fees as meta
		$wc_order->add_meta_data( self::SF_CDISCOUNT_FEE_META_KEY, $fees );

		return true;
	}
}