<?php

namespace ShoppingFeed\ShoppingFeedWCGLS;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

/**
 * Define All needed methods
 * Helper class.
 *
 * @package ShoppingFeed
 */
class ShoppingFeedGLSHelper {
    /**
     * Return the settings link for plugin
     * @return string
     */
    public static function get_setting_link() {
        return admin_url( 'admin.php?page=shopping-feed-advanced' );
    }
}
