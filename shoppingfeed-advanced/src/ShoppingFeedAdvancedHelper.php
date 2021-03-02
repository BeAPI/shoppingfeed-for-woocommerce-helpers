<?php

namespace ShoppingFeed\ShoppingFeedWCAdvanced;

use ShoppingFeed\ShoppingFeedWCAdvanced\Admin\Options;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

/**
 * Define All needed methods
 * Helper class.
 *
 * @package ShoppingFeed
 */
class ShoppingFeedAdvancedHelper {

	public static function get_last_action_data() {
		$last_action_id = get_option( LAST_MIGRATION_ACTION );
		if ( empty( $last_action_id ) ) {
			return array();
		}

		try {
			return array(
				'date'   => \ActionScheduler::store()->get_date( $last_action_id )->format( 'Y-m-d H:i:s' ),
				'status' => \ActionScheduler::store()->get_status( $last_action_id ),
			);
		} catch ( \Exception $exception ) {
			return array();
		}
	}

	public static function get_sfa_settings( $param ) {
		$options = get_option( Options::SFA_OPTIONS );
		if ( empty( $param ) ) {
			return $options;
		}

		if ( empty( $options[ $param ] ) ) {
			return false;
		}

		return $options[ $param ];
	}

	/**
	 * Return the settings link for plugin
	 * @return string
	 */
	public static function get_setting_link() {
		return admin_url( 'admin.php?page=shopping-feed-advanced' );
	}
}
