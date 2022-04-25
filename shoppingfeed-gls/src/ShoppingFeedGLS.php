<?php

namespace ShoppingFeed\ShoppingFeedWCGLS;

// Exit on direct access
use ShoppingFeed\ShoppingFeedWCGLS\Admin\Database;
use ShoppingFeed\ShoppingFeedWCGLS\Admin\Orders;
use ShoppingFeed\ShoppingFeedWCGLS\Admin\GLS;

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

	/** @var GLS $gls */
	private $gls;

	/**
	 * ShoppingFeed constructor.
	 */
	private function __construct() {
		//Check Compatibility
		if ( ! $this->check_compatibility() ) {
			add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );

			return;
		}

		$this->orders   = new Orders();
		$this->database = new Database();
		$this->gls      = new GLS();

		//Add settings link
		add_filter( 'plugin_action_links_' . SF_GLS_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );
	}

	public function display_admin_notice() {
		?>
		<div id="message" class="notice notice-error">
			<p><?php esc_html_e( 'ShoppingFeed and GLS plugins for WooCommerce must be activated', 'shopping-feed-gls' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Check if the plugin is compatible
	 *
	 * @return bool
	 */
	public function check_compatibility(): bool {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ( is_plugin_active( 'shoppingfeed-for-woocommerce/shoppingfeed.php' ) || is_plugin_active( 'shopping-feed/shoppingfeed.php' ) )
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
	public static function get_instance(): ShoppingFeedGLS {
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
	public function plugin_action_links( array $links = array() ): array {
		$links[] = array(
			sprintf(
				'<a href="%s">%s</a>',
				esc_url( ShoppingFeedGLSHelper::get_setting_link() ),
				esc_html__( 'Settings', 'shopping-feed-gls' )
			),
		);

		return $links;
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
