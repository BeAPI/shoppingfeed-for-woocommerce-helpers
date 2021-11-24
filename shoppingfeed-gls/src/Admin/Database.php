<?php

namespace ShoppingFeed\ShoppingFeedWCGLS\Admin;

use WC_Order;

// Exit on direct access
defined('ABSPATH') || exit;

class Database {

    public function __construct() {}

    /**
     * Ensures the GLS table exists
     *
     * @param string $table_name
     * @return bool
     */
    public function check_gls_table_exists( string $table_name ) : bool {
        global $wpdb;

        $query = $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name );

        if ( $wpdb->get_var( $query ) === $table_name ) {
            return true;
        }

        return false;
    }

    /**
     * Inserts the WC_Order fields in the GLS table
     *
     * @param WC_Order $order
     *
     * @return void
     */
    public function insert_order_in_gls_table( WC_Order $order ) : void {
        global $wpdb;

        $data = $this->map_order_fields_for_gls_table( $order );

        $wpdb->insert( SF_GLS_TABLE_NAME, $data['fields'], $data['formats'] );
    }

    /**
     * Creates a mapping for WC_Order fields
     * to be inserted into wp_woocommerce_gls_cart_carrier table
     *
     * @param WC_Order $order
     * @return array
     */
    private function map_order_fields_for_gls_table( WC_Order $order ) : array {
        // TODO: find these fields with test values
        return [
            'fields' => [
                'session_id'              => 'test_session_id', // 255 max characters string, non-nullable
                'customer_id'             => $order->get_customer_id(), // 255 max characters string, non-nullable
                'order_id'                => $order->get_id(), // 11 number int
                'shipping_method_id'      => 'test_shipping_method_id', // 255 max characters string, non-nullable
                'gls_product'             => 'test_gls_product', // 255 max characters string, non-nullable
                'parcel_shop_id'          => 'test_parcel_shop_id', // 255 max characters string, nullable
                'name'                    => $order->get_formatted_shipping_full_name(), // 255 max characters string, nullable
                'address1'                => $order->get_shipping_address_1(), // 255 max characters string, nullable
                'address2'                => $order->get_shipping_address_2(), // 255 max characters string, nullable
                'postcode'                => $order->get_shipping_postcode(), // 255 max characters string, nullable
                'city'                    => $order->get_shipping_city(), // 255 max characters string, nullable
                'phone'                   => $order->get_billing_phone(), // 255 max characters string, nullable
                'phone_mobile'            => 'test_phone_mobile', // 255 max characters string, nullable
                'customer_phone_mobile'   => 'test_customer_phone_mobile', // 255 max characters string, nullable
                'country'                 => $order->get_shipping_country(), // 3 max characters string, nullable
                'parcel_shop_working_day' => 'test_parcel_shop_working_day', // text (65535 max), nullable
            ],
            'formats' => [
                '%s',
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            ]
        ];
    }
}