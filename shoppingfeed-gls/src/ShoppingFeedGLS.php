<?php

namespace ShoppingFeed\ShoppingFeedWCGLS;

// Exit on direct access
use ShoppingFeed\ShoppingFeedWCGLS\Admin\Database;
use ShoppingFeed\ShoppingFeedWCGLS\Admin\Orders;

defined( 'ABSPATH' ) || exit;

/**
 * Class ShoppingFeed to init plugin
 */
class ShoppingFeedGLS {

    /** @var ShoppingFeedGLS */
    private static $instance;

    /** @var Orders $orders */
    private $orders;

    /** @var Database $database */
    private $database;

    /**
     * ShoppingFeed constructor.
     */
    private function __construct() {
        //Check Compatibility
        if ( ! $this->check_compatibility() ) {
            add_action(
                'admin_notices',
                function () {
                    ?>
                    <div id="message" class="notice notice-error">
                        <p><?php esc_html_e( 'ShoppingFeed and GLS plugins for WooCommerce must be activated', 'shopping-feed-gls' ); ?></p>
                    </div>
                    <?php
                }
            );

            return;
        }

        $this->orders   = new Orders();
        $this->database = new Database();

        //Add settings link
        add_filter( 'plugin_action_links_' . SF_GLS_PLUGIN_BASENAME, [ $this, 'plugin_action_links' ] );
    }

    /**
     * Check if the plugin is compatible
     */
    public function check_compatibility() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if ( is_plugin_active( 'shoppingfeed-for-woocommerce/shoppingfeed.php' )
            && is_plugin_active( 'woocommerce-gls/woocommerce-gls.php' ) ) {
            return true;
        }

        return false;
    }

    /**
     * Get the singleton instance.
     *
     * @return ShoppingFeedGLS
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Add additional action links.
     *
     * @param array $links
     *
     * @return array
     */
    public function plugin_action_links( $links = [] ) {
        $plugin_links = [
            '<a href="' . esc_url( ShoppingFeedGLSHelper::get_setting_link() ) . '">' . esc_html__( 'Settings', 'shopping-feed-advanced' ) . '</a>',
        ];

        return array_merge( $plugin_links, $links );
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
