<?php

namespace ShoppingFeed\ShoppingFeedWCCdiscountFees;

class ShoppingFeedCdiscountFeesHelper {
    /**
     * Return the settings link for plugin
     * @return string
     */
    public static function get_setting_link() {
        return admin_url( 'admin.php?page=shopping-feed-advanced' );
    }
}