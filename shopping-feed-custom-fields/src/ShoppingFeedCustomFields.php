<?php

namespace ShoppingFeed\ShoppingFeedWCCustomFields;

// Exit on direct access
use ShoppingFeed\ShoppingFeedWCCustomFields\Admin\Filters;
use ShoppingFeed\ShoppingFeedWCCustomFields\Admin\Options;

defined( 'ABSPATH' ) || exit;

/**
 * Class ShoppingFeed to init plugin
 */
class ShoppingFeedCustomFields {

	/**
	 * @var self
	 */
	private static $instance;

	/** @var Options $options */
	private $options;

	/** @var Filters $filters */
	private $filters;

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
						<p><?php esc_html_e( 'ShoppingFeed and ACF plugins must be activated', 'shopping-feed-custom-fields' ); ?></p>
					</div>
					<?php
				}
			);

			return;
		}

		$this->options = new Options();
		$this->filters = new Filters();

		//Add settings link
		add_filter( 'plugin_action_links_' . SFCF_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );
	}

	/**
	 * Check if the plugin is compatible
     * @return bool
	 */
	public function check_compatibility() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( 'shopping-feed/shoppingfeed.php' ) && is_plugin_active( 'advanced-custom-fields/acf.php' );
	}

	/**
	 * Get the singleton instance.
	 *
	 * @return self
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
	public function plugin_action_links( $links = array() ) {
		$plugin_links = array(
			'<a href="' . esc_url( ShoppingFeedCustomFieldsHelper::get_setting_link() ) . '">' . esc_html__( 'Settings', 'shopping-feed-custom-fields' ) . '</a>',
		);

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
