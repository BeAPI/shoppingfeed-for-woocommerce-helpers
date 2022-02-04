<?php
/**
 * Plugin Name:      ShoppingFeed - Cdiscount Fees
 * Plugin URI:       https://wordpress.org/plugins/shoppingfeed/
 * Description:      Sets separate fees in SF metas for Cdiscount fees
 * Author:           Shopping-Feed
 * Author URI:       https://www.shopping-feed.com/
 * Text Domain:      shoppingfeed-cdiscount-fees
 * Domain Path:      /languages
 * Version:          1.0
 * Requires at least WP: 5.2
 * Requires at least WooCommerce: 3.8 (3.9/4.0)
 * Requires PHP:     5.6
 * License:          GPLv3 or later
 * License URI:      https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace ShoppingFeed\ShoppingFeedWCCdiscountFees;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

// Load composer autoload
if ( file_exists( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
}

global $wpdb;

define( 'SF_CDISCOUNT_FEES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SF_CDISCOUNT_FEES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SF_CDISCOUNT_FEES_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Plugin bootstrap function.shoppingfeed-advanced/src/ShoppingFeedAdvanced.php
 */
function init() {
    load_plugin_textdomain( 'shopping-feed-cdiscount-fees', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

    ShoppingFeedCdiscountFees::get_instance();
}

\add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );