<?php
/**
 * Plugin Name:     ShoppingFeed Advanced
 * Plugin URI:      https://wordpress.org/plugins/shoppingfeed/
 * Description:     Recongnize custom fields for ShoppingFeed plugin
 * Author:          Shopping-Feed
 * Author URI:      https://www.shopping-feed.com/
 * Text Domain:     shopping-feed-advanced
 * Domain Path:     /languages
 * Version:         6.0.7
 * Requires at least WP: 5.2
 * Requires at least WooCommerce: 3.8 (3.9/4.0)
 * Requires PHP:      5.6
 * License:         GPLv3 or later
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace ShoppingFeed\ShoppingFeedWCAdvanced;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

// Load composer autoload
if ( file_exists( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
}

define( 'SFA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SFA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SFA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'EAN_FIELD_SLUG', 'sf_advanced_ean_field' );
define( 'BRAND_FIELD_SLUG', 'sf_advanced_brand_field' );
define( 'TRACKING_NUMBER_FIELD_SLUG', 'sf_advanced_tracking_number_field' );
define( 'TRACKING_LINK_FIELD_SLUG', 'sf_advanced_tracking_link_field' );
define( 'LAST_MIGRATION_ACTION', 'sf_last_migration_action' );

/**
 * Plugin bootstrap function.shoppingfeed-advanced/src/ShoppingFeedAdvanced.php
 */
function init() {
	load_plugin_textdomain( 'shopping-feed-advanced', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	ShoppingFeedAdvanced::get_instance();
}

\add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );
