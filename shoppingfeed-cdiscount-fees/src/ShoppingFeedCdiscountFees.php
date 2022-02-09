<?php

namespace ShoppingFeed\ShoppingFeedWCCdiscountFees;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

use ShoppingFeed\ShoppingFeedWCCdiscountFees\Admin\Order;
use ShoppingFeed\ShoppingFeedWCCdiscountFees\Admin\Totals;
use ShoppingFeed\ShoppingFeedWCCdiscountFees\ShoppingFeedCdiscountFeesHelper;

/**
 * Class ShoppingFeed to init plugin
 */
class ShoppingFeedCdiscountFees {

    /** @var ShoppingFeedCdiscountFees */
    private static $instance;

    /** @var Order $order */
    private $order;

    /** @var Totals $totals */
    private $totals;

    /**
     * ShoppingFeed constructor.
     */
    private function __construct() {
        //Check Compatibility
        if ( ! $this->check_compatibility() ) {
            add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );

            return;
        }

        $this->order  = new Order();
        $this->totals = new Totals();
    }

    public function display_admin_notice() {
        ?>
        <div id="message" class="notice notice-error">
            <p><?php esc_html_e( 'ShoppingFeed plugin must be activated', 'shopping-feed-cdiscount-fees' ); ?></p>
        </div>
        <?php
    }

    /**
     * Check if the plugin is compatible
     *
     * @return bool
     */
    public function check_compatibility() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if ( ( is_plugin_active( 'shoppingfeed-for-woocommerce/shoppingfeed.php' ) || is_plugin_active( 'shopping-feed/shoppingfeed.php' ) ) ) {
            return true;
        }

        return false;
    }

    /**
     * Get the singleton instance.
     *
     * @return ShoppingFeedWCCdiscountFees
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Singleton instance can't be cloned.
     */
    private function __clone() {
    }

    /**
     * Singleton instance can't be serialized.
     */
    private function __wakeup() {
    }
}