<?php

namespace ShoppingFeed\ShoppingFeedWCAdvanced;

use ShoppingFeed\ShoppingFeedWCAdvanced\Admin\Actions;
use ShoppingFeed\ShoppingFeedWCAdvanced\Admin\Fields;
use ShoppingFeed\ShoppingFeedWCAdvanced\Admin\Options;
use ShoppingFeed\ShoppingFeedWCAdvanced\Admin\Filters;
use ShoppingFeed\ShoppingFeedWCAdvanced\Admin\Taxonomies;
use ShoppingFeed\ShoppingFeedWCAdvanced\Admin\Orders;
use ShoppingFeed\ShoppingFeedWCAdvanced\Admin\Order;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

/**
 * Class ShoppingFeed to init plugin
 */
class ShoppingFeedAdvanced {

	/**
	 * @var ShoppingFeedAdvanced
	 */
	private static $instance;

	/** @var Fields $fields */
	private $fields;

	/** @var Options $options */
	private $options;

	/** @var Filters $filters */
	private $filters;

	/** @var Actions $actions */
	private $actions;

	/** @var Taxonomies $taxonomies */
	private $taxonomies;

	/** @var Orders $orders */
	private $orders;

	/** @var Order $order */
	private $order;

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
                        <p><?php esc_html_e( 'ShoppingFeed plugin must be activated', 'shopping-feed-advanced' ); ?></p>
                    </div>
					<?php
				}
			);

			return;
		}

		$this->fields     = new Fields();
		$this->options    = new Options();
		$this->taxonomies = new Taxonomies();
		$this->filters    = new Filters();
		$this->actions    = new Actions();
		$this->orders     = new Orders();
		$this->order      = new Order();

		//Add settings link
		add_filter( 'plugin_action_links_' . SFA_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );
	}

	/**
	 * Check if the plugin is compatible
	 */
	public function check_compatibility() {
		return defined( 'SF_VERSION' );
	}

	/**
	 * Get the singleton instance.
	 *
	 * @return ShoppingFeedAdvanced
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
			'<a href="' . esc_url( ShoppingFeedAdvancedHelper::get_setting_link() ) . '">' . esc_html__( 'Settings', 'shopping-feed-advanced' ) . '</a>',
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
