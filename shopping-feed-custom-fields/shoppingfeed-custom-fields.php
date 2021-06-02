<?php
/**
 * Plugin Name:     ShoppingFeed Custom Fields
 * Plugin URI:      https://wordpress.org/plugins/shoppingfeed/
 * Description:     Map custom fields for ShoppingFeed plugin
 * Author:          Shopping-Feed
 * Author URI:      https://www.shopping-feed.com/
 * Text Domain:     shopping-feed-custom-fields
 * Domain Path:     /languages
 * Version:         1.0.0
 * License:         GPLv3 or later
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace ShoppingFeed\ShoppingFeedWCCustomFields;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

// Load composer autoload
if ( file_exists( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
}

define( 'SFCF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SFCF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SFCF_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Plugin bootstrap function.shoppingfeed-custom-fields/src/ShoppingFeedCustomFields.php
 */
function init() {
	load_plugin_textdomain( 'shopping-feed-custom-fields', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	ShoppingFeedCustomFields::get_instance();
}

\add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );
