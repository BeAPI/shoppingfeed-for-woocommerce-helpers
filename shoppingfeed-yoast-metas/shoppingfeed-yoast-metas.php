<?php
/**
 * Plugin Name:     ShoppingFeed Yoast Metas
 * Plugin URI:      https://wordpress.org/plugins/shoppingfeed/
 * Description:     Add yoast metas to the feed generted by ShoppingFeed plugin
 * Author:          Shopping-Feed
 * Author URI:      https://www.shopping-feed.com/
 * Domain Path:     /languages
 * Version:         1.0.0
 * Requires at least WP: 5.2
 * Requires at least WooCommerce: 3.8 (3.9/4.0)
 * Requires PHP:      5.6
 * License:         GPLv3 or later
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace ShoppingFeed\ShoppingFeedWCYoastMetas;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

function init() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if ( ! is_plugin_active( 'shopping-feed/shoppingfeed.php' ) ) {
		add_action(
			'admin_notices',
			function () {
				?>
                <div id="message" class="notice notice-error">
                    <p><?php esc_html_e( 'ShoppingFeed plugin must be activated', 'shopping-feed-advanced' ); ?></p>
                </div>
				<?php
			}
		);

		return;
	}

	if ( ! is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
		add_action(
			'admin_notices',
			function () {
				?>
                <div id="message" class="notice notice-error">
                    <p><?php esc_html_e( 'Yoast plugin must be activated', 'shopping-feed-advanced' ); ?></p>
                </div>
				<?php
			}
		);

		return;
	}


	add_filter( 'shopping_feed_extra_fields', function ( $fields, $product ) {
		/** @var \WC_Product $product */
		$fields[] = [
			'name'  => 'meta-title',
			'value' => ( new \WPSEO_Replace_Vars() )->replace( \WPSEO_Meta::get_value( 'title', $product->get_id() ), $product )
		];
		$fields[] = [
			'name'  => 'meta-description',
			'value' => ( new \WPSEO_Replace_Vars() )->replace( \WPSEO_Meta::get_value( 'metadesc', $product->get_id() ), $product )
		];

		return $fields;
	}, 10, 2 );
}

\add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );